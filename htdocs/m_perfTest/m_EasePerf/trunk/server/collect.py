#!/usr/bin/python
# coding: utf-8
# filename: collect.py
##################################
# @author yinxj, cailm, zhangb
# @date 2013-01-07

import logging, xml.dom.minidom, os, sys, optparse, commands, threading, subprocess, re
import datetime, time
import logProcessor
import dbOperator
from daemon import Daemon
from collectData import Data
from collectProcess import Process

logger = None

class Collect( Daemon ):
    def __init__( self, confFile, pidFile, logFile, handlefulllog = False, beforedays = 0 , notimestamp = False ):
        Daemon.__init__( self, pidFile, stderr=logFile, stdout=logFile)
        self.confFile = confFile
        self.pidFile = pidFile
        self.logFile = logFile
        self.beforeDays = beforedays
        self.handleFullLog = handlefulllog
        self.noTimeStamp = notimestamp
        # save datas defined in config file
        self.datas = []

    def run( self ):
        # logger
        self.getLogger()
        logger.info( 'collect tool start' )
        logger.info( 'get user config' )
        self.readConf()
        for data in self.datas:
            threading.Thread( target = data.monitor ).start()

    def getLogger( self ):
        global logger
        logger = logging.getLogger()
        hdlr = logging.FileHandler( self.logFile )
        formatter = logging.Formatter( '%(asctime)s %(levelname)s %(message)s' )
        hdlr.setFormatter( formatter )
        logger.addHandler( hdlr )
        logger.setLevel( logging.DEBUG )

    # 读取配置文件至datas中
    def readConf( self ):
        if not os.path.exists( self.confFile ):
            logger.error('配置文件不存在')
            exit( -1 )
        dom = xml.dom.minidom.parse( self.confFile )
        # step1: get user info
        self.ldap = self.getText( dom, 'ldap', True, 'root' )
        self.product = self.getText( dom, 'product', True, 'root' )
        self.type = self.getText( dom, 'type', True, 'root' )
        # step2: get datas
        # 配置文件有且只有一个datas
        datasDomTmp = dom.getElementsByTagName( 'datas' )
        if len( datasDomTmp ) != 1:
            logger.error('datas 配置不正确')
            exit( -1 )
        datasDom = datasDomTmp[0].getElementsByTagName( 'data' )
        machineHostname = commands.getoutput('hostname -s')
        # step3: get data from datas
        for dataDom in datasDom:
            data = Data( self.ldap, self.product, self.type, logger, self.handleFullLog, self.beforeDays , self.noTimeStamp )
            cubename = self.getText( dataDom, 'cubeName', True, 'data' )
            if None == re.match( "\w{2}\d{3}", cubename ):
                data.cubename = machineHostname + "_" + cubename
            else:
                data.cubename = cubename

            # 读取待监控的服务日志配置
            logFileDom = dataDom.getElementsByTagName( 'logFile' )
            if len(logFileDom) > 1:
                logger.error('%s: 每个data只能配一个logFile的node' % data.cubename)
                exit( -1 )
            elif len(logFileDom) == 0:
                logger.warning('%s: 未配置logFile, 不对服务日志做监控' % data.cubename)
            else:
                data.logFile = self.getText( logFileDom[0], 'fileName', False, data.cubename )
                # 读取监控的时间间隔
                timeInterval = self.getText( logFileDom[0], 'timeInterval', False, data.cubename )
                self.assertDigit(timeInterval, data.cubename, 'timeInterval')
                data.logTimeInterval = int( timeInterval )
                # 读取key
                numberKeys = self.getText( logFileDom[0], 'numberKeys', False, data.cubename ).split( ',' )
                stringKeys = self.getText( logFileDom[0], 'stringKeys', False, data.cubename ).split( ',' )
                # 去除各字段首尾空格，以及空字段
                data.numberKeys = [ str( item ).strip() for item in numberKeys if str( item ).strip() ]
                data.stringKeys = [ str( item ).strip() for item in stringKeys if str( item ).strip() ]

            # 读取待监控的gc日志配置
            gcLogFileDom = dataDom.getElementsByTagName( 'gcLogFile' )
            if len(gcLogFileDom) > 1:
                logger.error('%s: 每个data只能配一个gcLogFile的node' % data.cubename)
                exit( -1 )
            elif len(gcLogFileDom) == 0:
                logger.warning('%s: 未配置logFile, 不对服务日志做监控' % data.cubename)
            else:
                data.gcLogFile = self.getText( gcLogFileDom[0], 'fileName', False, data.cubename )
                # 读取监控的时间间隔
                timeInterval = self.getText( gcLogFileDom[0], 'timeInterval', False, data.cubename )
                self.assertDigit(timeInterval, data.cubename, 'timeInterval')
                data.gclogTimeInterval = int( timeInterval )

            # 读取待监控进程的配置
            # 每个data最多只能有一个processes, 下面可配多个process
            processesDomTmp = dataDom.getElementsByTagName( 'processes' )
            if len( processesDomTmp ) > 1:
                logger.error('%s: 每个data只能配一个processes的node' % data.cubename)
                exit( -1 )
            if len( processesDomTmp ) == 0:
                logger.warning('%s: 未配置processes, 不对进程做监控' % data.cubename)
            else:
                # 读取监控的时间间隔
                timeInterval = self.getText( processesDomTmp[0], 'timeInterval', False, data.cubename )
                self.assertDigit(timeInterval, data.cubename, 'timeInterval')
                data.processTimeInterval = int( timeInterval )

                processesDom = processesDomTmp[0].getElementsByTagName( 'process' )
                if len( processesDom ) == 0:
                    logger.warning('%s: processes下未配置process, 不对进程做监控' % data.cubename)
                for processDom in processesDom:
                    process = Process( logger )
                    alias = self.getText( processDom, 'alias', False, data.cubename ).replace(' ','')
                    if len(alias) == 0:
                        logger.warning('%s: 未配置alias' % data.cubename)
                        continue
                    process.alias = alias.strip( ',' ).split( ',' )
                    process.regPattern = self.getText( processDom, 'regPattern', False, data.cubename ).strip()
                    process.processPath = self.getText( processDom, 'processPath', False, data.cubename ).strip()
                    pids = self.getText( processDom, 'pid', False, data.cubename ).strip().replace(' ','')
                    if len( pids ) == 0:
                        process.pids = []
                    else:
                        self.assertDigit( pids.replace( ',', '' ), data.cubename, 'pid')
                        process.pids = pids.split( ',' )
                    process.configCheck()
                    data.processes.append( process )

            data.configCheck()
            self.datas.append( data )
        self.printData()

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
        return node[0].childNodes[0].data

    def printData( self ):
        logger.info( '---------------------------- user config ----------------------------' )
        logger.info( 'ldap=%s' % self.ldap )
        logger.info( 'product=%s' % self.product )
        logger.info( 'type=%s' % self.type )
        logger.info( '------------------------------ datas --------------------------------' )
        for data in self.datas:
            logger.info( '-------------- data -----------------' )
            logger.info( 'cubename=%s' % data.cubename )
            logger.info( '----------- logFile ----------' )
            logger.info( 'logFile=%s' % data.logFile )
            logger.info( 'numberKeys=%s' % data.numberKeys )
            logger.info( 'stringKeys=%s' % data.stringKeys )
            logger.info( 'timeInterval=%s' % data.logTimeInterval )
            logger.info( '---------- gcLogFile ---------' )
            logger.info( 'gcLogFile=%s' % data.gcLogFile )
            logger.info( 'timeInterval=%s' % data.gcLogTimeInterval )
            logger.info( '---------- processes ---------' )
            logger.info( 'timeInterval=%s' % data.processTimeInterval )
            for process in data.processes:
                logger.info( '----- process ------' )
                logger.info( 'alias=%s' % process.alias )
                logger.info( 'regPattern=%s' % process.regPattern )
                logger.info( 'processPath=%s' % process.processPath )
                logger.info( 'pid=%s' % process.pids )
        logger.info( '---------------------------------------------------------------------' )

def main( argv ):
    reload( sys )
    sys.setdefaultencoding( 'utf-8' )
    parser = optparse.OptionParser( usage = '''python collect.py start|stop|restart [-c config.xml]''' )
    parser.add_option( "-c", "--config", action = "store", type = "string", dest = "confFile" )
    parser.add_option( "-a", "--alllog", action = "store_true", dest = "alllog" )
    parser.add_option( "-n", "--notimestamp", action = "store_true", dest = "notimestamp" )
    parser.add_option( "-d", "--date", action = "store", type = "string", dest = "date" , default = 0, help = "specify the date(date or number). eg: 2013-1-21 ,  1 refer to yesterday " )
    ( options, args ) = parser.parse_args( sys.argv[1:] )
    # multi collect process under same path
    if options.confFile == None:
        confFile = 'config.xml'
    else:
        confFile = options.confFile
    if confFile.endswith( '.xml' ):
        pidFile = confFile[0:-4] + '.pid'
        logFile = '%s.log' % confFile[0:-4]
    else:
        pidFile = confFile + '.pid'
        logFile = '%s.log' % confFile
    # start or stop
    if len( args ) == 1 and args[0] in ['start', 'stop', 'restart']:
        collect = Collect( os.path.abspath( confFile ), os.path.abspath( pidFile ), os.path.abspath( logFile ), options.alllog, options.date, options.notimestamp )
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


# todo:
# 1. log打印线程名 (?): middle
# 2. 进程配置增加指定pid所在文件的方式: high
