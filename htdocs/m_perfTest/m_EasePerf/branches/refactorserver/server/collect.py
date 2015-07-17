#!/usr/bin/python2.7
# encoding=utf-8
'''
性能测试工具收集

Created on 2013-4-7

@author: 张波
'''

import logging, os, sys, optparse, threading, re
import xml.etree.ElementTree

from daemon import Daemon
import perfUtils
from logHandleImpl import logHandleImpl
from jvmLogHandleImpl import jvmLogHandleImpl
from jvmLogHandleGCViewerImpl import jvmLogHandleGCViewerImpl
from processHandleImpl import ProcessInfoHandle
from processHandleImpl import UserInfoHandle
from processHandleImpl import ProcessHandle
from shellCommandHandleImpl import shellCommandHandle
from dbOperator import LogDBOperator



class Collect( Daemon ):
    def __init__( self, configfile, pidfile, logfile, logstarthead = False, logstartdate = 0 , notimestamp = False ):
        Daemon.__init__( self, os.path.abspath( pidfile ), stderr = os.path.abspath( logfile ), stdout = os.path.abspath( logfile ) )
        self.configFile = os.path.abspath( configfile )
        self.pidFile = os.path.abspath( pidfile )
        self.logFile = os.path.abspath( logfile )
        self.logStartDate = logstartdate
        self.logStartHead = logstarthead
        self.noTimeStamp = notimestamp


        self.__loggger = None
        self.__machineName = ""
        # 各个处理器
        self.__handlers = []


    def initHandle( self ):
        '''
        初始化
        '''
        self.__loggger = self.__getLogger()
        self.__machineName = perfUtils.getMachineName()

    def __getLogger( self ):
        '''
        获取日志处理
        '''
        logger = logging.getLogger()
        hdlr = logging.FileHandler( self.logFile, 'w', 'utf-8' )
        formatter = logging.Formatter( '%(asctime)s %(levelname)s %(message)s' )
        hdlr.setFormatter( formatter )
        logger.addHandler( hdlr )
        logger.setLevel( logging.DEBUG )
        return logger

    def __getNodeValue( self, root, xpath = None, defaultvalue = "" ):
        targetvalue = defaultvalue
        if xpath:
            taragetelement = root.find( xpath )
            if None != taragetelement and taragetelement.text:
                targetvalue = taragetelement.text
        else:
            targetvalue = root.text;
        return targetvalue

    def parseConfig( self ):
        '''
        解析配置文件
        '''
        if not self.configFile or not os.path.exists( self.configFile ):
            self.logger.error( '配置文件不存在. file: %s' % self.configFile )
            return False
        try:
            root = xml.etree.ElementTree.parse( self.configFile ).getroot()

            ldap = self.__getNodeValue( root, "db/ldap" )
            product = self.__getNodeValue( root, "db/product" )
            ptype = self.__getNodeValue( root, "db/type" )

            datanodes = root.findall( 'datas/data' )
            for datanode in datanodes:
                cubename = self.__getNodeValue( datanode, "cubename" )
                if not re.match( "^\w{2}\d{3}", cubename ):
                    cubename = self.__machineName + "_" + cubename

                # 创建dboper
                curdbop = LogDBOperator( ldap, product, ptype, cubename )

                timeinterval = int( self.__getNodeValue( datanode, "timeInterval" ) )

                # 读取logfile配置信息
                lognodes = datanode.findall( "logfiles/logfile" )
                if lognodes:
                    for lognode in lognodes:
                        logfilealias = self.__getNodeValue( lognode, "alias" )
                        multikey = self.__getNodeValue( lognode, "multiKey" ).lower().strip()
                        logfile = self.__getNodeValue( lognode, "logfile" )
                        numberkeys = self.__getNodeValue( lognode, "numberKeys" )
                        stringkeys = self.__getNodeValue( lognode, "stringKeys" )
                        ftimeinterval = int( self.__getNodeValue( lognode, "timeInterval", timeinterval ) )
                        datadleaytime = int( self.__getNodeValue( lognode, "dataDelayTime", ftimeinterval ) )

                        # 去除各字段首尾空格，以及空字段
                        realnumberkeys = [ str( item ).strip() for item in numberkeys.split( ',' ) if str( item ).strip() ]
                        realstringkeys = [ str( item ).strip() for item in stringkeys.split( ',' ) if str( item ).strip() ]

                        # 是否支持处理log中一行中有多个value
                        enablemultikey = False
                        if 'true' == multikey or "t" == multikey: enablemultikey = True
                        loghandler = logHandleImpl( logfile, logfilealias, ftimeinterval, realnumberkeys, realstringkeys, self.__loggger, curdbop, self.logStartHead, self.logStartDate, self.noTimeStamp , enablemultikey, datadleaytime )

                        if loghandler.isValid():
                            curdbop.addNumberKeys( realnumberkeys )
                            curdbop.addStringKeys( realstringkeys )
                            self.__handlers.append( loghandler )
                        else:
                            self.__loggger.error( "logfile配置错误. logfile: %s" % logfile )

                # 读取jvm文件配置信息
                jvmlognodes = datanode.findall( 'jvmlogs/jvmlog' )
                if jvmlognodes:
                    #默认使用gcviewer处理，如果jvm配置超过1个则使用默认的
                    usegcviewimpl = True
                    if len( jvmlognodes ) > 1: usegcviewimpl = False
                    for jvmlognode in jvmlognodes:
                        jvmlogalias = self.__getNodeValue( jvmlognode, "alias" )
                        jvmlogfile = self.__getNodeValue( jvmlognode, "logfile" )
                        jvmtimeinterval = int( self.__getNodeValue( jvmlognode, "timeInterval", timeinterval ) )
                        # 这里可以配置修改使用哪个jvmlog分析器
                        if usegcviewimpl:
                            jvmloghandler = jvmLogHandleGCViewerImpl( jvmlogfile, jvmlogalias, jvmtimeinterval, self.__loggger, curdbop, self.logStartHead, self.logStartDate )
                        else:
                            jvmloghandler = jvmLogHandleImpl( jvmlogfile, jvmlogalias, jvmtimeinterval, self.__loggger, curdbop, self.logStartHead, self.logStartDate )
                        if jvmloghandler.isValid():
                            curdbop.addOtherKeys( jvmloghandler.generateKeys() )
                            self.__handlers.append( jvmloghandler )
                        else:
                            self.__loggger.error( "jvm log配置错误. jvm: %s" % jvmlogalias )
                # 读取进程信息配置配置信息
                processnodes = datanode.findall( 'processes/process' )
                if processnodes:
                    ptimeinterval = int( self.__getNodeValue( datanode, 'processes/timeInterval', timeinterval ) )
                    pmtimeinterval = int( self.__getNodeValue( datanode, 'processes/mTimeInterval', ptimeinterval ) )
                    processhandler = ProcessHandle( ptimeinterval, self.__loggger, curdbop )
                    for processnode in processnodes:
                        processalias = self.__getNodeValue( processnode, "alias" )
                        processregexpattern = self.__getNodeValue( processnode, "regPattern" )
                        processpath = self.__getNodeValue( processnode, "processPath" )
                        processpid = self.__getNodeValue( processnode, "pid" )
                        mitems = self.__getNodeValue( processnode, "mItems" ).strip()


                        # 去除各字段首尾空格，以及空字段
                        processaliases = [ str( item ).strip() for item in processalias.split( ',' ) if str( item ).strip() ]
                        processpids = [ str( item ).strip() for item in processpid.split( ',' ) if str( item ).strip() ]
                        mitems = [ str( item ).strip() for item in mitems.split( ',' ) if str( item ).strip() ]
                        
                        # mitems采集频率
                        mitemfrequence =int(( pmtimeinterval + ptimeinterval -1)/ptimeinterval)
                        
                        processinfohandler = ProcessInfoHandle( processaliases, processregexpattern, processpath, processpids, mitems, mitemfrequence , self.__loggger )
                        if processinfohandler.isValid():
                            processhandler.addTargetHandle( processinfohandler )
                        else:
                            self.__loggger.error( "进程配置错误. process: %s" % processalias )
                    # 配置监控进程，默认监控其他人的进程信息
                    if processhandler.isValid():
                        userhandler = UserInfoHandle( self.__loggger )
                        processhandler.addTargetHandle( userhandler )

                    if processhandler.isValid():
                        curdbop.addOtherKeys( processhandler.generateKeys() )
                        self.__handlers.append( processhandler )

                # 读取shellcommand配置信息
                shellcmdnodes = datanode.findall( 'shellcommands/shellcommand' )
                if shellcmdnodes:
                    for shellcmdnode in shellcmdnodes:
                        cmdtype = self.__getNodeValue( shellcmdnode, "type" )
                        cmdname = self.__getNodeValue( shellcmdnode, "key" )
                        cmdptimeinterval = int( self.__getNodeValue( shellcmdnode, "timeInterval" , timeinterval ) )
                        cmdshell = self.__getNodeValue( shellcmdnode, "command" )

                        shellcmdinfohandler = shellCommandHandle( cmdname, cmdtype, cmdshell, cmdptimeinterval, self.__loggger, curdbop )
                        if shellcmdinfohandler.isValid():
                            curdbop.addOtherKeys( shellcmdinfohandler.generateKeys() )
                            self.__handlers.append( shellcmdinfohandler )
                        else:
                            self.__loggger.error( "shellcomdand配置错误. key: %s" % cmdname )
                # update all keys to db
                curdbop.updateKeys()
        except:
            self.__loggger.error( '配置文件格式错误. file: %s' % self.configFile )
            return False
        return True

    def run( self ):

        self.initHandle()
        self.__loggger.info( 'collect tool start' )
        self.__loggger.info( 'get user config' )
        # parse config
        if self.parseConfig():
            # run handle
            self.startMonitor()

    def startMonitor( self ):
        '''
        start monitor
        '''
        for handler in self.__handlers:
            threading.Thread( target = handler.startMonitor ).start()

def setSysCoding( coding ):
    if coding:
        if sys.getdefaultencoding() != coding:
            reload( sys )
            sys.setdefaultencoding( coding )

def main( argv ):
    setSysCoding( 'utf-8' )
    parser = optparse.OptionParser( usage = '''python collect.py start|stop|restart [-c config.xml]''' )
    parser.add_option( "-c", "--config", action = "store", type = "string", dest = "configFile" )
    parser.add_option( "-a", "--alllog", action = "store_true", dest = "alllog" )
    parser.add_option( "-n", "--notimestamp", action = "store_true", dest = "notimestamp" )
    parser.add_option( "-d", "--date", action = "store", type = "string", dest = "date" , help = "specify the date(date or number). eg: 2013-1-21 ,  1 refer to yesterday " )
    ( options, args ) = parser.parse_args( sys.argv[1:] )
    # multi collect process under same path
    if options.configFile == None:
        confFile = 'config.xml'
    else:
        confFile = options.configFile
    if confFile.endswith( '.xml' ):
        pidFile = confFile[0:-4] + '.pid'
        logFile = 'collect.' + confFile[0:-4] + '.log'
    else:
        pidFile = confFile + '.pid'
        logFile = 'collect.' + confFile + '.log'
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
