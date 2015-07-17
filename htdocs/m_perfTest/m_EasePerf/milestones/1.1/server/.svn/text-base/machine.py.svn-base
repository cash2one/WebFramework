#!/usr/bin/env python
# coding=utf-8
# #此脚本不断监控机器的状态
import os, sys, time, datetime
import re
import logging
import subprocess
import optparse

import dbOperator
from daemon import Daemon



class MachineKeyType:
    SWAP_IN = "swap.swap_in"
    SWAP_OUT = "swap.swap_out"
    BLOCK_IN = "block.block_in"
    BLOCK_OUT = "block.block_out"
    SYS_IN = "sys_in"
    SYS_CS = "sys_cs"
    SYS_LOAD = "sys_load"
    CPU_US = "cpu.cpu_us"
    CPU_SY = "cpu.cpu_sy"
    CPU_ID = "cpu.cpu_id"
    CPU_WA = "cpu.cpu_wa"
    MEM_TOTAL = "mem.mem_total"
    MEM_USED = "mem.mem_used"
    MEM_BUFFER = "mem.mem_buffer"
    MEM_CACHE = "mem.mem_cache"
    USER_CPU = "users_cpu"
    USER_MEM = "users_mem"
    @staticmethod
    def buildAllMachineKeys():
        '''
        创建机器需要监控的关键信息
        '''
        skeys = list()
        skeys.append( MachineKeyType.BLOCK_IN )
        skeys.append( MachineKeyType.BLOCK_OUT )
        skeys.append( MachineKeyType.CPU_ID )
        skeys.append( MachineKeyType.CPU_SY )
        skeys.append( MachineKeyType.CPU_US )
        skeys.append( MachineKeyType.CPU_WA )
        skeys.append( MachineKeyType.SWAP_IN )
        skeys.append( MachineKeyType.SWAP_OUT )
        skeys.append( MachineKeyType.SYS_CS )
        skeys.append( MachineKeyType.SYS_IN )
        skeys.append( MachineKeyType.SYS_LOAD )
        skeys.append( MachineKeyType.MEM_TOTAL )
        skeys.append( MachineKeyType.MEM_USED )
        skeys.append( MachineKeyType.MEM_BUFFER )
        skeys.append( MachineKeyType.MEM_CACHE )
        skeys.append( MachineKeyType.USER_CPU )
        skeys.append( MachineKeyType.USER_MEM )
        return skeys

class MachineTools:
    '''
    系统相关工具
    '''
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
    def getMachineInfo():
        '''
        获取机器信息
        '''
        sysinfo = {}
        # 获取负载
        cmd = "w|grep 'load average'| awk '{print $(NF-2)}'"
        result = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE ).communicate()[0]
        machine_load = result.strip( "\r" ).strip( "\n" ).strip().strip( "," ).strip()
        sysinfo[MachineKeyType.SYS_LOAD] = machine_load


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
        while True:
            starttime = time.time()

            # 获取当前时间
            systime = int( time.mktime( datetime.datetime.now().timetuple() ) )
            systime = systime - systime % self.timeInterval
            # 获取进程信息
            sysinfo = MachineTools.getMachineInfo()

            value = list()
            value.append( ( systime, MachineKeyType.BLOCK_IN , sysinfo[ MachineKeyType.BLOCK_IN] ) )
            value.append( ( systime, MachineKeyType.BLOCK_OUT , sysinfo[ MachineKeyType.BLOCK_OUT] ) )
            value.append( ( systime, MachineKeyType.CPU_ID , sysinfo[ MachineKeyType.CPU_ID] ) )
            value.append( ( systime, MachineKeyType.CPU_SY , sysinfo[ MachineKeyType.CPU_SY] ) )
            value.append( ( systime, MachineKeyType.CPU_US , sysinfo[ MachineKeyType.CPU_US] ) )
            value.append( ( systime, MachineKeyType.CPU_WA , sysinfo[ MachineKeyType.CPU_WA] ) )
            value.append( ( systime, MachineKeyType.SWAP_IN , sysinfo[ MachineKeyType.SWAP_IN] ) )
            value.append( ( systime, MachineKeyType.SWAP_OUT , sysinfo[ MachineKeyType.SWAP_OUT] ) )
            value.append( ( systime, MachineKeyType.SYS_CS , sysinfo[ MachineKeyType.SYS_CS] ) )
            value.append( ( systime, MachineKeyType.SYS_IN , sysinfo[ MachineKeyType.SYS_IN] ) )
            value.append( ( systime, MachineKeyType.SYS_LOAD , sysinfo[ MachineKeyType.SYS_LOAD] ) )
            value.append( ( systime, MachineKeyType.MEM_TOTAL , sysinfo[ MachineKeyType.MEM_TOTAL] ) )
            value.append( ( systime, MachineKeyType.MEM_USED , sysinfo[ MachineKeyType.MEM_USED] ) )
            value.append( ( systime, MachineKeyType.MEM_BUFFER , sysinfo[ MachineKeyType.MEM_BUFFER] ) )
            value.append( ( systime, MachineKeyType.MEM_CACHE , sysinfo[ MachineKeyType.MEM_CACHE] ) )

            # 获取所有用户内存和cpu信息(只选择了top n)
            uservalues = MachineTools.getAllUserMemCpu( totalmem, topN = 12 )
            for username, uservalue in uservalues:
                value.append( ( systime, "%s.%s.cpu" % ( MachineKeyType.USER_CPU, username ) , uservalue['cpu'] ) )
                value.append( ( systime, "%s.%s.cpu" % ( MachineKeyType.USER_MEM, username ) , uservalue['mem'] ) )

            # 插入数据库
            db_instance.insert( value )

            # 等待一定时间
            indeedsleeptime = time.time() - starttime + self.timeInterval
            if indeedsleeptime > 0:
                time.sleep( indeedsleeptime )

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
