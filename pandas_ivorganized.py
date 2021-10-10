#!/usr/bin/env python
import pandas as pd
import numpy as np
import sys
import os
import mysql.connector
from datetime import datetime
import pymysql
import re
import py_connection


mycursor = py_connection.mydb.cursor()
count = 0
# pd.set_option('display.max_columns', 20)
# pd.set_option('display.width', 1000)
try:
    file_path = sys.argv[1]
    df = pd.read_excel(file_path)
except:
    print('File not found')
    failed = pd.DataFrame({'Errors': ['File not found', sys.exc_info()[0]]})
    failed.to_excel('excels/failed_'+sys.argv[1]+'.xlsx', index=False)
    print(count)
    sys.exit(1)

try:
    expected_headers = ['Sr. No.', 'Dept.', 'Initials', 'Name/s of  Author /s / Faculty', 'Name of Industry', 'City',
                        'Purpose', 'Target Audience', 'Staff', 'Start Date', 'End Date', 'No. of Participants', 'Sponsor Details']
    file_headers = list(df.columns.values)
    print(expected_headers)
    print(file_headers)
    for i in range(len(expected_headers)):
        if(not expected_headers[i] == file_headers[i]):
            raise KeyError('Header format error in "' +
                           file_headers[i]+'" Expected "'+expected_headers[i]+'"')
    df.dropna(axis=0, how='all', thresh=None, subset=None, inplace=True)
# print(df.head())
    l = list(df.columns)
# print(df.iloc[4:6,1])
    for i in l:
        if i[0:3] == '201' or i[0] == '1':
            name = i
            headers = df.iloc[0]
            # print(headers)
            df.columns = headers
            df = df.iloc[1:]
            # print(df.info())
            # print(df.head())
    # print(df.head())
    df = df[df.columns.dropna()]
    df.dropna(subset=["Name/s of  Author /s / Faculty"], axis=0, inplace=True)
    # print(df.head(1))
    # print(df.info())
    # print(df.shape[0])
    # df['Sr. No.'] = np.arange(len(df))
    # print(df.isnull().head())
    df.fillna('NA', inplace=True)
    # print(df)
except Exception as e:
    print('Pre-processing error')
    print(sys.exc_info())
    failed = pd.DataFrame(
        {'Errors': ['Pre-processing error', sys.exc_info()[0], e.args]})
    print(failed)
    print(count)
    failed.to_excel('excels/failed_'+sys.argv[1]+'.xlsx', index=False)
    # failed.to_excel('failed_'+sys.argv[1]+'.xlsx',index=False)
    sys.exit(1)

failed = pd.DataFrame(columns=df.columns)
failed['Errors'] = []

# print(df)
# enter every line into database
for i in range(0, df.shape[0]):
    faclist = list(df.iloc[i, 2:])

    try:
        # pop initials
        faclist.pop(0)

        # separate coauthors
        authors = faclist[0].split(',')
        authname = authors[0].split('.')[-1].strip()
        # print("author"+authname)
        q1 = "SELECT Fac_ID from facultydetails where F_NAME like '%"+authname+"%'"
        mycursor.execute(q1)
        result = mycursor.fetchone()
        facid = ""
        if result and len(result) == 1:
            facid = int(result[0])
        #print('Authname: '+str(authname)+'Facid:'+str(facid))
        if facid == '':
            raise Exception('Fac_ID not found/empty')
        faclist.insert(0, facid)
        # print(faclist)
        # pop faculty name
        faclist.pop(1)
    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Author/ID formatting error\n' + \
            str(e.args)
        continue

    # date formatting
    try:
        start_date = str(faclist.pop(6))
        end_date = str(faclist.pop(6))
        if start_date != '' and start_date != 'NA':
            date_from = datetime.strptime(start_date, '%Y-%m-%d %H:%M:%S')
            year = date_from.strftime("%Y")
            month = date_from.strftime("%m")
            #start_date = datetime.strftime(date_from, '%Y-%m-%d')
        if end_date != '' and end_date != 'NA':
            date_to = datetime.strptime(end_date, '%Y-%m-%d %H:%M:%S')
            delta = date_to - date_from
            noofdays = delta.days
            noofweeks = int(noofdays)//7
            #end_date = datetime.strftime(date_to, '%Y-%m-%d')
        # print(date_str)
        faclist.insert(6, str(date_from))
        faclist.insert(7, str(date_to))
    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Date formatting error\n' + str(e.args) +"          "+ str(faclist)
        continue

    # deal with paricipants null values
    if faclist[8] == 'NA':
        faclist[8] = 0

    # dealing with sponsor details
    try:
        if faclist[9] == 'NA' or faclist[9] == '':
            faclist[10] = 'not sponsored'
        else:
            faclist[10] = 'sponsored'
    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Sponsor field formatting error\n' + \
            str(e.args) +"          "+ str(faclist)
        continue


    # check is entry already present
    try:
        iv_name = '%'+faclist[1].strip().strip('"').strip('.')+'%'
        q_check = "SELECT 1 from iv_organized where f_id=" + \
            str(faclist[0])+" AND ind LIKE '"+iv_name+"'"
        # print(q_check)
        result = mycursor.execute(q_check)
        # print('afterrrr')
        result = mycursor.rowcount
        # print("RESULT"+result,end='\n\n')
        faclist[11] = year
        faclist[12] = month
        faclist[13] = noofdays
        faclist[14] = noofweeks
        del faclist[15:]
        if result == 0:
            val = tuple(faclist)
            # print(faclist)
            # print(val)
            sql = "INSERT INTO iv_organized(f_id, ind, city, purpose, t_audience, staff, t_from, t_to, part, details, ivtype,year,month,noofdays,noofweeks) VALUES ('%s', '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')" % val
            # print(sql)

            mycursor.execute(sql)

            py_connection.mydb.commit()
            count = count+1
            print('E N T R Y  P R O C E S S E D')
        else:
            # print(faclist)
            # print(authors)
            print('DUPLICATE ENTRY')
    except Exception as e:
        print('entry not processed'+str(e.args))
        # print(authors)
        # f_series = pd.Series(faclist, index = failed.columns)
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Entry not processed\n' + str(e.args) + '      '+ str(faclist) + "      "+ str(tuple(faclist))
        continue


# print(failed)
print(count)
# status = os.stat('trial.xlsx')
# print(oct(status.st_mode)[-3:])

# df.to_excel(sys.argv[1], index=False)
if not failed.empty:
    failed.to_excel('excels/failed_'+sys.argv[1]+'.xlsx', index=False)
# failed.to_excel('failed_'+sys.argv[1]+'.xlsx', index=False)
