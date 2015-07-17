# encoding=utf-8
'''
性能测试工具收集
mysql操作模块

Created on 2013-4-7

@author: 张波
'''

import MySQLdb
import hashlib
import ConfigParser
import os
import threading

class LogDBOperator:
    def __init__( self, ldap, product, ptype, cubename ):
        self.__ldap = ldap
        self.__product = product
        self.__type = ptype
        self.__cubeName = cubename
        self.__numKeys = []
        self.__strKeys = []
        self.__otherKeys = []

        self.__dbConnection = None
        self.__dbCursor = None
        self.__delmChar = ","
        self.__configfile = "mysql.conf"
        self.__indexID = None
        self.__isInited = False
        # 数据库锁
        self.__dbLock = threading.RLock()

        # 初始化db各项参数
        self.__dbInit()

    def addNumberKeys( self, numkeys ):
        '''
        添加数字类型key
        '''
        self.__numKeys.extend( numkeys )

    def addStringKeys( self, strkeys ):
        '''
        添加字符类型类型key
        '''
        self.__strKeys.extend( strkeys )

    def addOtherKeys( self, otherkeys ):
        '''
        添加其它类型key
        '''
        self.__otherKeys.extend( otherkeys )

    def __dbInit( self ):
        '''
        数据初始化
        '''
        if not self.__isInited:
            # 读取配置
            cf = ConfigParser.ConfigParser()
            cf.read( os.path.abspath( self.__configfile ) )
            hotst = cf.get( "db", "db_host" )
            username = cf.get( "db", "db_user" )
            password = cf.get( "db", "db_pw" )
            database = cf.get( "db", "database" )

            # 建立和数据库系统的连接
            self.__dbConnection = MySQLdb.connect( host = hotst, user = username, passwd = password, db = database )
            self.__dbConnection.ping( True )
            # 获取操作游标
            self.__dbCursor = self.__dbConnection.cursor()

            # add data
            self.updateKeys()
        self.__isInited = True

    def __formateKeys( self ):
        '''
        规范keys
        '''
        # 去除各字段首尾空格，以及空字段
        self.__numKeys = [ str( item ).strip() for item in self.__numKeys if str( item ).strip() ]
        self.__strKeys = [ str( item ).strip() for item in self.__strKeys if str( item ).strip() ]
        self.__otherKeys = [ str( item ).strip() for item in self.__otherKeys if str( item ).strip() ]

        # 去除重复的
        self.__numKeys = list( set( self.__numKeys ) )
        self.__strKeys = list( set( self.__strKeys ) )
        self.__otherKeys = list( set( self.__otherKeys ) )


    def updateKeys( self ):
        '''
        将key更新到数据库中
        '''

        # 规范keys
        self.__formateKeys()

        if None == self.__indexID:
            self.__indexID = hashlib.md5( self.__ldap + self.__product + self.__type + self.__cubeName ).hexdigest()

        # 查找表1是否已存在该id
        sqlcmd = "select ldap, product, type, cubetype, numvars, strvars, machinevars from indexTable where id = %s"
        params = ( self.__indexID, )
        count = self.__dbCursor.execute( sqlcmd, params )
        if 1 == count:
            res = self.__dbCursor.fetchone()
            if res[0] != self.__ldap or res[1] != self.__product or res[2] != self.__type or res[3] != self.__cubeName:
                print "hash value duplicate"
                self.__dbCursor.close()
                exit( -1 )
            # 若已存在该项
            self.__numKeys.extend( str( res[4] ).split( self.__delmChar ) )
            self.__strKeys.extend( str( res[5] ).split( self.__delmChar ) )
            self.__otherKeys.extend( str( res[6] ).split( self.__delmChar ) )
            self.__formateKeys()

            numbkeysstr = self.__delmChar.join( self.__numKeys )
            strkeysstr = self.__delmChar.join( self.__strKeys )
            otherkeysstr = self.__delmChar.join( self.__otherKeys )
            sqlcmd = "update indexTable set numvars=%s, strvars=%s, machinevars=%s where id=%s"
            params = ( numbkeysstr, strkeysstr, otherkeysstr, self.__indexID )
            self.__dbCursor.execute( sqlcmd, params )
        # 不存在, 则插入
        else:
            numbkeysstr = self.__delmChar.join( self.__numKeys )
            strkeysstr = self.__delmChar.join( self.__strKeys )
            otherkeysstr = self.__delmChar.join( self.__otherKeys )
            sqlcmd = "insert into indexTable (id, ldap, product, type, cubetype, numvars, strvars, machinevars)values(%s,%s,%s,%s,%s,%s,%s,%s)"
            params = ( self.__indexID, self.__ldap, self.__product, self.__type, self.__cubeName, numbkeysstr, strkeysstr, otherkeysstr )
            self.__dbCursor.execute( sqlcmd, params )
        self.__dbConnection.commit()


    def insert( self, values ):
        '''
        插入数据,如果未初始化，则无法插入数据
        '''
        # values=[]
        # values.append(['2012-05-28 13:10:30', 'search.process.time.99', 173])
        self.__dbLock.acquire()
        if self.__isInited:
            for value in values:
                if 3 == len( value ):
                    self.__dbCursor.execute( 'insert into dataTable (indexID, time, keyName, keyValue) values(%s,%s,%s,%s)', ( self.__indexID, value[0], value[1], value[2] ) );
            self.__dbConnection.commit()
        self.__dbLock.release()

    def close( self ):
        '''
        关闭连接，释放资源
        '''
        self.__dbCursor.close()


class MachineDBOperator:
    def __init__( self, machinename, keys ):
        self.__dbConnection = None
        self.__dbCursor = None
        self.__configfile = "mysql.conf"
        self.__machineID = None

        self.__keys = []
        # 数据库锁
        self.__dbLock = threading.RLock()

        # 读取配置
        cf = ConfigParser.ConfigParser()
        cf.read( os.path.abspath( self.__configfile ) )
        hotst = cf.get( "db", "db_host" )
        username = cf.get( "db", "db_user" )
        password = cf.get( "db", "db_pw" )
        database = cf.get( "db", "database" )

        # 建立和数据库系统的连接
        self.__dbConnection = MySQLdb.connect( host = hotst, user = username, passwd = password, db = database )
        self.__dbConnection.ping( True )
        # 获取操作游标
        self.__dbCursor = self.__dbConnection.cursor()

        # 添加监控机器和监控项
        self.addMonitorMachine( machinename )
        self.addMonitorKeys( keys )

        # 获取机器ID
        self.__machineID = self.getMachineID( machinename )


    def getMonitorMachines( self ):
        '''
        获取机器列表
        '''
        hosts = list()
        querysql = 'select host from machines'
        self.__dbCursor.execute( querysql )
        while True:
            result = self.__dbCursor.fetchone()
            if None == result:
                break
            hosts.append( result[0] )
        self.__dbConnection.commit()
        return hosts

    def getMonitorKeys( self ):
        '''
        获取机器监控项表
        '''
        keys = list()
        querysql = 'select keyName from machineKeys'
        self.__dbCursor.execute( querysql )
        while True:
            result = self.__dbCursor.fetchone()
            if None == result:
                break
            keys.append( result[0] )
        self.__dbConnection.commit()
        return keys

    def addMonitorMachine( self, machinename ):
        '''
        添加监控机器，如果已近添加则不处理
        '''
        monitormachines = self.getMonitorMachines()
        machinename = str( machinename ).strip()
        if machinename and ( machinename not in monitormachines ):
            self.__dbCursor.execute( "insert into machines (host) values( %s )", ( machinename, ) )
            self.__dbConnection.commit()

    def addMonitorKeys( self, keys ):
        '''
        添加监控项，只添加新增的项
        '''
        # 去除各字段首尾空格，以及空字段
        keys = [ str( item ).strip() for item in keys if str( item ).strip() ]
        # 去除重复的
        keys = list( set( keys ) )

        monitorkeys = self.getMonitorKeys()
        for key in keys:
            if  key not in monitorkeys :
                self.__dbCursor.execute( "insert into machineKeys (keyName) values( %s )", ( key, ) )
                self.__dbConnection.commit()

    def getMachineID( self, machinename ):
        '''
        获取机器id
        '''
        querysql = 'select id from machines where host="%s"' % ( str( machinename ).strip() )
        self.__dbCursor.execute( querysql )
        res = self.__dbCursor.fetchone()
        return res[0]

    def insert( self, values ):
        # values=[]
        # values.append(['2012-05-28 13:10:30', 'cpu', 173])
        # values.append(['2012-05-28 13:10:30', 'mem', 85])
        if None == self.__machineID:
            return

        # 插入数据
        self.__dbLock.acquire()
        for value in values:
            if 3 == len( value ):
                self.__dbCursor.execute( 'insert into machineData (machineID, time, keyName, keyValue) values(%s,%s,%s,%s)', ( self.__machineID, value[0], value[1], value[2] ) );
        self.__dbConnection.commit()
        self.__dbLock.release()

    def close( self ):
        '''
        关闭连接，释放资源
        '''
        self.__dbCursor.close()
