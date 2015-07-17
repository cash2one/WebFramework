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
    def __init__( self, ldap, product, type, cubename, numvars, strvars, machinevars ):
        self.connection = None
        self.cursor = None
        self.indexID = None
        self.delmChar = ","
        self.configfile = "mysql.conf"

        # 读取配置
        cf = ConfigParser.ConfigParser()
        cf.read( os.path.abspath( self.configfile ) )
        hotst = cf.get( "db", "db_host" )
        username = cf.get( "db", "db_user" )
        password = cf.get( "db", "db_pw" )
        database = cf.get( "db", "database" )


        # 建立和数据库系统的连接
        self.connection = MySQLdb.connect( host = hotst, user = username, passwd = password, db = database )
        # 获取操作游标
        self.cursor = self.connection.cursor()


        # 去除各字段首尾空格，以及空字段
        numvars = [ str( item ).strip() for item in numvars if str( item ).strip() ]
        strvars = [ str( item ).strip() for item in strvars if str( item ).strip() ]
        machinevars = [ str( item ).strip() for item in machinevars if str( item ).strip() ]


        # 根据配置hash生成id1
        self.indexID = hashlib.md5( ldap + product + type + cubename ).hexdigest()
        # 查找表1是否已存在该id
        count = self.cursor.execute( "select ldap, product, type, cubetype, numvars, strvars, machinevars from indexTable where id = %s", self.indexID )
        if 1 == count:
            res = self.cursor.fetchone()
            if res[0] != ldap or res[1] != product or res[2] != type or res[3] != cubename:
                print "hash value duplicate"
                self.cursor.close()
                exit( -1 )
            # 若已存在该项, 则update表1合并new & old vars
            newnumvar = self.delmChar.join( list( set( str( res[4] ).split( self.delmChar ) + numvars ) ) )
            newstrvar = self.delmChar.join( list( set( str( res[5] ).split( self.delmChar ) + strvars ) ) )
            newmachinevar = self.delmChar.join( list( set( str( res[6] ).split( self.delmChar ) + machinevars ) ) )
            self.cursor.execute( "update indexTable set numvars=%s, strvars=%s, machinevars=%s where id=%s", ( newnumvar, newstrvar, newmachinevar, self.indexID ) )
        # 不存在, 则插入
        else:
           self.cursor.execute( "insert into indexTable (id, ldap, product, type, cubetype, numvars, strvars, machinevars)values(%s,%s,%s,%s,%s,%s,%s,%s)", [self.indexID, ldap, product, type, cubename, self.delmChar.join( numvars ), self.delmChar.join( strvars ), self.delmChar.join( machinevars )] )
        self.connection.commit()

    def insert( self, values ):
        # values=[]
        # values.append([timestamp, 'search.process.time.99', 173])
        # 插入数据
        for value in values:
            self.cursor.execute( 'insert into dataTable (indexID, time, keyName, keyValue) values(%s,%s,%s,%s)', ( self.indexID, value[0], value[1], value[2] ) );
        self.connection.commit();

    def close( self ):
        '''
        关闭连接，释放资源
        '''
        self.cursor.close()


class MachineDBOperator:
    def __init__( self, machinename, keys ):
        self.connection = None
        self.cursor = None
        self.machineID = None
        self.configfile = "mysql.conf"

        # 读取配置
        cf = ConfigParser.ConfigParser()
        cf.read( os.path.abspath( self.configfile ) )
        hotst = cf.get( "db", "db_host" )
        username = cf.get( "db", "db_user" )
        password = cf.get( "db", "db_pw" )
        database = cf.get( "db", "database" )

        # 建立和数据库系统的连接
        self.connection = MySQLdb.connect( host = hotst, user = username, passwd = password, db = database )
        # 获取操作游标
        self.cursor = self.connection.cursor()


        # 去除各字段首尾空格，以及空字段
        keys = [ str( item ).strip() for item in keys if str( item ).strip() ]

        # 添加监控机器和监控项
        self.addMonitorMachine( machinename )
        self.addMonitorKeys( keys )

        # 获取机器ID
        self.machineID = self.getMachineID( machinename )


    def getMonitorMachines( self ):
        '''
        获取机器列表
        '''
        hosts = list()
        querysql = 'select host from machines'
        self.cursor.execute( querysql )
        while True:
            result = self.cursor.fetchone()
            if None == result:
                break
            hosts.append( result[0] )
        self.connection.commit()
        return hosts

    def getMonitorKeys( self ):
        '''
        获取机器监控项表
        '''
        keys = list()
        querysql = 'select keyName from machineKeys'
        self.cursor.execute( querysql )
        while True:
            result = self.cursor.fetchone()
            if None == result:
                break
            keys.append( result[0] )
        self.connection.commit()
        return keys

    def addMonitorMachine( self, machinename ):
        '''
        添加监控机器，如果已近添加则不处理
        '''
        monitormachines = self.getMonitorMachines()
        machinename = str( machinename ).strip()
        if machinename and ( machinename not in monitormachines ):
            self.cursor.execute( "insert into machines (host) values( %s )", ( machinename, ) )
            self.connection.commit()

    def addMonitorKeys( self, keys ):
        '''
        添加监控项，只添加新增的项
        '''
        monitorkeys = self.getMonitorKeys()
        for key in keys:
            key = str( key ).strip()
            if key and ( key not in monitorkeys ):
                self.cursor.execute( "insert into machineKeys (keyName) values( %s )", ( key, ) )
                self.connection.commit()

    def getMachineID( self, machinename ):
        '''
        获取机器id
        '''
        querysql = 'select id from machines where host="%s"' % ( str( machinename ).strip() )
        self.cursor.execute( querysql )
        res = self.cursor.fetchone()
        return res[0]

    def insert( self, values ):
        # values=[]
        # values.append(['2012-05-28 13:10:30', 'cpu', 173])
        # values.append(['2012-05-28 13:10:30', 'mem', 85])
        if None == self.machineID:
            return

        # 插入数据
        for value in values:
            self.cursor.execute( 'insert into machineData (machineID, time, keyName, keyValue) values(%s,%s,%s,%s)', ( self.machineID, value[0], value[1], value[2] ) );
        self.connection.commit();

    def close( self ):
        '''
        关闭连接，释放资源
        '''
        self.cursor.close()
