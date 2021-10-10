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
    expected_headers = ['Sr. No.','Dept.','Initials','Name/s of  Author /s / Faculty','Title of paper/STTP/WS attended etc.',
    'Organised by','Location','Conference / journal / WS / STTP / Colloquium / Transaction / International Conference abroad / Seminar',
    'Duration (hours)','Start Date (DD-MM-YYYY)','End Date (DD-MM-YYYY)','Awards if any','Local/State/National/International']
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
    df.dropna(subset=["Name/s of  Author /s / Faculty"], axis=0, inplace=True)
    # print(df.head(1))
    # print(df.info())
    # print(df.shape[0])
    # df['Sr. No.'] = np.arange(len(df))
    # print(df.isnull().head())
    df.fillna('NA', inplace=True)
    #df.drop(columns=['h-index'], axis=1, inplace=True)
    #df.drop(columns=['No of Citations as on date'], axis=1, inplace=True)
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
        if facid=='':
            raise Exception('Fac_ID not found/empty')
        faclist.insert(0, facid)
        # print(faclist)
    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Author/ID formatting error\n' + str(e.args)
        continue

    try:
        start_date = str(faclist.pop(7))
        end_date = str(faclist.pop(7))
        if start_date != '' and start_date != 'NA':
            date_from = datetime.strptime(start_date, '%Y-%m-%d %H:%M:%S')
            #start_date = datetime.strftime(str(date_from), '%Y-%m-%d %H:%M:%S')
            year = date_from.strftime("%Y")
            month = date_from.strftime("%m")
        if end_date != '' and end_date != 'NA':
            date_to = datetime.strptime(end_date, '%Y-%m-%d %H:%M:%S')
            delta = date_to - date_from
            noofdays = delta.days
            noofweeks = int(noofdays)//7

        faclist.insert(7, str(date_from))
        faclist.insert(8, str(end_date))
        # print(faclist)
    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Date formatting error\n' + str(e.args) + "\n" + str(faclist)
        continue

    # dealing with organizers and location fields
    try:
        #organiser part
        if faclist[3] == 'NA' or faclist[3] == '':
            faclist[3] = 'Info not available'

        #location part 
        if faclist[4] == 'NA' or faclist[3] == '':
            faclist[4] = 'Info not available'

    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Organizer field formatting error\n' + \
            str(e.args)
        continue

    # replace na with no awards
    try:

        if faclist[9] == 'NA' or faclist[9] == '':
            faclist[9] = 'No Awards'
        
    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Awards field formatting error\n' + \
            str(e.args)
        continue

    # deal with duration null values
    if faclist[6] == 'NA':
        faclist[6] = 0

    # pop faculty name


    if faclist[10] == 'NA' or faclist[10] == '':
        faclist[10] = 'No Information'

    faclist[11] = noofdays

    faclist.pop(1)
    
    del faclist[11:]

    # print(faclist)
    # mycursor.execute("INSERT INTO co_author(p_id,c_name) VALUES (2,'netra')")
    # print(mycursor.lastrowid)

    # check is paper already present
    try:
        paper_name = '%'+faclist[1].strip().strip('"').strip('.')+'%'
        q_check = "SELECT 1 from attended where Fac_id=" + \
            str(faclist[0])+" AND Act_title LIKE '"+paper_name+"'"
        # print(q_check)
        result = mycursor.execute(q_check)
        # print('afterrrr')
        result = mycursor.rowcount
        # print("RESULT"+result,end='\n\n')
        if result == 0:
            val = tuple(faclist)
            # print(faclist)
            # print(val)
            sql = "INSERT INTO attended(Fac_id, Act_title, Organized_by, Location, Act_type, Equivalent_Duration, Date_from, Date_to, Awards,Status_Of_Activity,noofdays) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')" % val
            # print(sql)
            # sql = "INSERT INTO faculty(Fac_id, Paper_title, conf_journal_name, Paper_type, Paper_N_I, Date_from, Date_to, paper_category,  Paper_co_authors, scopus, h_index, citations, presented_by, Link_publication, Paper_awards) VALUES ({0}, '{1}','{2}','{3}','{4}','{5}','{6}','{7}','{8}','{9}',{10},'{11}','{12}','{13}','{14}')".format(val)

            mycursor.execute(sql)
            # paper_id = mycursor.lastrowid
            # print(paper_id)

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
        failed['Errors'].iloc[-1] = 'Entry not processed\n' + str(e.args) + "\n" + str(faclist) + "\n" + str(val)
        continue


# print(failed)
print(count)
# status = os.stat('trial.xlsx')
# print(oct(status.st_mode)[-3:])

# df.to_excel(sys.argv[1], index=False)
if not failed.empty:
    failed.to_excel('excels/failed_'+sys.argv[1]+'.xlsx', index=False)
    # failed.to_excel('failed_'+sys.argv[1]+'.xlsx', index=False)
