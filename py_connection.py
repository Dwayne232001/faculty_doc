import pymysql
import mysql.connector

mydb = pymysql.connect(
    host="localhost",
    user="root",
    passwd="",
    database="faculty_doc_portal"
)