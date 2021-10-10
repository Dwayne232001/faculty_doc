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
# print(df.head())
# print(df.info())
try:
    # check for expected headers
    expected_headers = ['Sr. No.','Initials','Name/s of Author','Title of Paper','Affiliation of Publication (Name of Congerence/Journal)','Conference/Journal (National Conference, International Confernce, National Journal, International Journal)',
    'Start Date (DD-MM-YYYY)','End Date (DD-MM-YYYY)','Peer Reviewed (Yes/No)','scopus','H-index','Citations','Presented by','Publication (Link)','Awards']
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
    failed = pd.DataFrame(
        {'Errors': ['Pre-processing error', sys.exc_info()[0], e.args]})
    print(failed)
    print(count)
    failed.to_excel('excels/failed_'+sys.argv[1]+'.xlsx', index=False)
    # failed.to_excel('failed_'+sys.argv[1]+'.xlsx',index=False)
    sys.exit(1)
# print(df.iloc[0,1])
# print(list(df.iloc[0,2:]))
# print(df)

# failed = pd.DataFrame(columns=['Fac_id', 'Paper_title', 'conf_journal_name', 'Paper_type', 'Paper_N_I', 'Date_from', 'Date_to', 'paper_category','Paper_co_authors', 'scopus', 'h_index', 'citations', 'presented_by', 'Link_publication', 'Paper_awards'])
failed = pd.DataFrame(columns=df.columns)
failed['Errors'] = []

# enter every line into database
for i in range(0, df.shape[0]):
    faclist = list(df.iloc[i, 2:])
    # separate coauthors
    try:
        authors = faclist[0].split(',')
        # print(authors)
        if authors[-1].find(' and ') != -1:
            temp = authors[-1].split(' and ')
            authors.pop()
            authors += temp
        # print(authors)
        # get author id
        facid = ""
        if len(authors) > 1:
            coauthors = authors[1:]
        else:
            coauthors = []
            # print(coauthors)
            # coauth = ""
            # for c in coauthors:
            #     coauth += c
            # print(coauth)
        coauth = ','.join(coauthors)
        authname = authors[0].split('.')[-1].strip()
        # print("author"+authname)
        q1 = "SELECT Fac_ID from facultydetails where F_NAME like '%"+authname+"%'"
        mycursor.execute(q1)
        result = mycursor.fetchone()
        if result and len(result) == 1:
            facid = int(result[0])
        #print('Authname: '+str(authname)+'Facid:'+str(facid))
        if facid=='':
            raise Exception('Fac_ID not found/empty')
        faclist.insert(0, facid)
        # print(faclist)
    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Authors/ID formatting error\n' + str(e.args)+ "\n" + str(faclist)
        continue

    # check conference journal field
    try:
        conf_jour = faclist[4]
        if conf_jour == 'NA' or '':
            faclist[4] = 'NA'
        
    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Error in conference_journal field\n' + \
            str(e.args)+ "\n" + str(faclist)
        continue

    try:
        # month = faclist.pop(6).strip()
        # year = faclist[6]
        # # print(year)
        # if month != '' and month != 'NA':
        #     date_str = (month+'-'+str(year))
        #     date = datetime.strptime(date_str, '%B-%Y')
        # else:
        #     date_str = str(year)
        #     # print(date_str)
        #     date = datetime.strptime(date_str, '%Y')
        # # print(date)
        # date_str = datetime.strftime(date, '%Y-%m-%d')
        # # print(date_str)
        # faclist.pop(6)
        # faclist.insert(6, date_str)
        # faclist.insert(6, date_str)
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
    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Date formatting error\n' + str(e.args)+ "\n" + str(faclist)
        continue

    try:
        peer_reviewed = faclist[7]
        if peer_reviewed == 'NA' or peer_reviewed == '':
            faclist[7] = 'No'
        scopus = faclist[8]
        hindex = faclist[9]

        if hindex == '' or hindex == 'NA':
            faclist[9] = 0
        

        citations = faclist[10]
        if citations == '' or citations == 'NA':
            citations = 0
        faclist.pop(10)
        faclist.insert(10, citations)

        presentedby = faclist[11]
        if presentedby == '' :
            presentedby == "NULL"
        faclist.pop(11)
        faclist.insert(11, presentedby)

        
        publication = faclist[12]
        if publication == '':
            publication == "NULL"
        faclist.pop(12)
        faclist.insert(12, publication)

        awards = faclist[13]
        if awards == '':
            awards == 'NA'
        faclist.pop(13)
        faclist.insert(13, awards)



        faclist.pop(1)
        #faclist.pop(7)
        #faclist.pop(8)
        #faclist.insert(8, coauth)
    except Exception as e:
        failed = failed.append(df.iloc[i, :], ignore_index=True)
        failed['Errors'].iloc[-1] = 'Entry formatting error, not enough values\n' + \
            str(e.args)+ "\n" + str(faclist)
        continue
    # print(faclist)
    # mycursor.execute("INSERT INTO co_author(p_id,c_name) VALUES (2,'netra')")
    # print(mycursor.lastrowid)

    # check is paper already present
    try:
        paper_name = '%'+faclist[1].strip().strip('"').strip('.')+'%'
        q_check = "SELECT 1 from faculty where Fac_id=" + \
            str(faclist[0])+" AND Paper_title LIKE '"+paper_name+"'"
        # print(q_check)
        result = mycursor.execute(q_check)
        # print('afterrrr')
        result = mycursor.rowcount
        # print("RESULT"+result,end='\n\n')
        faclist.append(noofdays)
        faclist.append(noofweeks)
        faclist.append(month)
        faclist.append(year)
        faclist.append(coauth)
        faclist.append(authname)
        if result == 0:
            val = tuple(faclist)
            # print(faclist)
            # print(val)
            sql = "INSERT INTO faculty(Fac_id, Paper_title, jour_name,ConfJour, Date_from, Date_to, PeerRev, scopus, h_index, citations, presented_by, Link_publication, Paper_awards,noofdays,noofweeks,month,year,Paper_co_authors,author) VALUES ('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')" % val
            # print(sql)
            # sql = "INSERT INTO faculty(Fac_id, Paper_title, conf_journal_name, Paper_type, Paper_N_I, Date_from, Date_to, paper_category,  Paper_co_authors, scopus, h_index, citations, presented_by, Link_publication, Paper_awards) VALUES ({0}, '{1}','{2}','{3}','{4}','{5}','{6}','{7}','{8}','{9}',{10},'{11}','{12}','{13}','{14}')".format(val)

            mycursor.execute(sql)
            paper_id = mycursor.lastrowid
            # print(paper_id)
            for a in coauthors:
                authname = a.strip()
                # print(authname)
                # print("COAUTHID"+c_id)
                coauth = (paper_id, authname)
                coauth_q = "INSERT INTO co_author(p_id,c_name) VALUES (%s,'%s')" % coauth
                mycursor.execute(coauth_q)

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
        failed['Errors'].iloc[-1] = 'Entry not inserted\n' + str(e.args)+ "\n" + str(faclist)


# print(failed)
print(count)
# status = os.stat('trial.xlsx')
# print(oct(status.st_mode)[-3:])

# df.to_excel(sys.argv[1], index=False)
if not failed.empty:
    failed.to_excel('excels/failed_'+sys.argv[1]+'.xlsx', index=False)
# failed.to_excel('failed_'+sys.argv[1]+'.xlsx', index=False)