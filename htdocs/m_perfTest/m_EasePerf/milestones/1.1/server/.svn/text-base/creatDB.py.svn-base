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

def createDB():
    configfile = "mysql.conf"
    # 读取配置
    cf = ConfigParser.ConfigParser()
    cf.read( os.path.abspath( configfile ) )
    hotst = cf.get( "db", "db_host" )
    username = cf.get( "db", "db_user" )
    password = cf.get( "db", "db_pw" )
    database = cf.get( "db", "database" )



    # 建立和数据库系统的连接
    conn = MySQLdb.connect( host = hotst, user = username, passwd = password )
    # 获取操作游标
    cursor = conn.cursor()


    # 执行SQL,创建一个数据库
    cursor.execute( 'create database if not exists ' + database )
    # 选择数据库
    conn.select_db( database )

    # 创建日志表,indexTable 和 dataTable
    cursor.execute( cf.get( "log", "indexTableSQL" ) )
    cursor.execute( cf.get( "log", "dataTableSQL" ) )

    # 创建系统资源表 machines,machineData,machineKeys
    cursor.execute( cf.get( "machine", "machineTableSQL" ) )
    cursor.execute( cf.get( "machine", "machineKeyTableSQL" ) )
    cursor.execute( cf.get( "machine", "machineDataTableSQL" ) )


    # 设置increment的step及offset为1
    cursor.execute( 'set auto_increment_increment=1' )
    cursor.execute( 'set auto_increment_offset=1' )

    # 关闭连接，释放资源
    cursor.close()

if __name__ == "__main__":
    createDB()
