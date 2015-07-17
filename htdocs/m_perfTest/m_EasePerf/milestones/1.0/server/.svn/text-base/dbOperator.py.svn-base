#!/usr/bin/python
# coding=utf-8
# filename: db.py
##################################
# @author yinxj
# @date 2012-05-25
##################################
# 性能测试工具收集
# mysql操作模块
# collect.py调用时需要一个data用一个实例
##################################
import MySQLdb
import hashlib
import ConfigParser
import string, os, sys, commands

class DBOperator:
    def __init__( self, ldap, product, type, cubename, numvar, strvar, machinevar ):
        self.conn = None
        self.cursor = None
        self.id1 = None

        # 读取数据库配置
        cf = ConfigParser.ConfigParser()
        cf.read( commands.getoutput('pwd') + "/mysql.conf" )

        # 建立和数据库系统的连接
        self.conn = MySQLdb.connect( host = cf.get( "db", "db_host" ), user = cf.get( "db", "db_user" ), passwd = cf.get( "db", "db_pw" ) )
        # 选择数据库
        self.conn.select_db( cf.get( "db", "database" ) )

        # 获取操作游标
        self.cursor = self.conn.cursor()
        # 根据配置hash生成id1
        self.id1 = hashlib.md5( ldap + product + type + cubename ).hexdigest()
        # 查找表1是否已存在该id
        count = self.cursor.execute( """select ldap, product, type, cubetype, numvar, strvar, machinevar from indexTable where id1 = %s""", self.id1 )
        if 1 == count:
            res = self.cursor.fetchone()
            if res[0] != ldap or res[1] != product or res[2] != type or res[3] != cubename:
                print "hash value duplicate"
                self.cursor.close()
                exit(-1)
            # 若已存在该项, 则update表1
            # 合并new & old vars
            newnumvar=self.getnewvar(res[4],numvar)
            newstrvar=self.getnewvar(res[5],strvar)
            newmachinevar=self.getnewvar(res[6],machinevar)
            self.cursor.execute( "update indexTable set numvar=%s, strvar=%s, machinevar=%s where id1=%s", (newnumvar, newstrvar, newmachinevar, self.id1) )
        # 不存在, 则插入
        else:
           self.cursor.execute("insert into indexTable values(%s,%s,%s,%s,%s,%s,%s,%s)",[self.id1, ldap, product, type, cubename,','.join(numvar),','.join(strvar),','.join(machinevar)])
        self.conn.commit()

    def getnewvar(self, oldvar, currentvar):
        newvar=''
        if oldvar!=None:
            newvar=oldvar
            oldvars=oldvar.split(',')
            for var in currentvar:
                if var not in oldvars:
                    newvar=newvar+','+var
        else:
            for var in currentvar:
                newvar=newvar+','+var
        return newvar.strip(',')
        

    def insert( self, values ):
        # values=[]
        # values.append(['2012-05-28 13:10:30', 'search.process.time.99', 173])
        # values.append(['2012-05-28 13:10:30', 'search.process.time.avg', 85])
        # values.append(['2012-05-28 13:10:30', 'seach.process.time.90', 150])
        # values.append(['2012-05-28 13:10:30', 'seach.process.time.qps', 13])

        # 插入数据
        for value in values:
            self.cursor.execute( 'insert into dataTable (id1, t, keyName, keyValue) values(%s,%s,%s,%s)', ( self.id1, value[0], value[1], value[2] ) );
        self.conn.commit();


    def getid1( self ):
        return self.id1

    def close( self ):
        '''
        关闭连接，释放资源
        '''
        self.cursor.close()
