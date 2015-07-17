#!/usr/bin/python
# coding: utf-8
# filename: collectData.py
##################################
# @author yinxj, cailm, zhangb
# @date 2013-01-07

import logging, os, sys, commands, threading, subprocess, re
import datetime, time
import logProcessor
import gcLogProcessor
import dbOperator

class Data:
    def __init__( self, ldap, product, type, logger, handlefulllog = False, beforedays = 0 , notimestamp = False ):
        # set to False when debug
        self.insertToDatabase=True
        self.logger = logger
        self.beforeDays = beforedays
        self.handleFullLog = handlefulllog
        self.noTimeStamp = notimestamp
        self.ldap = ldap
        self.product = product
        self.type = type
        self.cubename = ''
        self.logTimeInterval = 30
        self.gcLogTimeInterval = 600
        self.processTimeInterval = 20

        self.logFile = ''
        self.gcLogFile = ''
        self.numberKeys = []
        self.stringKeys = []
        self.processKeys = []
        self.processes = []
        # LogProcessor与GCLogProcessor必须同时有getStatisticData(logLines)接口
        self.logStatisticTool = None
        self.gcLogStatisticTool = None
        self.processMonitorItems=['cpu','mem']
        self.gcMonitorItems=self.loadGCKeys()
        self.defaultReadSize = 8 * 1024 * 1024

        self.dbop = None
        # logHandler与processHandler共用一个dbop, 加锁实现共用
        self.dbLock = None

    def configCheck( self ):
        if len( self.processes ) == 0 and self.logFile == '' and self.gcLogFile == '':
            self.logger.error('%s: 未配置待监控日志文件或gc日志文件或进程' % self.cubename)
            exit( -1 )
        if self.logFile != '' and ( len( self.numberKeys ) == 0 and len( self.stringKeys ) == 0 ):
            self.logger.error('%s: 未配置待监控日志文件 %s 需要监控的key' % ( self.cubename, self.logFile ))
            exit( -1 )
        if self.logFile == '':
            self.logger.warning('%s: 未配置待监控日志文件' % self.cubename)
        if self.gcLogFile == '':
            self.logger.warning('%s: 未配置待监控gc日志文件' % self.cubename)

    def generateProcessKeys( self ):
        if len( self.processMonitorItems ) == 0 or len( self.processes ) == 0:
            return
        for process in self.processes:
            allprocessnames = []
            allprocessnames.extend( process.alias )
            allprocessnames.append( "otheruser" )
            for alias in allprocessnames:
                for item in self.processMonitorItems:
                    self.processKeys.append( alias + '.p' + item )

    def loadGCKeys( self ):
        # 格式说明:
        # 第一列为key均以"gc."开头, 第二列为该key的含义, 第三列为单位
        # load key的时候只取第一列即可
        gcKeyFile='gc.keys'
        if not os.path.isfile('gc.keys'):
            self.logger.error("%s: can't find file: %s" % (self.cubename, gcKeyFile))
        return re.findall('[\w\.]+',commands.getoutput("awk '{print $1}' %s | grep ^gc" % gcKeyFile))
        

    # entrance
    def monitor( self ):
        self.generateProcessKeys()
        if self.gcLogFile!='':
            self.dbop = dbOperator.DBOperator( self.ldap, self.product, self.type, self.cubename, self.numberKeys + self.gcMonitorItems, self.stringKeys, self.processKeys )
        else:
            self.dbop = dbOperator.DBOperator( self.ldap, self.product, self.type, self.cubename, self.numberKeys, self.stringKeys, self.processKeys )
        self.dbLock = threading.RLock()
        if self.logFile != '':
            self.logStatisticTool = logProcessor.LogProcessor( self.numberKeys + self.stringKeys, self.logTimeInterval, self.beforeDays, self.noTimeStamp , self.logger )
            threading.Thread( target = self.logHandler, args=(self.logFile, self.logStatisticTool, self.logTimeInterval) ).start()
        if self.gcLogFile != '':
            self.gcLogStatisticTool = gcLogProcessor.GCLogProcessor(self.logger,self.gcMonitorItems)
            threading.Thread( target = self.logHandler, args=(self.gcLogFile, self.gcLogStatisticTool, self.gcLogTimeInterval) ).start()
        if len( self.processes ) != 0:
            threading.Thread( target = self.processHandler, args=(self.processTimeInterval,) ).start()

    def logHandler( self, logFilePattern, statisticTool, timeInterval ):
        # 更新文件名称
        curFile,readPosition,curFileSize=self.updateLogFile(logFilePattern, logFilePattern, 0, 0)
        # 若文件存在, 且不需要分析整个log, 则指向末尾, 否则指向开头并等待文件出现
        if os.path.exists( curFile ) and not self.handleFullLog:
            lf = open( curFile )
            lf.seek( -1, os.SEEK_END)
            readPosition = lf.tell()
            lf.close()
        # 开始监控logfile
        while True:
            # update file name
            curFile,readPosition,curFileSize=self.updateLogFile(logFilePattern, curFile, readPosition, curFileSize)
            if not os.path.exists( curFile ):
                time.sleep( 5 )
                self.logger.warning( '%s: @@logHandler@@ file=%s, 待监控文件不存在, 暂不做监控, 等待文件出现ing...' % (self.cubename, curFile) )
                continue
            readPosition = self.log2db( curFile, readPosition, statisticTool )
            self.logger.info( '%s: @@logHandler@@ file=%s, sleeping, will wake up after %s seconds' % ( self.cubename, curFile, timeInterval ) )
            time.sleep( timeInterval )

    def updateLogFile( self, logFilePattern, curFile, readPosition, preFileSize ):
        '''
        更新文件名称和读取位置
        '''
        timePattern = re.match( ".*@@(.*)@@", logFilePattern )
        if timePattern:
            timePattern = timePattern.group( 1 )
            timePattern = datetime.datetime.strftime( datetime.datetime.now(), timePattern )
            logFile = re.sub( "@@.*@@", timePattern , logFilePattern )
            if curFile != logFile:
                # 1. 文件名变化
                self.logger.info('@@logHandler@@ file changed: %s -> %s' % (curFile, logFile))
                return logFile, 0, 0
        if os.path.exists( curFile ):
            curFileSize = os.stat( curFile ).st_size
            if preFileSize <= curFileSize:
                #　2. 文件名无变化 & 文件名无变化 & 文件大小合理变化
                return curFile, readPosition, curFileSize
        # 3. 文件名无变化 & (文件不存在 | 文件大小不合理变化)
        return curFile, 0, 0

    # 若单行log大于defaultReadSize, 将会出bug: 无法往下继续读, 因此defaultReadSize不宜过小, 但从资源消耗角度看, 也不宜过大
    # 每次均读至末尾
    def log2db( self, curFile, readPosition, statisticTool ):
        lf = open( curFile )
        lf.seek( readPosition )
        # read DEFAULT_READ_SIZE bytes per time
        while True:
            rawLines = lf.read( self.defaultReadSize )
            index = rawLines.rfind( '\n' )
            if index < 0:
                break
            lf.seek( index + 1 - len( rawLines ), os.SEEK_CUR )
            logLines = rawLines[0:index].splitlines()
            result = statisticTool.getStatisticData( logLines )
            # 与processHandler共用dbop
            if len( result ) > 0:
                self.dbLock.acquire()
                if self.insertToDatabase:
                    self.dbop.insert( result )
                self.dbLock.release()
                if len( result ) > 20:
                    self.logger.info( '@@logHandler@@ file=%s, insert.status=success, data.length=%s, data=%s, ..., %s' % (curFile, len(result), result[0:10], result[-10:]) )
                else:
                    self.logger.info( '@@logHandler@@ file=%s, insert.status=success, data.length=%s, data=%s' % (curFile, len(result), result) )
            else:
                self.logger.info( '@@logHandler@@ file=%s, insert.status=empty' % curFile)
        position=lf.tell()
        lf.close()
        return position

    def processHandler( self, timeInterval ):
        while True:
            currentTime = int( time.time() )
            currentTime = currentTime - currentTime % timeInterval
            allprocessdatas = []
            for process in self.processes:
                allprocessdatas += process.getAllProcessData()
            if len( allprocessdatas ) > 0:
                # add timestamp
                insertData = []
                for data in allprocessdatas:
                    insertData.append( [currentTime, data[0], data[1]] )
                self.logger.info( '%s: @@processHandler@@ result=%s' % ( self.cubename, insertData ) )
                # insert, 与logHandler共用dbop
                self.dbLock.acquire()
                if self.insertToDatabase:
                    self.dbop.insert( insertData )
                self.dbLock.release()
                self.logger.info( '%s: @@processHandler@@ result inserted to database' % self.cubename )
            else:
                self.logger.info( '%s: @@processHandler@@ no process data get' % self.cubename )
                pass
            endTime = int( time.time() )
            sleepTime = timeInterval - ( endTime - currentTime )
            if sleepTime <= 0:
                continue
            self.logger.info( '%s: @@processHandler@@ sleep, will wake up in %ss' % ( self.cubename, sleepTime ) )
            time.sleep( sleepTime )

