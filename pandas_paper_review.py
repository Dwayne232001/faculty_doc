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
    expected_headers = ['Sr. No.','Initials','Name/s of Author','Title of Paper','National/International',
    'Conference/Journal Name','Start Date (DD-MM-YYYY)', 'End Date (DD-MM-YYYY)', 'Organised By', 'Paper Category', 'Details','Paper Type (Conference/Journal)']
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
    df.dropna(subset=["Name/s of Author"], axis=0, inplace=True)
    # print(df.head(1))
    # print(df.info())
    # print(df.shape[0])
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
    faclist = list(df.iloc[i, 2:])

    try:
        authors = faclist[0].split(',')
        # get author id
        facid = ""
        authname = authors[0].split('.')[-1].strip()
        # print("author"+authname)
        q1 = "SELECT Fac_ID from facultydetails where F_NAME like '%"+authname+"%'"
        mycursor.execute(q1)
        result = mycursor.fetchone()
        if result and len(result) == 1:
            facid = int(result[0])
        #print('Authname: '+str(authname)+'Facid:'+str(facid))
        faclist.insert(0, facid)
    except:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Authors/ID formatting error\n' + str(e.args)+ "\n" + str(faclist)
        continue

    try:
        conf_jour = faclist[4]
        if conf_jour == '' or conf_jour == "NA":
            faclist[4] = 'No Information'


        # print(faclist)
    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Error in conference_journal field\n' + str(e.args)+ "\n" + str(faclist)
        continue

    try:
        start_date = str(faclist.pop(5))
        end_date = str(faclist.pop(5))
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


        faclist.insert(5, str(date_from))
        faclist.insert(6, str(end_date))

        # month = faclist.pop(5)
        # year = faclist[5]
        # # print(year)
        # if month != '':
        #     date_str = (month+'-'+str(year))
        #     date = datetime.strptime(date_str, '%B-%Y')
        # else:
        #     date_str = str(year)
        #     # print(date_str)
        #     date = datetime.strptime(date_str, '%Y')
        # # print(date)
        # date_str = datetime.strftime(date, '%Y-%m-%d')
        # # print(date_str)
        # faclist.pop(5)
        # faclist.insert(5, date_str)
        # faclist.insert(5, date_str)
    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Date formatting error\n' + str(e.args)+ "\n" + str(faclist)
        continue


    try:
        category = faclist[8]
        if category.lower().find('p') != -1:
            category = 'peer reviewed'
        elif category == 'NA':
            category = 'non peer reviewed'
        faclist.pop(8)
        faclist.insert(5, category)
    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Category field formatting error\n' + str(e.args)+ "\n" + str(faclist)
        continue

    # scopus = faclist[10]
    # hindex = 0
    # citations = "NA"
    # presentedby = "NULL"
    # publication = "NULL"
    # awards = "NA"
    # faclist += [hindex, citations, presentedby, publication, awards]

    # pop author name
    faclist.pop(1)

    faclist[10] = year
    faclist[11] = month
    faclist[12] = noofdays
    del faclist[13:]
    # faclist.pop(7)
    # faclist.pop(8)
    # faclist.insert(8, coauth)
    print(faclist)
    # mycursor.execute("INSERT INTO co_author(p_id,c_name) VALUES (2,'netra')")
    # print(mycursor.lastrowid)

    # check is paper already present
    try:
        paper_name = '%'+faclist[1].strip().strip('"').strip('.')+'%'
        q_check = "SELECT 1 from paper_review where Fac_id=" + str(faclist[0])+" AND Paper_title LIKE '"+paper_name+"'"
        # print(q_check)
        result = mycursor.execute(q_check)
        # print('afterrrr')
        result = mycursor.rowcount
        # print("RESULT"+result,end='\n\n')


        if result == 0:
            val = tuple(faclist)
            # print(faclist)
            # print(val)
            sql = "INSERT INTO paper_review(Fac_id, Paper_title, Paper_N_I, conf_journal_name, paper_category, Date_from, Date_to, organised_by, details, Paper_type,year,month,noofdays) VALUES ('%s', '%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')" % val
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
        failed['Errors'].iloc[-1] = 'Entry not processed\n' + str(e.args)+ "\n" + str(faclist)
        continue
     

# print(failed)
print(count)
# status = os.stat('trial.xlsx')
# print(oct(status.st_mode)[-3:])

# df.to_excel(sys.argv[1], index=False)
if not failed.empty:
    failed.to_excel('excels/failed_'+sys.argv[1]+'.xlsx', index=False)
    # failed.to_excel('failed_'+sys.argv[1]+'.xlsx', index=False)


