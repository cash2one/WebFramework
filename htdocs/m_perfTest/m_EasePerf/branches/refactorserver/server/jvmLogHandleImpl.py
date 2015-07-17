# encoding=utf-8
'''
性能测试工具收集
jvm日志处理模块

Created on 2013-4-8

@author: 张波
'''

import re
from fileHandleImpl import fileHandleImpl

class jvmLogHandleImpl( fileHandleImpl ):
    '''
    classdocs
    '''
    DATA_TYPE_AVG = 0
    DATA_TYPE_SUM = 1
    DATA_TYPE_QPS = 2


    def __init__( self, logfile , nickname, timeinterval, logger , dbop, starthead = False, startdate = None ):
        '''
        Constructor
        '''
        fileHandleImpl.__init__( self, logfile, nickname, timeinterval, logger, dbop, starthead, startdate )

        # 内部状态
        self.__jvmHandlers = []
        self.__jvmMonitorItems = ['parnew', 'parnewper', 'old', 'oldper', 'realtime', 'realtime_full', 'gc_full', 'gc_young']

        self.__jvmInfoDatasTypeQPS = [( 0, {} ), ]  # 未处理数据： 数据存放方式是字典型的， timestamp :{ key :  [values]}
        self.__jvmInfoDatasTypeAVG = [( 0, {} ), ]  # 未处理数据： 数据存放方式是字典型的， timestamp :{ key :  [values]}
        self.__jvmInfoDatasTypeSUM = [( 0, {} ), ]  # 未处理数据： 数据存放方式是字典型的， timestamp :{ key :  [values]}


############################################################
# 实现handleInterface接口 generateKeys
# override fileHandleImpl函数
############################################################
    def initHandle( self ):
        '''
        初始化: overrider 父类初始化函数
        '''
        # 调用父类初始化
        fileHandleImpl.initHandle( self )

        # 初始化gcpattern
        self.__initGCPatterns()

    def generateKeys( self ):
        '''
        生成keys
        '''
        retkeys = []
        for itemname in self.__jvmMonitorItems:
            retkeys.append( self.__generateKey( itemname ) )
        return retkeys

    def isLineValid( self, line ):
        '''
        行是否合法
        '''
        index = line.find( 'real=' )
        if -1 == index:
            return False

        return True

    def processLine( self, line, timestamp ):
        '''
        处理单行
        '''
        # 重新计算timestamp，结果为timeinteval的倍数
        timestamp = timestamp - timestamp % self.timeInterval
        for handler in self.__jvmHandlers:
            if True == handler( line, timestamp ):
                break

    def calcData( self ):
        '''
        计算数据
        '''
        result = []

        # average
        if self.__jvmInfoDatasTypeAVG:
            datalogs = self.__jvmInfoDatasTypeAVG[:-1]
            self.__jvmInfoDatasTypeAVG = [ self.__jvmInfoDatasTypeAVG[-1] ]
            for timestamp, datas in datalogs:
                for keyName in datas:
                    keyValues = datas[keyName]
                    result.append( ( timestamp, keyName, sum( keyValues ) / len( keyValues ) ) )
        # sum
        if self.__jvmInfoDatasTypeSUM:
            datalogs = self.__jvmInfoDatasTypeSUM[:-1]
            self.__jvmInfoDatasTypeSUM = [ self.__jvmInfoDatasTypeSUM[-1] ]
            for timestamp, datas in datalogs:
                for keyName in datas:
                    keyValues = datas[keyName]
                    result.append( ( timestamp, keyName , sum( keyValues ) ) )
        # qps
        if self.__jvmInfoDatasTypeQPS:
            datalogs = self.__jvmInfoDatasTypeQPS[:-1]
            self.__jvmInfoDatasTypeQPS = [ self.__jvmInfoDatasTypeQPS[-1] ]
            for timestamp, datas in datalogs:
                for keyName in datas:
                    keyValues = datas[keyName]
                    result.append( ( timestamp, keyName , sum( keyValues ) / self.timeInterval ) )
        return result


############################################################
# 私有函数
############################################################
    def __initGCPatterns( self ):
        '''
        初始化gcinfopatterns
        '''

        self.__jvmInfoPattern1 = re.compile( '.*ParNew:\s*\d+K->(\d+)K\((\d+)K\)[^:]*\d+K->(\d+)K\((\d+)K\).*real=([\d\.]+)\s*secs.*' )
        self.__jvmHandlers.append( self.__jvmInfoHandler1 )

        self.__jvmInfoPattern2 = re.compile( '.*Full\s+GC.*\[PSYoungGen:\s*\d+K->(\d+)K\((\d+)K\)\]\s*\[PSOldGen:\s*\d+K->(\d+)K\((\d+)K\)].*real=([\d\.]+)\s*secs.*' )
        self.__jvmHandlers.append( self.__jvmInfoHandler2 )

        self.__jvmInfoPattern3 = re.compile( '.*GC\s+\[PSYoungGen:\s*\d+K->(\d+)K\((\d+)K\)\]\s*\d+K->(\d+)K\((\d+)K\).*real=([\d\.]+)\s*secs.*' )
        self.__jvmHandlers.append( self.__jvmInfoHandler3 )

    def __generateKey( self, itemname ):
        '''
        生成单个key
        '''
        return self.getAlias() + ".jvm_" + itemname

    def __addDataType( self, timestamp, dataname, datavalue , datatype ):
        '''
        添加数据
        '''
        targetlist = self.__jvmInfoDatasTypeAVG
        if jvmLogHandleImpl.DATA_TYPE_QPS == datatype:
            targetlist = self.__jvmInfoDatasTypeQPS
        elif jvmLogHandleImpl.DATA_TYPE_SUM == datatype:
            targetlist = self.__jvmInfoDatasTypeSUM

        if timestamp != targetlist[-1][0]:
            targetlist.append( ( timestamp, {} ), )
        if not targetlist[-1][1].has_key( dataname ):
            targetlist[-1][1][dataname] = [datavalue]
        else:
            targetlist[-1][1][dataname].append( datavalue )

    def __jvmInfoHandler1( self, line, timestamp ):
        '''
        获取JVM信息：
        格式：.*ParNew:\s*\d+K->(\d+)K\((\d+)K\)[^:]*\d+K->(\d+)K\((\d+)K\).*real=([\d\.]+)\s*secs.*
    返回值：是否解析成功
        '''
        result = self.__jvmInfoPattern1.match( line )
        if result:
            self.__addDataType( timestamp , self.__generateKey( 'parnew' ), float( result.group( 1 ) ) / 1024, jvmLogHandleImpl.DATA_TYPE_AVG )
            self.__addDataType( timestamp , self.__generateKey( 'parnewper' ), float( result.group( 1 ) ) / float( result.group( 2 ) ), jvmLogHandleImpl.DATA_TYPE_AVG )
            self.__addDataType( timestamp , self.__generateKey( 'old' ), float( result.group( 3 ) ) / 1024, jvmLogHandleImpl.DATA_TYPE_AVG )
            self.__addDataType( timestamp , self.__generateKey( 'oldper' ), float( result.group( 3 ) ) / float( result.group( 4 ) ), jvmLogHandleImpl.DATA_TYPE_AVG )
            self.__addDataType( timestamp , self.__generateKey( 'realtime' ), float( result.group( 5 ) ), jvmLogHandleImpl.DATA_TYPE_SUM )
            return True
        return False
    def __jvmInfoHandler2( self, line, timestamp ):
        '''
        获取JVM信息：
        格式：
        [Full GC (System) [PSYoungGen: 2336K->0K(451136K)] [PSOldGen: 0K->2215K(1031296K)] 2336K->2215K(1482432K) [PSPermGen: 10695K->10695K(21952K)], 0.0455400 secs] [Times: user=0.05 sys=0.00, real=0.04 secs]
    返回值：是否解析成功
        '''
        result = self.__jvmInfoPattern2.match( line )
        if result:
            self.__addDataType( timestamp , self.__generateKey( 'parnew' ), float( result.group( 1 ) ) / 1024, jvmLogHandleImpl.DATA_TYPE_AVG )
            self.__addDataType( timestamp , self.__generateKey( 'parnewper' ), float( result.group( 1 ) ) / float( result.group( 2 ) ) , jvmLogHandleImpl.DATA_TYPE_AVG )
            self.__addDataType( timestamp , self.__generateKey( 'old' ), float( result.group( 3 ) ) / 1024 , jvmLogHandleImpl.DATA_TYPE_AVG )
            self.__addDataType( timestamp , self.__generateKey( 'oldper' ), float( result.group( 3 ) ) / float( result.group( 4 ) ) , jvmLogHandleImpl.DATA_TYPE_AVG )
            self.__addDataType( timestamp , self.__generateKey( 'realtime' ), float( result.group( 5 ) ) , jvmLogHandleImpl.DATA_TYPE_SUM )
            self.__addDataType( timestamp , self.__generateKey( 'realtime_full' ), float( result.group( 5 ) ) , jvmLogHandleImpl.DATA_TYPE_SUM )
            self.__addDataType( timestamp , self.__generateKey( 'gc_full' ), 1, jvmLogHandleImpl.DATA_TYPE_SUM )
            return True
        return False
    def __jvmInfoHandler3( self, line, timestamp ):
        '''
        获取JVM信息：
        格式：.*ParNew:\s*\d+K->(\d+)K\((\d+)K\)[^:]*\d+K->(\d+)K\((\d+)K\).*real=([\d\.]+)\s*secs.*
    返回值：是否解析成功
        '''
        result = self.__jvmInfoPattern3.match( line )
        if result:
            self.__addDataType( timestamp , self.__generateKey( 'parnew' ), float( result.group( 1 ) ) / 1024 , jvmLogHandleImpl.DATA_TYPE_AVG )
            self.__addDataType( timestamp , self.__generateKey( 'parnewper' ), float( result.group( 1 ) ) / float( result.group( 2 ) ) , jvmLogHandleImpl.DATA_TYPE_AVG )
            self.__addDataType( timestamp , self.__generateKey( 'old' ), float( result.group( 3 ) ) / 1024 , jvmLogHandleImpl.DATA_TYPE_AVG )
            self.__addDataType( timestamp , self.__generateKey( 'oldper' ), float( result.group( 3 ) ) / float( result.group( 4 ) ) , jvmLogHandleImpl.DATA_TYPE_AVG )
            self.__addDataType( timestamp , self.__generateKey( 'realtime' ), float( result.group( 5 ) ) , jvmLogHandleImpl.DATA_TYPE_SUM )
            self.__addDataType( timestamp , self.__generateKey( 'gc_young' ), 1, jvmLogHandleImpl.DATA_TYPE_SUM )
            return True
        return False
