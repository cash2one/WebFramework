#!/usr/bin/python
# coding=utf-8
# filename: creatDB.py
##################################
# @author yinxj
# @date 2012-05-25
##################################
# 性能测试工具收集
# mysql数据库创建
##################################
import MySQLdb
import ConfigParser
import string, os, sys

# 读取配置
cf=ConfigParser.ConfigParser()
cf.read("mysql.conf")
# 建立和数据库系统的连接
conn=MySQLdb.connect(host=cf.get("db","db_host"),user=cf.get("db","db_user"),passwd=cf.get("db","db_pw"))
# 获取操作游标
cursor = conn.cursor()
# 执行SQL,创建一个数据库
cursor.execute('create database if not exists '+ cf.get("db","database"));
# 选择数据库
conn.select_db(cf.get("db","database"));
# 执行SQL,创建数据表
cursor.execute("""create table if not exists indexTable(id1 varchar(32) not null primary key,ldap varchar(256),product varchar(256),type varchar(256),cubetype varchar(256))""");
cursor.execute("""create table if not exists dataTable(id2 int(32) not null primary key auto_increment, id1 varchar(32) not null, t timestamp, keyName varchar(64), keyValue double)""");
# 设置increment的step及offset为1
cursor.execute('set auto_increment_increment=1');
cursor.execute('set auto_increment_offset=1');
#关闭连接，释放资源
cursor.close();
