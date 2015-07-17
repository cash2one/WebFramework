# encoding=utf-8
'''
shell命令处理
Created on 2013-7-4

@author: 张波
'''

import subprocess
import time
import perfUtils
from handleInterface import handleInterface


class shellCommandHandle( handleInterface ):
    '''
    shell 命令执行体
    '''


    def __init__( self, keyname, keytype, command , timeinterval, logger = None, dbop = None ):
        '''
        Constructor
        '''
        self.logger = logger  # 处理日志器
        self.__timeInterval = timeinterval  # 时间间隔
        self.__dbop = dbop

        self.__keyName = str( keyname ).strip()  # 监控名称
        self.__keyType = str( keytype ).lower().strip()  # 类型
        self.__shellCommand = command  # shell 命令

        # 内部属性
        self.__machineName = perfUtils.getMachineName()  # 机器名称
        self.__supportType = ['number', 'string']  # 支持的类型列表
        self.__targetKeyName = self.__keyName
        if not self.__targetKeyName.lower().startswith( self.__machineName ):
            self.__targetKeyName = self.__machineName + '.cmd.' + self.__targetKeyName

        try:
            self.__devnull = open( '/dev/null', 'w' )  # 将结果不显示
        except:
            self.__devnull = None


############################################################
# 实现 handleInterface接口
# 实现接口：initHandle, isValid, startMonitor, getAlias
# 未实现接口：generateKeys
############################################################
    def initHandle( self ):
        '''
        初始化
        '''
        pass

    def isValid( self ):
        '''
        配置是否合法
        '''
        if not self.__keyName or not self.__keyType in self.__supportType or not self.__shellCommand:
            return False
        return True

    def generateKeys( self ):
        '''
        生成keys
        '''
        return [self.__targetKeyName]

    def getAlias( self ):
        '''
        获取别名
        '''
        return self.__keyName

    def startMonitor( self ):
        '''
        分析配置的log文件信息
        '''

        if not self.isValid():
            return
        # 初始化
        self.initHandle()

        lastsystime = None
        queryintervaltime = self.__timeInterval / 3 + 1
        while True:
            # 获取当前时间
            curtime = int( time.time() )
            systime = curtime - curtime % self.__timeInterval

            # 等待一定时间，轮询间隔为 timeInterval的 1/3,避免
            # 程序调度本身需要一定时间，导致数据没有插入，有其是在负载高的时候)
            if lastsystime == systime:
                time.sleep( queryintervaltime )
                continue
            lastsystime = systime

            # 执行shell命令，获取信息
            result = self.__getShellCommandInfo()
            if result:
                # 添加timestamp信息
                insertData = []
                for data in result:
                    insertData.append( ( systime, data[0], data[1] ) )
                if self.logger: self.logger.info( '@@shellCommandHandle@@ name: %s ,result: %s' % ( self.getAlias(), insertData ) )
                if self.__dbop:
                    self.__dbop.insert( insertData )
                    if self.logger: self.logger.info( '@@shellCommandHandle@@ name: %s ,result inserted to database' % self.getAlias() )

            if self.logger: self.logger.info( '@@shellCommandHandle@@ name: %s, will wake up after %s seconds' % ( self.getAlias(), self.__timeInterval ) )

############################################################
# 私有函数
############################################################
    def __getShellCommandInfo( self ):
        '''
       执行shell命令，获取信息
        '''
        shcmdinfo = []
        result = subprocess.Popen( args = self.__shellCommand, shell = True, stdout = subprocess.PIPE, stderr = self.__devnull ).communicate()[0]
        result = result.strip( "\r" ).strip( "\n" ).strip()
        try:
            if 'number' == self.__keyType:
                shcmdinfo.append( ( self.__targetKeyName, float( result ) ) )
            else:
                targetkey = self.__targetKeyName + '^' + result
                shcmdinfo.append( ( targetkey, 1 ) )
        except:
            shcmdinfo = []
        return shcmdinfo
