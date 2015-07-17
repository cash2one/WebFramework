# encoding=utf-8
'''
性能测试工具收集
jvm日志处理模块

1，关于gckey，gcviewer使用这个完全参照yxj同学的
Created on 2013-4-8

@author: 张波
'''

import os, sys
import time
import random
import subprocess
from fileHandleImpl import fileHandleImpl

class jvmLogHandleGCViewerImpl( fileHandleImpl ):
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
        self.__jvmInfoDatasTypeQPS = {}  # 未处理数据： 数据存放方式是字典型的， timestamp :{ key :  [values]}
        self.__jvmInfoDatasTypeAVG = {}  # 未处理数据： 数据存放方式是字典型的， timestamp :{ key :  [values]}
        self.__jvmInfoDatasTypeSUM = {}  # 未处理数据： 数据存放方式是字典型的， timestamp :{ key :  [values]}

        self.__jvmKeyFile = 'gc.keys'
        self.__gcViewerInitTimestamp = time.time()  # 初始化时间
        self.__lastMaxTimestamp = 0  # 记录最大的timestamp
        self.__gcViewerPath = os.path.join( os.path.dirname( sys._getframe().f_code.co_filename ), 'gcviewer.jar' )
        self.__tmpGCViwerLogDir = 'tmp_gcviewerlog'  # gcviewerlog临时目录
        self.__minHanleSize = 200  # 每次处理最少行数
        self.__maxHandleCircle = 6  # 单次输入数据，最多分为6次处理
        try:
            self.__devnull = open( '/dev/null', 'w' )  # 将结果不显示
        except:
            self.__devnull = None


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

    def generateKeys( self ):
        '''
        生成keys：load from __jvmKeyFile file
        '''
        retkeys = []
        keyfilepath = os.path.abspath( self.__jvmKeyFile )
        if os.path.exists( keyfilepath ) and os.path.isfile( keyfilepath ):
            with open( keyfilepath ) as filehandle:
                for line in filehandle.readlines():
                    line = line.strip()
                    values = line.split()
                    if line.startswith( "gc." ) and values:
                        retkeys.append( values[0] )
        return retkeys

############################################################
# override fileHandleImpl函数
############################################################
    def processLines( self, logLines ):
        '''
        获取统计结果:覆盖父类方法
        '''
        retdatas = []
        if not logLines:
            return retdatas

        # 计算每次处理log行数
        loglinesize = len( logLines )
        handlelinesize = int( loglinesize / self.__maxHandleCircle ) + 1
        if handlelinesize < self.__minHanleSize:
            if loglinesize < self.__minHanleSize:
                loglinesize = self.__minHanleSize
            else:
                handlelinesize = int( loglinesize / int( loglinesize / self.__minHanleSize ) )
        startindex = 0
        while startindex < loglinesize:
            # 获取gcview结果
            self.__getGCViewerResult( logLines[startindex:startindex + handlelinesize ] )
            # 计算结果
            retdatas.extend( self.calcData() )

            startindex += handlelinesize

        return retdatas

    def calcData( self ):
        '''
        计算数据
        '''
        result = []

        # average
        for timestamp, datas in self.__jvmInfoDatasTypeAVG.iteritems():
            for keyName in datas:
                keyValues = datas[keyName]
                result.append( ( timestamp, keyName, sum( keyValues ) / len( keyValues ) ) )
        self.__jvmInfoDatasTypeAVG = {}

        # sum
        for timestamp, datas in self.__jvmInfoDatasTypeSUM.iteritems():
            for keyName in datas:
                keyValues = datas[keyName]
                result.append( ( timestamp, keyName , sum( keyValues ) ) )
        self.__jvmInfoDatasTypeSUM = {}

        # qps
        for timestamp, datas in self.__jvmInfoDatasTypeQPS.iteritems():
            for keyName in datas:
                keyValues = datas[keyName]
                result.append( ( timestamp, keyName , sum( keyValues ) / self.timeInterval ) )
        self.__jvmInfoDatasTypeQPS = {}

        return result


############################################################
# 私有函数
############################################################
    def __addDataType( self, timestamp, dataname, datavalue , datatype ):
        '''
        添加数据
        '''
        targetdataset = self.__jvmInfoDatasTypeAVG
        if jvmLogHandleGCViewerImpl.DATA_TYPE_QPS == datatype:
            targetdataset = self.__jvmInfoDatasTypeQPS
        elif jvmLogHandleGCViewerImpl.DATA_TYPE_SUM == datatype:
            targetdataset = self.__jvmInfoDatasTypeSUM

        if not targetdataset.has_key( timestamp ):
            targetdataset[timestamp] = {}
        targettsdict = targetdataset[timestamp]

        if not targettsdict.has_key( dataname ):
            targettsdict[dataname] = [datavalue]
        else:
            targettsdict[dataname].append( datavalue )

    def __formateGCViewTimeStamp( self, timestamp ):
        '''
        格式化:gcview 结果数据timestmap
        '''
        # LOWESTTIMESTAMP = 100000000
        LOWESTTIMESTAMP = 1000000000
        if timestamp < LOWESTTIMESTAMP:
            timestamp = timestamp + self.__gcViewerInitTimestamp
        # 更新__lastMaxTimestamp
        if self.__lastMaxTimestamp < timestamp:
            self.__lastMaxTimestamp = timestamp
        return timestamp
    
    def __updateInitTimeStamp( self, timestamp ):
        '''
        更新最后一次timestamp，只有在每次分析gcview结果的时候调用，只调用一次
        '''
        curtimestamp = self.__formateGCViewTimeStamp( timestamp )
        if curtimestamp < self.__lastMaxTimestamp:
            self.__gcViewerInitTimestamp = self.__lastMaxTimestamp


    def __getGCViewerResult( self, lines ):
        '''
        获取gcview结果：
        1，将line写到文件中，
        2，通过gcview分析文件
        3，读取gc 结果文件
        '''

        if not lines:
            return
        # 创建gcviewer log临时目录
        if not os.path.exists( self.__tmpGCViwerLogDir ): os.makedirs( self.__tmpGCViwerLogDir )

        tempinputfile = os.path.join( self.__tmpGCViwerLogDir, 'tmp.gcview.iniput.%s.%s.tmp' % ( time.time(), random.randint( 1, 1000 ) ) )
        tempresultfile = os.path.join( self.__tmpGCViwerLogDir, 'tmp.gcview.result.%s.%s.tmp' % ( time.time(), random.randint( 1, 1000 ) ) )
        try:
            # 1，将lines 写入到tempinputfile文件
            inputfile = open( tempinputfile, 'w+' )
            inputfile.write( '\n'.join( lines ) )
            inputfile.close()

            # ,2，使用gcview分析tempinputfile
            cmd = 'java -jar "%s" "%s" "%s" detail' % ( self.__gcViewerPath, tempinputfile, tempresultfile )
            subprocess.call( args = cmd, shell = True, stdout = self.__devnull, stderr = self.__devnull )

            # ,3，使用读取tempresultfile结果
            if os.path.exists( tempresultfile ):
                with open( tempresultfile ) as resultfile:
                    gcviewresults = resultfile.readlines()
                    updatetimestmap = True
                    for line in  gcviewresults:
                        values = line.strip().split()
                        if 3 <= len( values ):
                            # 只调用一次 self.__updateInitTimeStamp
                            if updatetimestmap:
                                self.__updateInitTimeStamp( int( values[0] ) )
                                updatetimestmap = False
                            self.__addDataType( self.__formateGCViewTimeStamp( int( values[0] ) ) , values[1], float( values[2] ), jvmLogHandleGCViewerImpl.DATA_TYPE_SUM )
            if self.logger: self.logger.info( '@@logHandler@@ file: %s ,gcviewer done' % self.getAlias() )
        except:
            if self.logger: self.logger.info( '@@logHandler@@ file:  %s ,gcviewer errors' % self.getAlias() )

        # 删除临时文件
        if os.path.exists( tempinputfile ): os.remove( tempinputfile )
        if os.path.exists( tempresultfile ): os.remove( tempresultfile )
