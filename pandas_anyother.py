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
    failed = pd.DataFrame({'Errors':['File not found',sys.exc_info()[0]]})
    failed.to_excel('excels/failed_'+sys.argv[1]+'.xlsx',index=False)
    print(count)
    sys.exit(1)

try:
    expected_headers = ['Sr. No.','Name of Faculty/Staff','Name of Activity','Start Date (DD-MM-YYYY)','End Date (DD-MM-YYYY)','Organized By','Purpose of Activity']
    file_headers = list(df.columns.values)
    print(expected_headers)
    print(file_headers)
    for i in range(len(expected_headers)):
        if(not expected_headers[i] == file_headers[i]):
            raise KeyError('Header format error in "'+file_headers[i]+'" Expected "'+expected_headers[i]+'"')

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
    df.dropna(subset=["Name of Faculty/Staff"], axis=0, inplace=True)
    df['Sr. No.'] = np.arange(len(df))
    # print(df.isnull().head())
    df.fillna('NA', inplace=True)
except Exception as e:
    print('Pre-processing error')
    failed = pd.DataFrame({'Errors':['Pre-processing error',sys.exc_info()[0], e.args]})  
    print(failed)
    print(count)
    failed.to_excel('excels/failed_'+sys.argv[1]+'.xlsx',index=False)
    # failed.to_excel('failed_'+sys.argv[1]+'.xlsx',index=False)
    sys.exit(1)

failed = pd.DataFrame(columns=df.columns)
failed['Errors'] = []

# enter every line into database
for i in range(0, df.shape[0]):
    faclist = list(df.iloc[i, 1:])
    # separate coauthors
    try:
        authors = faclist[0].split(',')
        authname = authors[0].split('.')[-1].strip()
        # get author id
        facid = ""
        q1 = "SELECT Fac_ID from facultydetails where F_NAME like '%"+authname+"%'"
        mycursor.execute(q1)
        result = mycursor.fetchone()
        if result and len(result) == 1:
            facid = int(result[0])
        print('Authname: '+str(authname)+'Facid:'+str(facid))
        if facid=='':
            raise Exception('Fac_ID not found/empty')
        faclist.insert(0, facid)
        # pop faculty name
        faclist.pop(1)
    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Author/ID formatting error\n' + str(e.args)
        continue

    try:
        # month = faclist.pop(6).strip()
        # year = faclist[6]
        # print(year)
        start_date = str(faclist.pop(2))
        end_date = str(faclist.pop(2))
        if start_date != '' and start_date != 'NA':
            date_from = datetime.strptime(start_date, '%Y-%m-%d %H:%M:%S')
            #start_date = datetime.strftime(date_from, '%Y-%m-%d')
            year = date_from.strftime("%Y")
            month = date_from.strftime("%m")
        if end_date != '' and end_date != 'NA':
            date_to = datetime.strptime(end_date, '%Y-%m-%d %H:%M:%S')
            delta = date_to - date_from
            noofdays = delta.days
            
            #end_date = datetime.strftime(date_to, '%Y-%m-%d')
        # print(date_str)
        # faclist.pop(6)
        faclist.insert(2, str(date_from))
        faclist.insert(3, str(date_to))
    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Date formatting error\n' + str(e.args)+"          "+ str(faclist)
        continue

    # check is entry is already present
    try:
        name = '%'+faclist[1].strip().strip('"').strip('.')+'%'
        # organizer = '%'+faclist[3].strip().strip('"').strip('.')+'%'
        q_check = "SELECT 1 from any_other_activity where Fac_id=" + str(faclist[0])+" AND activity_name LIKE '"+str(name)+"'"
        # print(q_check)
        result = mycursor.execute(q_check)
        # print('afterrrr')
        result = mycursor.rowcount
        # print("RESULT"+result,end='\n\n')
        faclist[6] = noofdays
        del faclist[7:]
        if result == 0:
            val = tuple(faclist)
            # print(faclist)
            print(val)
            sql = "INSERT INTO any_other_activity(Fac_id,activity_name,Date_from,Date_to,organized_by,purpose_of_activity,noofdays) VALUES ('%s', '%s','%s','%s','%s','%s','%s')" % val
            # print(sql)
            # sql = "INSERT INTO faculty(Fac_id, Paper_title, conf_journal_name, Paper_type, Paper_N_I, Date_from, Date_to, paper_category,  Paper_co_authors, scopus, h_index, citations, presented_by, Link_publication, Paper_awards) VALUES ({0}, '{1}','{2}','{3}','{4}','{5}','{6}','{7}','{8}','{9}',{10},'{11}','{12}','{13}','{14}')".format(val)
            
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
        failed = failed.append(df.iloc[i,:], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Entry not processed\n' + str(e.args) + "\n" + str(faclist)
     

# print(failed)
print(count)
# status = os.stat('trial.xlsx')
# print(oct(status.st_mode)[-3:])

# df.to_excel(sys.argv[1], index=False)
if not failed.empty:
    failed.to_excel('excels/failed_'+sys.argv[1]+'.xlsx', index=False)
    # failed.to_excel('failed_'+sys.argv[1]+'.xlsx', index=False)


