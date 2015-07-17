#!/usr/bin/python
# coding: utf-8
# filename: collect.py
##################################
# @author yinxj, cailm, zhangb
# @date 2013-01-07

import logging, xml.dom.minidom, os, sys, optparse, commands, time, threading, subprocess, re
import processor
import dbOperator
from daemon import Daemon

logger = None
PID = None
DEFAULT_READ_SIZE = 8 * 1024 * 1024
PROCESS_MONITOR_ITEMS = ['cpu', 'mem']
# INSERT_TO_DATABASE=False
INSERT_TO_DATABASE = True

class Collect(Daemon):
    def __init__( self, confFile, pidFile, logFile, handlefulllog, beforedays = 0 ):
        Daemon.__init__( self, pidFile )
        self.confFile = confFile
        self.pidFile = pidFile
        self.logFile = logFile
        self.beforeDays = beforedays
        self.handleFullLog = handlefulllog
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
            print 'ERROR 配置文件不存在'
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
            print 'ERROR datas 配置不正确'
            exit( -1 )
        datasDom = datasDomTmp[0].getElementsByTagName( 'data' )
        # step3: get data from datas
        for dataDom in datasDom:
            data = Data( self.ldap, self.product, self.type, self.handleFullLog, self.beforeDays )
            data.cubename = self.getText( dataDom, 'cubename', True, 'data' )
            data.logfile = self.getText( dataDom, 'logfile', False, data.cubename )
            timeInterval = self.getText( dataDom, 'timeInterval', True, data.cubename )
            if not timeInterval.isdigit():
                print 'ERROR %s: timeInterval 必须为整数' % self.cubename
                exit( -1 )
            data.timeInterval = int( timeInterval )
            data.numberKeys = self.getText( dataDom, 'numberKeys', False, data.cubename ).strip( ',' ).split( ',' )
            data.stringKeys = self.getText( dataDom, 'stringKeys', False, data.cubename ).strip( ',' ).split( ',' )
            # 每个data最多只能有一个processes, 下面可配多个process
            processesDomTmp = dataDom.getElementsByTagName( 'processes' )
            if len( processesDomTmp ) > 1:
                print 'ERROR %s: 每个data只能配一个processes的node' % data.cubename
                exit( -1 )
            if len( processesDomTmp ) == 0:
                print 'WARNING %s: 未配置processes, 不对进程做监控' % data.cubename
            else:
                processesDom = processesDomTmp[0].getElementsByTagName( 'process' )
                if len( processesDom ) == 0:
                    print 'WARNING %s: processes下未配置process, 不对进程做监控' % data.cubename
                for processDom in processesDom:
                    process = Process()
                    alias = self.getText( processDom, 'alias', False, data.cubename )
                    if alias == '':
                        print 'WARNING %s: 未配置alias' % data.cubename
                        continue
                    process.alias = alias.strip( ',' ).split( ',' )
                    process.regPattern = self.getText( processDom, 'regPattern', False, data.cubename ).strip()
                    process.processPath = self.getText( processDom, 'processPath', False, data.cubename ).strip()
                    pids = self.getText( processDom, 'pid', False, data.cubename ).strip()
                    if len( pids.replace( ',', '' ).replace( ' ', '' ) ) == 0:
                        process.pid = []
                    elif not pids.replace( ',', '' ).replace( ' ', '' ).isdigit():
                        print 'ERROR %s: pid 必须为整数' % data.cubename
                        exit( -1 )
                    else:
                        process.pid = pids.replace( ' ', '' ).split( ',' )
                    process.configCheck()
                    data.processes.append( process )
            data.configCheck()
            self.datas.append( data )
        self.printData()

    def getText( self, dom, tagName, mustHaveValue, parentName ):
        node = dom.getElementsByTagName( tagName )
        if len( node ) != 1 or len( node[0].childNodes ) == 0:
            if mustHaveValue:
                print 'ERROR %s of %s: 未配置' % ( tagName, parentName )
                exit( -1 )
            else:
                return ''
        if node[0].childNodes[0].nodeType not in [node[0].TEXT_NODE, node[0].CDATA_SECTION_NODE]:
            print 'ERROR %s of %s: 配置不正确' % ( tagName, parentName )
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
            logger.info( 'timeInterval=%s' % data.timeInterval )
            logger.info( 'logfile=%s' % data.logfile )
            logger.info( 'numberKeys=%s' % data.numberKeys )
            logger.info( 'stringKeys=%s' % data.stringKeys )
            logger.info( '---------- processes ---------' )
            for process in data.processes:
                logger.info( '----- process ------' )
                logger.info( 'alias=%s' % process.alias )
                logger.info( 'regPattern=%s' % process.regPattern )
                logger.info( 'processPath=%s' % process.processPath )
                logger.info( 'pid=%s' % process.pid )
        logger.info( '---------------------------------------------------------------------' )

class Data:
    def __init__( self, ldap, product, type, handlefulllog = False, beforedays = 0 ):
        self.beforeDays = beforedays
        self.handleFullLog = handlefulllog
        self.ldap = ldap
        self.product = product
        self.type = type
        self.cubename = ''
        self.timeInterval = 30
        self.logfile = ''
        self.numberKeys = []
        self.stringKeys = []
        self.machineKeys = []
        self.processes = []
        self.position = 0
        self.statisticTool = None
        self.dbop = None
        # logHandler与processHandler共用一个dbop, 需要加锁实现共用
        self.dbLock = None

    def configCheck( self ):
        if len( self.processes ) == 0 and self.logfile == '':
            print 'ERROR %s: 未配置待监控文件或进程' % self.cubename
            exit( -1 )
        if self.logfile != '' and ( len( self.numberKeys ) == 0 and len( self.stringKeys ) == 0 ):
            print 'ERROR %s: 未配置待监控文件 %s 需要监控的key' % ( self.cubename, self.logfile )
            exit( -1 )
        if self.logfile == '':
            print 'WARNING %s: 未配置待监控文件' % self.cubename

    def generateMachineKeys( self ):
        if len( PROCESS_MONITOR_ITEMS ) == 0 or len( self.processes ) == 0:
            return
        for process in self.processes:
            for alias in process.alias:
                for item in PROCESS_MONITOR_ITEMS:
                    self.machineKeys.append( alias + '.p' + item )


    # entrance
    def monitor( self ):
        self.generateMachineKeys()
        self.dbop = dbOperator.DBOperator( self.ldap, self.product, self.type, self.cubename, self.numberKeys, self.stringKeys, self.machineKeys )
        self.dbLock = threading.RLock()
        if self.logfile != '':
            self.statisticTool = processor.Processor( self.numberKeys + self.stringKeys, self.timeInterval, self.beforeDays )
            threading.Thread( target = self.logHandler ).start()
        if len( self.processes ) != 0:
            threading.Thread( target = self.processHandler ).start()

    def logHandler( self ):
        # 若文件存在,且不需要分析整个log，则指向末尾, 否则指向开头并等待文件生成
        if os.path.exists( self.logfile ) and not self.handleFullLog:
            LF = open( self.logfile )
            LF.seek( -1, 2 )
            self.position = LF.tell()
            LF.close()
        # 开始监控logfile
        while True:
            if not os.path.exists( self.logfile ):
                self.position = 0
                time.sleep( 5 )
                logger.warn( '%s: @@logHandler@@ 待监控文件不存在, 暂不做监控, 等待文件出现ing...' % self.cubename )
                continue
            self.log2db()
            logger.info( '%s: @@logHandler@@ sleeping, will wake up after %s seconds' % ( self.cubename, self.timeInterval ) )
            time.sleep( self.timeInterval )

    # 若单行log大于DEFAULT_READ_SIZE, 将会出bug: 无法往下继续读, 因此DEFAULT_READ_SIZE不宜过小, 但从资源消耗角度看, 也不宜过大
    # 每次均读至末尾
    def log2db( self ):
            LF = open( self.logfile )
            LF.seek( self.position )
            # read DEFAULT_READ_SIZE bytes per time
            while True:
                rawLines = LF.read( DEFAULT_READ_SIZE )
                if len( rawLines ) == 0 or rawLines.rfind( '\n' ) == -1:
                    break
                index = rawLines.rfind( '\n' )
                LF.seek( index + 1 - len( rawLines ), 1 )
                self.position = LF.tell()
                logLines = rawLines[0:index].splitlines()
                result = self.statisticTool.getStatisticData( logLines )
                logger.info( '%s: @@logHandler@@ result=%s' % ( self.cubename, result ) )
                # 与processHandler共用一个dbop
                if len( result ) > 0:
                    self.dbLock.acquire()
                    if INSERT_TO_DATABASE:
                        self.dbop.insert( result )
                    self.dbLock.release()
                    logger.info( '%s: @@logHandler@@ result inserted to database' % self.cubename )
            LF.close()

    def processHandler( self ):
        while True:
            currentTime = int( time.time() )
            currentTime = currentTime - currentTime % self.timeInterval
            systemData = []
            for process in self.processes:
                systemData += process.getSystemData()
            if len( systemData ) > 0:
                # add timestamp
                insertData = []
                for data in systemData:
                    insertData.append( [currentTime, data[0], data[1]] )
                logger.info( '%s: @@processHandler@@ result=%s' % ( self.cubename, insertData ) )
                # insert, 与logHandler共用一个dbop
                self.dbLock.acquire()
                if INSERT_TO_DATABASE:
                    self.dbop.insert( insertData )
                self.dbLock.release()
                logger.info( '%s: @@processHandler@@ result inserted to database' % self.cubename )
            else:
                logger.info( '%s: @@processHandler@@ no process data get' % self.cubename )
            endTime = int( time.time() )
            sleepTime = self.timeInterval - ( endTime - currentTime )
            if sleepTime <= 0:
                continue
            logger.info( '%s: @@processHandler@@ sleep, will wake up in %ss' % ( self.cubename, sleepTime ) )
            time.sleep( sleepTime )

class Process:
    def __init__( self ):
        self.alias = []
        self.regPattern = ''
        self.processPath = ''
        self.pid = []
        # total mem of machine, unit: mb
        self.totalMem = 0
        # 用于指向获取进程pid的函数
        self.getAllPid = None
        # 若非pid指定进程信息, 则每次都调用系统命令, 用于check进程的正常状态
        self.currentPidCmdPath = []

    def configCheck( self ):
        if len( self.alias ) == 0:
            print 'WARNING 未配置 process alias: 将不对进程做监控'
        if self.regPattern == '' and self.processPath == '' and len( self.pid ) == 0:
            print 'ERROR alias=%s: 每个process请至少用pid, processPath, regPattern中的一个来指定' % self.alias
            exit( -1 )
        if len( self.pid ) != 0 and len( self.pid ) != len( self.alias ):
            print 'ERROR alias=%s, pid=%s: process由pid指定, 但pid个数与alias个数不相同' % ( self.alias, self.pid )
            exit( -1 )
        # 若由pid指定process, 则regPattern及processPath无效
        if len( self.pid ) != 0:
            if len( self.regPattern ) != 0:
                print 'WARNING alias=%s, pid=%s, regPattern=%s: process由pid指定, regPattern配置无效' % ( self.alias, self.pid, self.regPattern )
                self.regPattern = ''
            if len( self.processPath ) != 0:
                print 'WARNING alias=%s, pid=%s, processPath=%s: process由pid指定, processPath配置无效' % ( self.alias, self.pid, self.processPath )
                self.processPath = ''
        self.postInit()

    # check通过后的初始化任务
    def postInit( self ):
        # 计算total_mem: 目前每个Process都会算一次, 不过占用资源较少, 暂不设为global或由Collect来计算并赋值
        meminfo = os.popen( "cat /proc/meminfo  |grep 'MemTotal'|awk '{print $2,$3}'", "r" ).read().split()
        self.totalMem = float( meminfo[0] )
        unit = meminfo[1].lower()
        if unit == 'kb':
            self.totalMem = self.totalMem / 1024
        # 目前的硬件水平... 后面两种应该没啥必要, 不过有备无患:)
        elif unit == 'mb':
            pass
        elif unit == 'gb':
            self.totalMem = self.totalMem * 1024
        else:
            logger.info( '%s: cannot get total mem' % self.alias )
            exit( -1 )
        # 指定获取进程的方法
        if len( self.pid ) != 0:
            self.getAllPid = self.getPid
        elif self.regPattern != '':
            if self.processPath != '':
                self.getAllPid = self.getPidByRegAndPath
            else:
                self.getAllPid = self.getPidByReg
        else:
            self.getAllPid = self.getPidByPath

    def getSystemData( self ):
        if len( self.pid ) == 0:
            self.refreshPidInfo()
        systemData = []
        pids = self.getAllPid()
        if len( pids ) != len( self.alias ):
            logger.info( 'alias=%s: 配置的进程数与检测到的个数不同, 配置的个数为%s, 当前监控到的个数为%s, 暂不对进程做监控' % ( self.alias, len( self.alias ), len( pids ) ) )
            return systemData
        if 'mem' in PROCESS_MONITOR_ITEMS and 'cpu' in PROCESS_MONITOR_ITEMS:
            memAndCpuData = self.getMemAndCpuData( pids )
        if len( memAndCpuData ) != 0:
            systemData += memAndCpuData
        return systemData

    def refreshPidInfo( self ):
        self.currentPidCmdPath = []
        # 获取当前user的进程pid, cmd, path的shell命令 (返回格式执行可见):
        # ps x --columns=100000 | sed -r 's/^\s*(.*)/\1/' | grep -P "^\s*\d" | grep -v -P "\d+:\d+\s+(ps|grep|sed|ssh|-bash|ls|sh)" | sed -r 's/^([0-9]*).*[0-9]+:[0-9]+\s+(.*)/echo -ne "\1\\\\t\2\\\\t";ls -l \/proc\/\1\/cwd/' | grep -v columns=100000 | sh | grep -P "^\d" | sed -r "s/([0-9]*)\t(.*)\t.*-> (.*)/\1\t\2\t\3/"
        # 1. ps -x (可改为用ps -xo) 注:ps 命令会有截断, 设为10w长应该可以对付一般情况
        # 2. 去掉非进程开头的行
        # 3. 去掉shell本身的进程 注: 过滤了sh, top等进程, 而一般的监控场景也不是对此类进程做监控
        # 4. 获取pid及path
        # 5. ls -l /proc/pid/cwd获取进程cwd 注: ls -1 /proc/pid/cwd时可能会有Permission denied的情况, 因此需要对返回值做处理
        # 6. escape真是很xx...!!!: reg+sed(还好用了-r)+echo -ne+python+...
        cmd = "ps x --columns=100000 | sed -r \"s/^\s*(.*)/\\1/\" |grep -P \"^\s*\d\" | grep -v -P \"\d+:\d+\s+(ps|grep|sed|ssh|top|tail|less|tail|more|-bash|ls|sh|vim?\s)\" | sed -r 's/^([0-9]*).*[0-9]+:[0-9]+\s+(.*)/echo -ne \"\\1\\\\t\\2\\\\t\";ls -l \/proc\/\\1\/cwd/' | grep -v columns=100000 | sh  | grep -P \"^\d\" | sed -r \"s/([0-9]*)\\\\t(.*)\\t.*-> (.*)/\\1\\\\t\\2\\\\t\\3/\""
        lines = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE, stderr = subprocess.PIPE ).communicate()[0].splitlines()
        for line in lines:
            if 'Permission denied' in line:
                continue
            datas = line.split()
            if len( datas ) < 3:
                continue
            if len( datas ) == 3:
                self.currentPidCmdPath.append( [datas[0], datas[1], datas[2]] )
            else:
                self.currentPidCmdPath.append( [datas[0], ' '.join( datas[1:-1] ), datas[-1]] )
        logger.info( 'alias=%s: update user\'s process info successfully, pidCmdPath.info=%s' % ( self.alias, self.currentPidCmdPath ) )

    def getPid( self ):
        return self.pid

    def getPidByReg( self ):
        pids = []
        for pid, cmd, path in self.currentPidCmdPath:
            # 去掉进程本身的pid
            if int( pid ) == PID:
                continue
            if len( re.findall( self.regPattern, cmd ) ) != 0:
                pids.append( pid )
        logger.info( 'ailas=%s: getPidByRegPattern=%s' % ( self.alias, pids ) )
        return pids

    def getPidByPath( self ):
        pids = []
        for pid, cmd, path in self.currentPidCmdPath:
            if path == '/' and self.processPath != '/':
                continue
            # 去掉进程本身的pid
            if int( pid ) == PID:
                continue
            if self.processPath.strip( '/' ) in path.strip( '/' ):
                pids.append( pid )
        logger.info( 'ailas=%s: getPidByPath=%s' % ( self.alias, pids ) )
        return pids

    def getPidByRegAndPath( self ):
        regPid = self.getPidByReg()
        pathPid = self.getPidByPath()
        pids = []
        for pid in regPid:
            if pid in pathPid:
                pids.append( pid )
        return pids

    def getMemAndCpuData( self, currentPids ):
        memAndCpuData = []
        cmd = "top -n1 -b -p %s | tail -n %s" % ( ','.join( currentPids ), len( currentPids ) + 1 ) + " | awk '{printf(\"%s %s %s\\n\",$1,$9,$10);}' "
        results = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE ).communicate()[0].strip().splitlines()
        logger.info( 'alias=%s, cmd=%s, result=%s' % ( self.alias, cmd, results ) )
        for result in results:
            pidcpumem = result.split()
            # 正常情况下每行应只有\s\d\.
            # 若有进程已被kill掉, len(pidcpumem)可能不为3
            if result.upper() != result.lower() or len( pidcpumem ) != 3:
                logger.warn( 'alias=%s: 获取到异常进程信息: result=%s' % ( self.alias, result ) )
                break
            memAndCpuData.append( [self.alias[currentPids.index( pidcpumem[0] )] + '.pcpu', float( pidcpumem[1] )] )
            memAndCpuData.append( [self.alias[currentPids.index( pidcpumem[0] )] + '.pmem', float( pidcpumem[2] ) * self.totalMem / 100] )
        logger.info( 'alias=%s, memAndCpuData=%s' % ( self.alias, memAndCpuData ) )
        return memAndCpuData

def main( argv ):
    reload( sys )
    sys.setdefaultencoding( 'utf-8' )
    parser = optparse.OptionParser( usage = '''python collect.py start|stop|restart [-c config.xml]''' )
    parser.add_option( "-c", "--config", action = "store", type = "string", dest = "confFile" )
    parser.add_option( "-a", "--alllog", action = "store_true", dest = "alllog" )
    parser.add_option( "-d", "--date", action = "store", type = "string", dest = "date" , default = 0, help = "specify the date(date or number). eg: 2013-1-21 ,  1 refer to yesterday " )
    ( options, args ) = parser.parse_args( sys.argv[1:] )
    # multi collect process under same path
    if options.confFile == None:
        confFile = 'config.xml'
    else:
        confFile = options.confFile
    if confFile.endswith( '.xml' ):
        pidFile = confFile[0:-4] + '.pid'
        logFile = 'collect.log.' + confFile[0:-4]
    else:
        pidFile = confFile + '.pid'
        logFile = 'collect.log.' + confFile
    # start or stop
    if len( args ) == 1 and args[0] in ['start', 'stop', 'restart']:
        collect = Collect( os.path.abspath( confFile ), os.path.abspath( pidFile), os.path.abspath( logFile ), options.alllog, options.date )
        if args[0] == 'start':
            collect.start()
        elif args[0] == 'stop':
            collect.stop()
        else:
            collect.restart()
    else:
        parser.print_help()
        exit(-1)

if __name__ == "__main__":
    main( sys.argv )


# todo:
# 1. log打印线程名 (?): middle
# 2. 进程配置增加指定pid所在文件的方式: high
# 3. 拆分Collect, Data, Process 三个类以便于多人开发: high
# 4. 拆分logfile及processes, 每个logfile每个process各一块: high
