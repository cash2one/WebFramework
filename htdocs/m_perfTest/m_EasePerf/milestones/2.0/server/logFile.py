#!/usr/bin/python
# coding: utf-8
# filename: logFile.py
##################################
# @author yinxj
# @date 2013-07-09
import re
import threading
import os
import time
from dbOperator import DBOperator

class LogFile:
    def __init__(self,ldap,product,type,file,timeInterval,numberKeys,stringKeys,statisticTool,wholeFile,keyAlarmers,debug,logger,cubeName):
        self.filePattern=file
        self.curFile=file
        self.timePattern=self.parseTimePattern(file)
        self.timeInterval=timeInterval
        self.numberKeys=numberKeys
        self.stringKeys=stringKeys
        self.statisticTool=statisticTool
        self.wholeFile=wholeFile
        self.keyAlarmers=keyAlarmers
        self.dbop=DBOperator(ldap,product,type,cubeName,numberKeys,stringKeys,[])
        self.debug=debug
        self.logger=logger
        self.cubeName=cubeName
        # 
        self.position=0
        self.preFileSize=0
        self.defaultReadSize= 16*1024*1024

    def validate(self):
        if self.filePattern=='':
            self.logger.warning('%s: 未配置文件名, 不对日志做收集' % self.cubeName)
            self.dbop.close()
            return False
        if len(self.numberKeys)+len(self.stringKeys)==0:
            self.logger.warning('%s: fileName=%s, 未配置监控keys, 不对日志做收集' % (self.cubeName,self.filePattern))
        return True

    def toString(self):
        str=''
        str+='fileName=%s\n' % self.filePattern
        str+='timeInterval=%s\n' % self.timeInterval
        str+='numberKeys=%s\n' % ','.join(self.numberKeys)
        str+='stringKeys=%s\n' % ','.join(self.stringKeys)
        str+='wholeFile=%s\n' % self.wholeFile
        str+='keyAlarmers='
        for key,alarmers in self.keyAlarmers.items():
            str+='%s:[' % key
            str+=','.join([alarmer.toString() for alarmer in alarmers])
            str+='],'
        str=str[0:-1]+'\n'
        return str

    def parseTimePattern(self,file):
        timePattern=re.match(".*@@(.*)@@",self.filePattern)
        if timePattern:
            return timePattern.group(1)
        return None

    def updateLogFile(self):
        '''
        更新文件名称和读取位置
        '''
        if self.timePattern:
            curTimePattern = datetime.datetime.strftime(datetime.datetime.now(),self.timePattern)
            logFile = re.sub( "@@.*@@", curTimePattern , self.filePattern )
            if self.curFile != logFile:
                # 1. 文件名变化
                self.logger.info('@@logHandler@@ file changed: %s -> %s' % (self.curFile, logFile))
                self.curFile=logFile
                self.position=0
                self.preFileSize=0
                return
        if os.path.exists(self.curFile):
            curFileSize = os.stat(self.curFile).st_size
            if self.preFileSize <= curFileSize:
                #　2. 文件名无变化 & 文件名无变化 & 文件大小合理变化
                return
        # 3. 文件名无变化 & (文件不存在 | 文件大小不合理变化)
        self.position=0
        self.preFileSize=0

    def monitor(self):
        self.updateLogFile()
        # 若文件存在, 且不需要分析整个log, 则指向末尾, 否则指向开头并等待文件出现
        if os.path.exists(self.curFile) and not self.wholeFile:
            lf=open(self.curFile)
            lf.seek( -1, os.SEEK_END)
            self.position = lf.tell()
            lf.close()
        # 开始监控logfile
        while True:
            # update file name
            self.updateLogFile()
            if not os.path.exists(self.curFile):
                time.sleep(5)
                self.logger.warning( '%s: @@logHandler@@ file=%s, 待监控文件不存在, 暂不做监控, 等待文件出现ing...' % (self.cubeName, self.curFile) )
                continue
            self.log2db()
            self.logger.info( '%s: @@logHandler@@ file=%s, sleeping, will wake up after %s seconds' % ( self.cubeName, self.curFile, self.timeInterval ) )
            time.sleep( self.timeInterval )

    # 若单行log大于defaultReadSize, 将会出bug: 无法往下继续读, 因此defaultReadSize不宜过小, 但从资源消耗角度看, 也不宜过大
    # 每次均读至末尾
    def log2db( self ):
        lf = open( self.curFile )
        # 先记住当前位置, 避免由于日志输出过快而每次处理完日志后又有新日志输出
        lf.seek( -1, os.SEEK_END)
        currentEndPosition=lf.tell()
        lf.seek( self.position )
        while True:
            if currentEndPosition<=lf.tell():
                break
            rawLines = lf.read( self.defaultReadSize )
            index = rawLines.rfind( '\n' )
            if index < 0:
                break
            lf.seek( index + 1 - len( rawLines ), os.SEEK_CUR )
            logLines = rawLines[0:index].splitlines()
            result = self.statisticTool.getStatisticData( logLines )
            self.alarm(result)
            if len( result ) > 0:
                if not self.debug:
                    self.dbop.insert( result )
                if not self.debug and len( result ) > 20:
                    self.logger.info( '@@logHandler@@ file=%s, insert.status=success, data.length=%s, data=%s, ..., %s' % (self.curFile, len(result), result[0:10], result[-10:]) )
                else:
                    self.logger.info( '@@logHandler@@ file=%s, insert.status=success, data.length=%s, data=%s' % (self.curFile, len(result), result) )
            else:
                self.logger.info( '@@logHandler@@ file=%s, insert.status=empty' % self.curFile)
        self.position=lf.tell()
        self.preFileSize=os.stat(self.curFile).st_size
        lf.close()

    def alarm(self,result):
        for res in result:
            if self.keyAlarmers.has_key(res[1]):
                for alarmer in self.keyAlarmers[res[1]]:
                    alarmer.alarm(res[2],self.cubeName,res[1])

