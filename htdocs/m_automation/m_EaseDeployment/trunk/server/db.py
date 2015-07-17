#!/usr/bin/python
#encoding=utf-8
import os, sys, string
import MySQLdb
import ConfigParser
import sys
reload(sys)
sys.setdefaultencoding('utf-8')

class DB:
 def __init__(self,conf_name):
  self.config = ConfigParser.ConfigParser()
  self.config.read(conf_name)
  self.host=self.config.get("dbinfo","host")
  self.port=self.config.get("dbinfo","port")
  self.user=self.config.get("dbinfo","user")
  self.passwd=self.config.get("dbinfo","passwd")
  self.db=self.config.get("dbinfo","db")
  self.tables=self.config.options("tables")
  try:
   self.conn=MySQLdb.connect(self.host, self.user, self.passwd, self.db, charset="utf8")
  except Exception, e:
   print e;
   sys.exit()

 def __del__(self):
  self.conn.close()

 def table_bak(self,table,file): 
  cursor=self.conn.cursor()
  oldStdout = sys.stdout
  sys.stdout=open(file,"w")
  sql=("select * from %s;")%(table)
  cursor.execute(sql);
  alldata=cursor.fetchall();
  if alldata:
   for rec in alldata:
    for i in rec:
     print i,
     print ",",
    print
  cursor.close()
  sys.stdout = oldStdout 

 def db_bak(self):
  for table in self.tables:
   table_name=self.config.get("tables",table)
   file=table_name+"_bak";
   self.table_bak(table_name,file)

 def table_update(self,table,file):
  if os.path.isfile(file):
   cursor=self.conn.cursor()
   sql=("delete from %s;")%(table)
   cursor.execute(sql)
   sql=("LOAD DATA LOCAL INFILE \"%s\" INTO TABLE %s FIELDS TERMINATED BY ' ,' LINES TERMINATED BY '\n';")%(file,table)
   cursor.execute(sql)
   self.conn.commit()
   cursor.close()

 def db_update_from_bak(self):
  for table in self.tables:
   table_name=self.config.get("tables",table)
   file=table_name+"_bak";
   self.table_update(table_name,file)
 
 def db_update_from_txt(self):
  for table in self.tables:
   table_name=self.config.get("tables",table)
   file=table_name+".txt";
   self.table_update(table_name,file)

 def table_clr(self,table):
  cursor=self.conn.cursor()
  sql=("delete from %s;")%(table)
  cursor.execute(sql)
  self.conn.commit()
  cursor.close()

 def db_clr(self):
  for table in self.tables:
   table_name=self.config.get("tables",table)
   self.table_clr(table_name)

 def execute(self,sql):
  cursor=self.conn.cursor()
  cursor.execute(sql)
  row = cursor.fetchone()
  self.conn.commit()
  cursor.close()
  return row[0]

 def table_set(self,sql):
  cursor=self.conn.cursor()
  cursor.execute(sql)
  row = cursor.fetchone()
  self.conn.commit()
  cursor.close()

if __name__ == "__main__":
 mydb=DB("db_click.conf")
# mydb.db_bak()
# mydb.db_update()
 mydb.db_update_from_txt()
