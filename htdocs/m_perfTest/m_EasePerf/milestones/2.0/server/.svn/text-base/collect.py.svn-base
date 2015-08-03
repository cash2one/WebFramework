#!/usr/bin/python
# coding: utf-8
# filename: collect.py
##################################
# @author yinxj, cailm, zhangb
# @date 2013-01-07

import logging
import xml.dom.minidom
import os
import sys
import optparse
import commands
import threading
import subprocess
import re
import datetime
import time
import dbOperator
from keyValueLogTool import KeyValueLogTool
from gcLogTool import GCLogTool
from daemon import Daemon
from data import Data
from process import Process
from alarmer import Alarmer
from dbOperator import DBOperator
from logFile import LogFile
from commandKey import CommandKey

logger = None

class Collect( Daemon ):
    def __init__( self,confFile,pidFile,loggerFile):
        Daemon.__init__( self,pidFile,stderr=loggerFile,stdout=loggerFile)
        self.confFile = confFile
        self.pidFile = pidFile
        self.loggerFile = loggerFile
        self.datas = []
        # debug
        self.debug=False
        # user info
        self.ldap=None
        self.product=None
        self.type=None
        self.phone=None
        self.maxAlarmInterval=None
        # 各gc file的gc keys及alarmer相同
        self.gcKeyFile='conf/gc.conf'
        self.gcKeys=[]
        # 常量
        self.DEFAULT_LOGFILE_TIME_INTERVAL=30
        self.DEFAULT_GCLOGFILE_TIME_INTERVAL=600
        self.DEFAULT_PROCESS_TIME_INTERVAL=10
        self.DEFAULT_COMMAND_TIME_INTERVAL=10
        self.PROCESS_KEYS=['mem','cpu','fds','netLinks','threads']

    def run( self ):
        # logger
        self.getLogger()
        logger.info( 'collect tool start' )
        logger.info( 'get user config' )
        # 解析gcKeys配置, 必须在readConf()之前
        self.getGCKeys()
        self.readConf()
        # 将配置文件信息记录到日志中, 便于确认
        self.printData()
        for data in self.datas:
            threading.Thread( target = data.monitor ).start()

    def getLogger( self ):
        global logger
        logger = logging.getLogger()
        hdlr = logging.FileHandler( self.loggerFile )
        formatter = logging.Formatter( '%(asctime)s %(levelname)s %(message)s' )
        hdlr.setFormatter( formatter )
        logger.addHandler( hdlr )
        if self.debug:
            logger.setLevel( logging.DEBUG )
        else:
            logger.setLevel( logging.INFO )

    def getGCKeys(self):
        # 格式说明:
        # 以'|'为分隔, 以'#'开头的行为注释
        # 第一列为key, 第二列为该key的含义, 第三列为单位, 第四列为alarm配置
        f=open(self.gcKeyFile)
        while True:
            line=f.readline()
            if line=='':
                break
            if line.startswith('#'):
                continue
            items=line.replace(' ','').split('|')
            if len(items)!=4:
                continue
            self.gcKeys.append(items[0])
        f.close()

    def getGCKeyAlarmers(self):
        # 格式说明:
        # 以'|'为分隔, 以'#'开头的行为注释
        # 第一列为key, 第二列为该key的含义, 第三列为单位, 第四列为alarm配置
        gcKeyAlarmers={}
        f=open(self.gcKeyFile)
        while True:
            line=f.readline()
            if line=='':
                break
            if line.startswith('#'):
                continue
            items=line.strip().replace(' ','').split('|')
            if len(items)!=4:
                continue
            if items[3]!='':
                keyAlarmers=self.parseAlarmers(items[3])
                if len(keyAlarmers)>0:
                    gcKeyAlarmers[items[0]]=keyAlarmers
                else:
                    logger.info(items[3])
        f.close()
        return gcKeyAlarmers
        

    # 读取配置文件至datas中
    def readConf( self ):
        if not os.path.exists( self.confFile ):
            logger.error('配置文件不存在')
            exit( -1 )
        dom = xml.dom.minidom.parse( self.confFile )
        # step1: get user info
        # 配置文件有且只有一个userInfo
        userInfo = dom.getElementsByTagName( 'userInfo' )
        if len(userInfo)!=1:
            logger.error('userInfo 配置不正确')
            exit(-1)
        userInfo=userInfo[0]
        self.ldap = self.getText( userInfo, 'ldap', True, 'userInfo' )
        self.product = self.getText( userInfo, 'product', True, 'userInfo' )
        self.type = self.getText( userInfo, 'type', True, 'userInfo' )
        self.phone = self.getText( userInfo, 'phone', False, 'userInfo' )
        self.maxAlarmInterval = self.getText( userInfo, 'maxAlarmInterval', False, 'userInfo' )
        Alarmer.phone=self.phone
        Alarmer.email=self.ldap+'@rd.xxx.com'
        if self.maxAlarmInterval!='':
            Alarmer.maxAlarmInterval=int(self.maxAlarmInterval)
        self.maxAlarmInterval=Alarmer.maxAlarmInterval
        # step2: get datas
        # 配置文件有且只有一个datas
        datasDomTmp = dom.getElementsByTagName( 'datas' )
        if len( datasDomTmp ) != 1:
            logger.error('datas 配置不正确')
            exit( -1 )
        datasDom = datasDomTmp[0].getElementsByTagName( 'data' )
        machineHostname = commands.getoutput('hostname -s').strip()
        # step3: get data from datas
        for dataDom in datasDom:
            cubeName = self.getText( dataDom, 'cubeName', True, 'data' )
            if None == re.match( "\w{2}\d{3}", cubeName ):
                cubeName = machineHostname + "_" + cubeName
            data = Data(cubeName,self.ldap,self.product,self.type,logger)
            data.logFiles=self.getLogFilesFromData(dataDom,cubeName)
            data.gcLogFiles=self.getGCLogFilesFromData(dataDom,cubeName)
            data.processes=self.getProcessesFromData(dataDom,cubeName)
            data.commandKeys=self.getCommandKeysFromData(dataDom,cubeName)
            if data.validate():
                self.datas.append(data)

    def getLogFilesFromData(self,dataDom,cubeName):
        logFilesDom = dataDom.getElementsByTagName( 'logFile' )
        if len(logFilesDom)==0:
            logger.warning('%s: 未配置logFile, 不对服务日志做收集' % cubeName)
            return []
        logFiles=[]
        for logFileDom in logFilesDom:
            file = self.getText( logFileDom, 'fileName', False, cubeName )
            # 读取wholeFile配置, 若未配置则为False
            wholeFile = self.getText( logFileDom, 'wholeFile', False, cubeName )
            if wholeFile.lower()=='true':
                wholeFile=True
            else:
                wholeFile=False
            # 读取监控的时间间隔, 若未配置则为DEFAULT_LOGFILE_TIME_INTERVAL
            timeInterval = self.getText( logFileDom, 'timeInterval', False, cubeName )
            if timeInterval!='':
                self.assertDigit(timeInterval, cubeName, 'timeInterval')
                timeInterval = int( timeInterval )
            else:
                timeInterval=self.DEFAULT_LOGFILE_TIME_INTERVAL
            # 读取key
            numberKeys = self.getText( logFileDom, 'numberKeys', False, cubeName ).split( ',' )
            stringKeys = self.getText( logFileDom, 'stringKeys', False, cubeName ).split( ',' )
            # 去除各字段首尾空格，以及空字段
            numberKeys = [ str( item ).strip() for item in numberKeys if str( item ).strip() ]
            stringKeys = [ str( item ).strip() for item in stringKeys if str( item ).strip() ]
            # 读取beforeDays配置, 默认为0
            beforeDays=0
            # 读取noTimeStamp配置, 默认为False
            noTimeStamp=False
            # 读取alarm keys
            keyAlarmers=self.parseAlarmKeys(self.getText(logFileDom,'alarmKeys',False,cubeName))
            statisticTool=KeyValueLogTool(numberKeys+stringKeys,timeInterval,beforeDays,noTimeStamp,logger)
            logFile=LogFile(self.ldap,self.product,self.type,file,timeInterval,numberKeys,stringKeys,statisticTool,wholeFile,keyAlarmers,self.debug,logger,cubeName)
            if logFile.validate():
                logFiles.append(logFile)
        return logFiles

    def getGCLogFilesFromData(self,dataDom,cubeName):
        gcLogFilesDom = dataDom.getElementsByTagName( 'gcLogFile' )
        if len(gcLogFilesDom)==0:
            logger.warning('%s: 未配置gcLogFile, 不对服务的gc日志做收集' % cubeName)
            return []
        gcLogFiles=[]
        for gcLogFileDom in gcLogFilesDom:
            file = self.getText( gcLogFileDom, 'fileName', False, cubeName )
            # 读取wholeFile配置, 若未配置则为False
            wholeFile = self.getText( gcLogFileDom, 'wholeFile', False, cubeName )
            if wholeFile.lower()=='true':
                wholeFile=True
            else:
                wholeFile=False
            # 读取监控的时间间隔, 若未配置则为DEFAULT_GCLOGFILE_TIME_INTERVAL
            timeInterval = self.getText( gcLogFileDom, 'timeInterval', False, cubeName )
            if timeInterval!='':
                self.assertDigit(timeInterval, cubeName, 'timeInterval')
                timeInterval = int( timeInterval )
            else:
                timeInterval=self.DEFAULT_GCLOGFILE_TIME_INTERVAL
            # 读取alarm keys
            if 'on' in self.getText(gcLogFileDom,'alarm',False,cubeName):
                # 可能会有多个gc files, 因此需要deepcopy
                keyAlarmers=self.getGCKeyAlarmers()
            else:
                keyAlarmers={}
            statisticTool=GCLogTool(logger,self.gcKeys)
            gcLogFile=LogFile(self.ldap,self.product,self.type,file,timeInterval,self.gcKeys,[],statisticTool,wholeFile,keyAlarmers,self.debug,logger,cubeName)
            if gcLogFile.validate():
               gcLogFiles.append(gcLogFile)
        return gcLogFiles

    def getProcessesFromData(self,dataDom,cubeName):
        processesDom = dataDom.getElementsByTagName( 'process' )
        if len(processesDom)==0:
            logger.warning('%s: 未配置process, 不对进程做收集' % cubeName)
            return []
        processes=[]
        for processDom in processesDom:
            # 读取监控的时间间隔, 若未配置则为DEFAULT_PROCESS_TIME_INTERVAL
            timeInterval = self.getText( processDom, 'timeInterval', False, cubeName )
            if timeInterval!='':
                self.assertDigit(timeInterval, cubeName, 'timeInterval')
                timeInterval = int( timeInterval )
            else:
                timeInterval=self.DEFAULT_PROCESS_TIME_INTERVAL
            alias = self.getText( processDom, 'alias', False, cubeName ).replace(' ','')
            if alias=='':
                logger.warning('%s: process未配置alias, 不对进程做收集' % cubeName)
                continue
            alias = alias.strip( ',' ).split( ',' )
            regPattern = self.getText( processDom, 'regPattern', False, cubeName ).strip()
            processPath = self.getText( processDom, 'processPath', False, cubeName ).strip()
            pids = self.getText( processDom, 'pid', False, cubeName ).strip().replace(' ','')
            if len( pids ) == 0:
                pids = []
            else:
                self.assertDigit( pids.replace( ',', '' ), cubeName, 'pid')
                pids = pids.split( ',' )
            keys=self.getText( processDom, 'keys', True, cubeName )
            if keys=='*':
                keys=self.PROCESS_KEYS
            else:
                keys=keys.split(',')
                keys = [ str( item ).strip() for item in keys if str( item ).strip() ]
            keyAlarmers=self.parseAlarmKeys(self.getText(processDom,'alarmKeys',False,cubeName))
            process=Process(self.ldap,self.product,self.type,alias,regPattern,processPath,pids,keys,keyAlarmers,logger,cubeName,self.debug,timeInterval)
            if process.validate():
                processes.append(process)
        return processes

    def getCommandKeysFromData(self,dataDom,cubeName):
        commandKeysDom = dataDom.getElementsByTagName('commandKey')
        if len(commandKeysDom)==0:
            logger.warning('%s: 未配置commandKey, 不做command的收集' % cubeName)
            return []
        commandKeys=[]
        for commandKeyDom in commandKeysDom:
            # 读取监控的时间间隔, 若未配置则为DEFAULT_COMMAND_TIME_INTERVAL
            timeInterval = self.getText( commandKeyDom, 'timeInterval', False, cubeName )
            if timeInterval!='':
                self.assertDigit(timeInterval, cubeName, 'timeInterval')
                timeInterval = int( timeInterval )
            else:
                timeInterval=self.DEFAULT_COMMAND_TIME_INTERVAL
            # 前台php/readLog.php中强制要求key中带有"."的bug导致, 暂不改前台... 
            key=self.getText(commandKeyDom,'key',False,cubeName)
            if key=='':
                logger.warning('%s: commandKey的key未配置, 不做commandKey的收集' % cubeName)
                continue
            key='commandKey.'+key
            command=self.getText(commandKeyDom,'command',True,cubeName)
            alarmers=self.parseAlarmers(self.getText(commandKeyDom,'alarm',False,cubeName))
            commandKey=CommandKey(self.ldap,self.product,self.type,key,command,alarmers,logger,cubeName,self.debug,timeInterval)
            if commandKey.validate():
                commandKeys.append(commandKey)
        return commandKeys

    def parseAlarmKeys(self,str):
        if str=='':
            return {}
        keyAlarmers={}
        key_value_factor=re.findall('([\^\w\.\-_\{\}]+):([\d\.]+)([\+-]+)',str.replace(' ',''))
        for key,value,factor in key_value_factor:
            if factor=='++' and float(value)<=1:
                logger.warning('%s: value must > 1 for type "++"' % str)
                exit(-1)
            alarmer=Alarmer(float(value),factor,logger)
            if keyAlarmers.has_key(key):
                keyAlarmers[key].append(alarmer)
            else:
                keyAlarmers[key]=[alarmer]
        return keyAlarmers

    def parseAlarmers(self,str):
        if str=='':
            return []
        alarmers=[]
        value_factor=re.findall('([\d\.]+)([\+-]+)',str.replace(' ',''))
        for value,factor in value_factor:
            if factor=='++' and float(value)<=1:
                logger.warning('%s: value must > 1 for type "++"' % str)
                exit(-1)
            alarmers.append(Alarmer(float(value),factor,logger))
        return alarmers

    def assertDigit(self, child, parentName, tagName):
        if not child.isdigit():
            logger.error('%s: %s 必须为整数' % (parentName, tagName))
            exit( -1 )

    def getText( self, dom, tagName, mustHaveValue, parentName ):
        node = dom.getElementsByTagName( tagName )
        if len( node ) != 1 or len( node[0].childNodes ) == 0:
            if mustHaveValue:
                logger.error('%s of %s: 未配置' % ( tagName, parentName ))
                exit( -1 )
            else:
                return ''
        if node[0].childNodes[0].nodeType not in [node[0].TEXT_NODE, node[0].CDATA_SECTION_NODE]:
            logger.error('%s of %s: 配置不正确' % ( tagName, parentName ))
            exit( -1 )
        return node[0].childNodes[0].data.strip()

    def printData( self ):
        logger.info( '---------------------------- user info ----------------------------' )
        logger.info( 'ldap=%s' % self.ldap )
        logger.info( 'product=%s' % self.product )
        logger.info( 'type=%s' % self.type )
        logger.info( 'phone=%s' % self.phone )
        logger.info( '------------------------------ datas ------------------------------' )
        for data in self.datas:
            logger.info( '----------------------- data --------------------------' )
            logger.info( 'cubeName=%s' % data.cubeName )
            for logFile in data.logFiles:
                logger.info( '----------------- logFile ----------------' )
                logger.info( logFile.toString() )
            for gcLogFile in data.gcLogFiles:
                logger.info( '---------------- gcLogFile ---------------' )
                logger.info( gcLogFile.toString() )
            for commandKey in data.commandKeys:
                logger.info( '--------------- commandKey ---------------' )
                logger.info( commandKey.toString() )
            for process in data.processes:
                logger.info( '----------------- process ----------------' )
                logger.info( process.toString() )
        logger.info( '-------------------------------------------------------------------' )

def main( argv ):
    reload( sys )
    sys.setdefaultencoding( 'utf-8' )
    parser = optparse.OptionParser( usage = '''python collect.py start|stop|restart [-c config.xml]''' )
    parser.add_option( "-c", "--config", action = "store", type = "string", dest = "confFile" )
    ( options, args ) = parser.parse_args( sys.argv[1:] )
    # multi collect process under same path
    if options.confFile == None:
        confFile = 'config.xml'
    else:
        confFile = options.confFile
    if confFile.endswith( '.xml' ):
        pidFile = confFile[0:-4] + '.pid'
        loggerFile = '%s.log' % confFile[0:-4]
    else:
        pidFile = confFile + '.pid'
        loggerFile = '%s.log' % confFile
    # start or stop
    if len( args ) == 1 and args[0] in ['start', 'stop', 'restart']:
        collect = Collect( os.path.abspath( confFile ), os.path.abspath( pidFile ), os.path.abspath( loggerFile ) )
        if args[0] == 'start':
            collect.start()
        elif args[0] == 'stop':
            collect.stop()
        else:
            collect.restart()
    else:
        parser.print_help()
        exit( -1 )

if __name__ == "__main__":
    main( sys.argv )
