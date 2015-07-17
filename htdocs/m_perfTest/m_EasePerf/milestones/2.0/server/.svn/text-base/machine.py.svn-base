#!/usr/bin/python2.7
# coding=utf-8
'''
性能测试工具收集
    监控机器的状态

Created on 2013-4-8

@author: 张波
'''

import os, sys, time, datetime
import re
import logging
import subprocess
import optparse

import dbOperator
from daemon import Daemon



class MachineKeyType:
    # 废弃监控项
    SWAP_IN = "swap.swap_in"
    SWAP_OUT = "swap.swap_out"
    BLOCK_IN = "block.block_in"
    BLOCK_OUT = "block.block_out"
    SYS_IN = "sys_in"
    SYS_CS = "sys_cs"

    LOAD_ONE = "sys_load.sys_load"
    LOAD_RQ = "sys_load.runqueue_size"  # Run queue length (number of processes waiting for run time)
    CPU_US = "cpu.cpu_us"
    CPU_SY = "cpu.cpu_sy"
    CPU_ID = "cpu.cpu_id"
    CPU_WA = "cpu.cpu_wa"
    MEM_TOTAL = "mem.mem_total"
    MEM_USED = "mem.mem_used"
    MEM_BUFFER = "mem.mem_buffer"
    MEM_CACHE = "mem.mem_cache"
    MEM_FREE = "mem.mem_free"
    DISK_READ = "disk.disk_read"
    DISK_WRITE = "disk.disk_write"
    PACK_IN = "package.package_in"
    PACK_OUT = "package.package_out"
    NETWORK_REC = "network.network_receive"
    NETWORK_SND = "network.network_send"

    USER_CPU = "users_cpu"
    USER_MEM = "users_mem"


    @staticmethod
    def buildAllMachineKeys():
        '''
        创建机器需要监控的关键信息
        '''
        skeys = list()
#        skeys.append( MachineKeyType.BLOCK_IN )
#        skeys.append( MachineKeyType.BLOCK_OUT )
#        skeys.append( MachineKeyType.SWAP_IN )
#        skeys.append( MachineKeyType.SWAP_OUT )
#        skeys.append( MachineKeyType.SYS_CS )
#        skeys.append( MachineKeyType.SYS_IN )
        skeys.append( MachineKeyType.CPU_ID )
        skeys.append( MachineKeyType.CPU_SY )
        skeys.append( MachineKeyType.CPU_US )
        skeys.append( MachineKeyType.CPU_WA )
        skeys.append( MachineKeyType.LOAD_ONE )
        skeys.append( MachineKeyType.LOAD_RQ )
        skeys.append( MachineKeyType.MEM_TOTAL )
        skeys.append( MachineKeyType.MEM_USED )
        skeys.append( MachineKeyType.MEM_BUFFER )
        skeys.append( MachineKeyType.MEM_CACHE )
        skeys.append( MachineKeyType.USER_CPU )
        skeys.append( MachineKeyType.USER_MEM )

        skeys.append( MachineKeyType.DISK_READ )
        skeys.append( MachineKeyType.DISK_WRITE )
        skeys.append( MachineKeyType.PACK_IN )
        skeys.append( MachineKeyType.PACK_OUT )
        skeys.append( MachineKeyType.NETWORK_REC )
        skeys.append( MachineKeyType.NETWORK_SND )
        return skeys

class MachineTools:
    '''
    系统相关工具
    '''

    '''
03:05:22 PM     CPU     %user     %nice   %system   %iowait    %steal     %idle
03:05:23 PM     all      8.96      0.00      5.47      0.00      0.00     85.57

03:05:22 PM       tps      rtps      wtps   bread/s   bwrtn/s
03:05:23 PM      0.00      0.00      0.00      0.00      0.00

03:05:22 PM kbmemfree kbmemused  %memused kbbuffers  kbcached  kbcommit   %commit
03:05:23 PM     26984   3081732     99.13    202208    708508  17654076    153.56

03:05:22 PM kbswpfree kbswpused  %swpused  kbswpcad   %swpcad
03:05:23 PM   1727876   6660044     79.40    310764      4.67

03:05:22 PM dentunusd   file-nr  inode-nr    pty-nr
03:05:23 PM    164726     94848    204777      1162

03:05:22 PM   runq-sz  plist-sz   ldavg-1   ldavg-5  ldavg-15
03:05:23 PM         0      5650      1.28      0.63      0.44

03:05:22 PM     IFACE   rxpck/s   txpck/s    rxkB/s    txkB/s   rxcmp/s   txcmp/s  rxmcst/s
03:05:23 PM      eth0    806.93    752.48     62.04     48.86      0.00      0.00      0.00
03:05:23 PM      eth1      0.00      0.00      0.00      0.00      0.00      0.00      0.00
03:05:23 PM        lo      0.00      0.00      0.00      0.00      0.00      0.00      0.00
03:05:23 PM     tunl0      0.00      0.00      0.00      0.00      0.00      0.00      0.00
03:05:23 PM      gre0      0.00      0.00      0.00      0.00      0.00      0.00      0.00    
    '''
    MachineKeyTypeMaping = { "%user":MachineKeyType.CPU_US, "%system":MachineKeyType.CPU_SY, \
                             "%iowait": MachineKeyType.CPU_WA, "%idle":MachineKeyType.CPU_ID, \
                             "kbmemfree":MachineKeyType.MEM_FREE, "kbmemused":MachineKeyType.MEM_USED, \
                             "kbbuffers": MachineKeyType.MEM_BUFFER, "kbcached":MachineKeyType.MEM_CACHE, \
                             "rtps":MachineKeyType.DISK_READ, "wtps":MachineKeyType.DISK_WRITE, \
                             "ldavg-1": MachineKeyType.LOAD_ONE, "runq-sz": MachineKeyType.LOAD_RQ, \
                             "rxpck/s":MachineKeyType.PACK_IN, "txpck/s":MachineKeyType.PACK_OUT, \
                             "rxkB/s": MachineKeyType.NETWORK_REC, "txkB/s":MachineKeyType.NETWORK_SND \
                             }

    @staticmethod
    def getMachineName():
        '''
        获取机器名称
        '''
        cmd = "uname -n"
        result = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE ).communicate()[0]
        result.strip( "\r" ).strip( "\n" ).strip()
        machinename = result.split( '.' )[0]
        return machinename

    @staticmethod
    def getCurrentUserName():
        '''
        获取当前用户信息
        '''
        cmd = "whoami"
        result = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE ).communicate()[0]
        result = result.strip( "\r" ).strip( "\n" ).strip()
        return result

    @staticmethod
    def getRawDatas( contents ):
        '''
        获取原始数据        
        '''
        rawdict = {}
        headline = None
        datalines = []
        alllines = contents.splitlines()
        alllines.append( "" )
        for line in alllines:
            line = line.strip( "\r" ).strip( "\n" ).strip()
            if line:
                if None == headline:
                    headline = line
                else:
                    datalines.append( line )
            else:
                if headline and datalines:
                    currawdict = {}
                    actionstatus = True
                    heads = re.split( "\s+", headline )
                    for dataline in datalines:
                        datas = re.split( "\s+", dataline )
                        if len( heads ) != len( datas ):
                            actionstatus = False
                            currawdict = {}
                            break
                        else:
                            for index, data in enumerate( datas ):
                                keyname = heads[index].strip()
                                keyvalue = data.strip()
                                try:
                                    keyvalue = float( keyvalue )
                                    if not currawdict.has_key( keyname ):
                                        currawdict[keyname] = keyvalue
                                    else:
                                        currawdict[keyname] += keyvalue
                                except:
                                    pass
                    if actionstatus and currawdict:
                        rawdict = dict( rawdict , **currawdict )
                headline = None
                datalines = []
        return rawdict



    @staticmethod
    def getMachineInfoBySar():
        '''
        获取当前用户信息
        '''
        cmd = 'sar -u -r -S -b -n DEV -q -v 1 1 | grep -v "Average"'
        # u: cpu    -r: mem    -S: swapmem    -b: io w/r frequece  -n DEV: network   -q: load    -v node
        result = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE ).communicate()[0]
        result = result.strip( "\r" ).strip( "\n" ).strip()
        rawdatas = MachineTools.getRawDatas( result )
        sysinfo = {}
        for key , value in rawdatas.iteritems():
            if MachineTools.MachineKeyTypeMaping.has_key( key ):
                mapkey = MachineTools.MachineKeyTypeMaping[key]
                sysinfo[mapkey] = value

        # 对结果进行单位转换和处理
        try:
            sysinfo[MachineKeyType.MEM_BUFFER] = int( sysinfo[MachineKeyType.MEM_BUFFER] / 1024 )
            sysinfo[MachineKeyType.MEM_CACHE] = int( sysinfo[MachineKeyType.MEM_CACHE] / 1024 )
            sysinfo[MachineKeyType.MEM_FREE] = int( sysinfo[MachineKeyType.MEM_FREE] / 1024 )
            sysinfo[MachineKeyType.MEM_USED] = int( sysinfo[MachineKeyType.MEM_USED] / 1024 )
            sysinfo[MachineKeyType.MEM_TOTAL] = sysinfo[MachineKeyType.MEM_FREE] + sysinfo[MachineKeyType.MEM_USED]
            del sysinfo[MachineKeyType.MEM_FREE]
        except:
            sysinfo = {}
        return sysinfo

    @staticmethod
    def getMachineInfo():
        '''
        获取机器信息
        '''
        sysinfo = {}
        # 获取负载
        cmd = "uptime | awk '{print $(NF-2)}'"
        result = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE ).communicate()[0]
        machine_load = result.strip( "\r" ).strip( "\n" ).strip().strip( "," ).strip()
        sysinfo[MachineKeyType.LOAD_ONE] = machine_load


        #  procs -----------memory---------- ---swap-- -----io---- --system-- -----cpu------
        # r  b   swpd   free   buff  cache   si   so    bi    bo   in   cs us sy id wa st
        cmd = "vmstat 1 2|tail -n 1"
        result = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE ).communicate()[0]
        result = result.strip( "\r" ).strip( "\n" ).strip()
        machine_infos = re.split( "\s+", result )
        sysinfo[ MachineKeyType.SWAP_IN ] = machine_infos[6]
        sysinfo[ MachineKeyType.SWAP_OUT ] = machine_infos[7]
        sysinfo[ MachineKeyType.BLOCK_IN ] = machine_infos[8]
        sysinfo[ MachineKeyType.BLOCK_OUT ] = machine_infos[9]
        sysinfo[ MachineKeyType.SYS_IN ] = machine_infos[10]
        sysinfo[ MachineKeyType.SYS_CS ] = machine_infos[11]
        sysinfo[ MachineKeyType.CPU_US ] = machine_infos[12]
        sysinfo[ MachineKeyType.CPU_SY ] = machine_infos[13]
        sysinfo[ MachineKeyType.CPU_ID ] = machine_infos[14]
        sysinfo[ MachineKeyType.CPU_WA ] = machine_infos[15]

        # 获取内存信息
        cmd = "free -m|grep 'Mem:'|awk '{printf \"%s %s %s %s\",$2,$3,$6,$7;}'"
        result = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE ).communicate()[0]
        result = result.strip( "\r" ).strip( "\n" ).strip()
        mem_infos = re.split( "\s+", result )
        sysinfo[ MachineKeyType.MEM_TOTAL ] = mem_infos[0]
        sysinfo[ MachineKeyType.MEM_USED ] = mem_infos[1]
        sysinfo[ MachineKeyType.MEM_BUFFER ] = mem_infos[2]
        sysinfo[ MachineKeyType.MEM_CACHE ] = mem_infos[3]
        return sysinfo

    @staticmethod
    def getMemSize():
        '''
        获取机器内存大小，返回单位M
        '''

        meminfo = os.popen( "cat /proc/meminfo  |grep 'MemTotal'|awk '{print $2,$3}'", "r" ).read().split()
        totalmem = float( meminfo[0] )
        unit = meminfo[1].lower()
        if unit == 'kb':
            totalmem = totalmem / 1024
        elif unit == 'mb':
            pass
        elif unit == 'gb':
            totalmem = totalmem * 1024
        return int( totalmem )

    @staticmethod
    def getAllUserMemCpu( totalmem = 1 , topN = -1 ):
        '''
        获取所有用户的cpu和内存信息, totalmem内存大小，默认范围的是内存比例
        '''
        cmd = "top -n1 b | grep -e \"^ *[0-9]\+\" | awk '{printf(\"%s %s %s\\n\",$2,$9,$10);}' "
        results = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE ).communicate()[0].strip().splitlines()
        alluserinfo = {}
        for result in results:
            lineinfos = result.split()
            # 正常情况下每行应只有\s\d\.
            # 若有进程已被kill掉, len(lineinfos)可能不为3
            if len( lineinfos ) != 3:
                continue
            try:
                username = lineinfos[0]
                cpurate = float( lineinfos[1] )
                memrate = float( lineinfos[2] )
                if not alluserinfo.has_key( username ):
                    alluserinfo[username] = {'all' : cpurate + memrate , 'cpu' : cpurate, 'mem' : memrate * totalmem / 100}
                else:
                    alluserinfo[username]['all'] += ( cpurate + memrate )
                    alluserinfo[username]['cpu' ] += cpurate
                    alluserinfo[username]['mem' ] += ( memrate * totalmem / 100 )
            except:
                pass
        # 排序
        alluserinfo = sorted( alluserinfo.iteritems(), key = lambda ( username, uservalue ): uservalue['all'], reverse = True )
        if topN > 0:
            alluserinfo = alluserinfo[: topN]
        return alluserinfo


class MachineMonitor( Daemon ):
    '''
    机器监控
    '''
    def __init__( self, timeinterval = 30, logfile = "machine_%s.log" % MachineTools.getMachineName(), loglevel = logging.INFO ):
        curfile = os.path.basename( sys._getframe().f_code.co_filename )
        curfile, ext = os.path.splitext( curfile )
        pidfile = os.path.abspath( curfile + "_" + MachineTools.getMachineName() + ".pid" )
        Daemon.__init__( self, os.path.abspath( pidfile ) )
        self.timeInterval = timeinterval
        self.targetFile = logfile
        self.logLevel = loglevel

    def getMachineLoger( self ):
        '''
        获取机器监控loger
        '''
        logger = logging.getLogger()
        # 可以在此处修改log的级别，分别为:DEBUG,INFO,WARN,ERROR
        logger.setLevel( self.logLevel )
        hdlr = logging.FileHandler( os.path.abspath( self.targetFile ) )
        formatter = logging.Formatter( '%(asctime)s|%(levelname)s|%(funcName)s: %(message)s', '%Y%m%d.%H%M%S' )
        hdlr.setFormatter( formatter )
        logger.addHandler( hdlr )
        return logger

    def startMonitorSys( self ):
        '''
        监控系统
        '''
        # 获取日志loger
        logger = self.getMachineLoger()

        # 获取机器名称
        machinename = MachineTools.getMachineName()

        # 创建数据库
        db_instance = dbOperator.MachineDBOperator( machinename, MachineKeyType.buildAllMachineKeys() )

        # 进程放入后台运行
        logger.info( "before createDaemon, pid=%d" % os.getpid() )
        logger.info( "after createDaemon, pid=%d" % os.getpid() )

        # 获取内存大小
        totalmem = MachineTools.getMemSize()

        # 开始监控并将信息写入输入库
        lastsystime = None
        queryintervaltime = self.timeInterval / 3 + 1
        while True:
            # 获取当前时间
            curtime = int( time.time() )
            systime = curtime - curtime % self.timeInterval

            # 等待一定时间，轮询间隔为 timeInterval的 1/3,避免
            # 程序调度本身需要一定时间，导致数据没有插入，有其是在负载高的时候)
            if lastsystime == systime:
                time.sleep( queryintervaltime )
                continue
            lastsystime = systime

            # 获取进程信息
            sysinfo = MachineTools.getMachineInfoBySar()

            values = list()
            for itemkey , itemvalue  in sysinfo.iteritems():
                values.append( ( systime, itemkey, itemvalue ) )
            # 获取所有用户内存和cpu信息(只选择了top n)
            uservalues = MachineTools.getAllUserMemCpu( totalmem, topN = 12 )
            for username, uservalue in uservalues:
                values.append( ( systime, "%s.%s.cpu" % ( MachineKeyType.USER_CPU, username ) , uservalue['cpu'] ) )
                values.append( ( systime, "%s.%s.mem" % ( MachineKeyType.USER_MEM, username ) , uservalue['mem'] ) )

            # 插入数据库
            db_instance.insert( values )
            for v in values:
                logger.info('%s\t%s\t%s' % (v[0],v[1],v[2]))

    def run( self ):
        self.startMonitorSys()


if __name__ == "__main__":
    parser = optparse.OptionParser( usage = '''python machine.py start|stop|restart [-option value] ''' )
    parser.add_option( "-i", "--timeInterval", action = "store", type = "int", dest = "timeInterval" , default = 30 )
    ( options, args ) = parser.parse_args( sys.argv[1:] )


    monitor = MachineMonitor( options.timeInterval )

    cmdstr = ""
    if len( sys.argv ) > 1:
        cmdstr = str( sys.argv[1] ).lower()
    if cmdstr in ["start", "stop", "restart"]:
        cmdstr = str( sys.argv[1] ).lower()
        if "start" == cmdstr:
            monitor.start()
        elif "stop" == cmdstr:
            monitor.stop()
        else:
            monitor.restart()
    else:
        parser.print_help()
        sys.exit( -1 )
