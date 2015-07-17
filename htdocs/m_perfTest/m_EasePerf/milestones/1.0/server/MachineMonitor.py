#!/usr/bin/env python
# coding=utf-8
# #此脚本不断监控机器的状态，包括load,mem,第二期改进的时候用
import os, sys, time, datetime
import re
import logging
import subprocess
import optparse
import xml.etree.ElementTree


import dbOperator
from lib import *




class MachineKeyType:
    SWAP_IN = "swap_in"
    SWAP_OUT = "swap_out"
    BLOCK_IN = "block_in"
    BLOCK_OUT = "block_out"
    SYS_IN = "sys_in"
    SYS_CS = "sys_cs"
    SYS_LOAD = "sys_load"
    CPU_US = "cpu_us"
    CPU_SY = "cpu_sy"
    CPU_ID = "cpu_id"
    CPU_WA = "cpu_wa"
    MEM_TOTAL = "mem_total"
    MEM_USED = "mem_used"
    MEM_BUFFER = "mem_buffer"
    MEM_CACHE = "mem_cache"
    @staticmethod
    def buildAllMachineKeys( machinename ):
        '''
        创建机器需要监控的关键信息
        '''
        skeys = list()
        skeys.append( MachineKeyType.buildMachineKey( machinename , MachineKeyType.BLOCK_IN ) )
        skeys.append( MachineKeyType.buildMachineKey( machinename , MachineKeyType.BLOCK_OUT ) )
        skeys.append( MachineKeyType.buildMachineKey( machinename , MachineKeyType.CPU_ID ) )
        skeys.append( MachineKeyType.buildMachineKey( machinename , MachineKeyType.CPU_SY ) )
        skeys.append( MachineKeyType.buildMachineKey( machinename , MachineKeyType.CPU_US ) )
        skeys.append( MachineKeyType.buildMachineKey( machinename , MachineKeyType.CPU_WA ) )
        skeys.append( MachineKeyType.buildMachineKey( machinename , MachineKeyType.SWAP_IN ) )
        skeys.append( MachineKeyType.buildMachineKey( machinename , MachineKeyType.SWAP_OUT ) )
        skeys.append( MachineKeyType.buildMachineKey( machinename , MachineKeyType.SYS_CS ) )
        skeys.append( MachineKeyType.buildMachineKey( machinename , MachineKeyType.SYS_IN ) )
        skeys.append( MachineKeyType.buildMachineKey( machinename , MachineKeyType.SYS_LOAD ) )
        skeys.append( MachineKeyType.buildMachineKey( machinename , MachineKeyType.MEM_TOTAL ) )
        skeys.append( MachineKeyType.buildMachineKey( machinename , MachineKeyType.MEM_USED ) )
        skeys.append( MachineKeyType.buildMachineKey( machinename , MachineKeyType.MEM_BUFFER ) )
        skeys.append( MachineKeyType.buildMachineKey( machinename , MachineKeyType.MEM_CACHE ) )
        return skeys

    @staticmethod
    def buildMachineKey( machinename , type ):
        '''
        创建机器需要监控的关键信息
        '''
        return machinename + ".s" + type

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



def getMachineLoger( logfile = "machine_monitor.log", level = logging.INFO ):
    '''
    获取机器监控loger
    '''
    LOGFILE = "/machine_monitor.log"
    logger = logging.getLogger()
    # 可以在此处修改log的级别，分别为:DEBUG,INFO,WARN,ERROR
    logger.setLevel( level )
    hdlr = logging.FileHandler( os.path.abspath( logfile ) )
    formatter = logging.Formatter( '%(asctime)s|%(levelname)s|%(funcName)s: %(message)s', '%Y%m%d.%H%M%S' )
    hdlr.setFormatter( formatter )
    logger.addHandler( hdlr )
    return logger

def startMonitorSys( ldap = None , product = None, type = None, cubename = None , timeinterval = 30 ):
    '''
    监控系统
    '''
    # 获取日志loger
    logger = getMachineLoger()

    # 获取机器名称
    machinename = MachineTools.getMachineName()
    if None == ldap:
        ldap = "Machine"
    if None == product:
        product = machinename[0:2]
        if "Machine" != ldap:
            product = "Machine_" + product
    if None == type:
        type = machinename[0:2]
    if None == cubename:
        cubename = machinename

    # 创建数据库
    db_instance = dbOperator.DBOperator( ldap, product, type, cubename, "", "", MachineKeyType.buildAllMachineKeys( machinename ) )

    # 进程放入后台运行
    logger.info( "before createDaemon, pid=%d" % os.getpid() )
    daemon.createDaemon( os.curdir )
    logger.info( "after createDaemon, pid=%d" % os.getpid() )

    # 开始监控并将信息写入输入库
    while True:
        starttime = time.time()

        # 获取当前时间
        systime = int( time.mktime( datetime.datetime.now().timetuple() ) )
        systime = systime - systime % timeinterval
        # 获取进程信息
        sysinfo = MachineTools.getMachineInfo()

        value = list()
        value.append( ( systime, MachineKeyType.buildMachineKey( machinename, MachineKeyType.BLOCK_IN ), sysinfo[ MachineKeyType.BLOCK_IN] ) )
        value.append( ( systime, MachineKeyType.buildMachineKey( machinename, MachineKeyType.BLOCK_OUT ), sysinfo[ MachineKeyType.BLOCK_OUT] ) )
        value.append( ( systime, MachineKeyType.buildMachineKey( machinename, MachineKeyType.CPU_ID ), sysinfo[ MachineKeyType.CPU_ID] ) )
        value.append( ( systime, MachineKeyType.buildMachineKey( machinename, MachineKeyType.CPU_SY ), sysinfo[ MachineKeyType.CPU_SY] ) )
        value.append( ( systime, MachineKeyType.buildMachineKey( machinename, MachineKeyType.CPU_US ), sysinfo[ MachineKeyType.CPU_US] ) )
        value.append( ( systime, MachineKeyType.buildMachineKey( machinename, MachineKeyType.CPU_WA ), sysinfo[ MachineKeyType.CPU_WA] ) )
        value.append( ( systime, MachineKeyType.buildMachineKey( machinename, MachineKeyType.SWAP_IN ), sysinfo[ MachineKeyType.SWAP_IN] ) )
        value.append( ( systime, MachineKeyType.buildMachineKey( machinename, MachineKeyType.SWAP_OUT ), sysinfo[ MachineKeyType.SWAP_OUT] ) )
        value.append( ( systime, MachineKeyType.buildMachineKey( machinename, MachineKeyType.SYS_CS ), sysinfo[ MachineKeyType.SYS_CS] ) )
        value.append( ( systime, MachineKeyType.buildMachineKey( machinename, MachineKeyType.SYS_IN ), sysinfo[ MachineKeyType.SYS_IN] ) )
        value.append( ( systime, MachineKeyType.buildMachineKey( machinename, MachineKeyType.SYS_LOAD ), sysinfo[ MachineKeyType.SYS_LOAD] ) )
        value.append( ( systime, MachineKeyType.buildMachineKey( machinename, MachineKeyType.MEM_TOTAL ), sysinfo[ MachineKeyType.MEM_TOTAL] ) )
        value.append( ( systime, MachineKeyType.buildMachineKey( machinename, MachineKeyType.MEM_USED ), sysinfo[ MachineKeyType.MEM_USED] ) )
        value.append( ( systime, MachineKeyType.buildMachineKey( machinename, MachineKeyType.MEM_BUFFER ), sysinfo[ MachineKeyType.MEM_BUFFER] ) )
        value.append( ( systime, MachineKeyType.buildMachineKey( machinename, MachineKeyType.MEM_CACHE ), sysinfo[ MachineKeyType.MEM_CACHE] ) )
        # 插入数据库
        db_instance.insert( value )

        # 等待一定时间
        indeedsleeptime = time.time() - starttime + timeinterval
        if indeedsleeptime > 0:
            time.sleep( indeedsleeptime )


def parseConfig( configfile ):
    '''
    parse config file
    '''
    def getElementValue( root, xpath = None ):
        targetvalue = None
        if xpath:
            taragetelement = root.find( xpath )
            if None != taragetelement and None != taragetelement.text:
                targetvalue = taragetelement.text
            else:
                targetvalue = root.text
        return targetvalue
    if configfile and os.path.exists( configfile ) :
        configfile = os.path.abspath( configfile )
        try:
            root = xml.etree.ElementTree.parse( configfile ).getroot()
            ldap = getElementValue( root, 'db/ldap' ).strip()
            product = getElementValue( root, 'db/product' ).strip()
            return ldap, product
        except:
            pass
    return None, None


if __name__ == "__main__":
    parser = optparse.OptionParser( usage = '''python MachineMonitro.py [-option value] ''' )
    parser.add_option( "-l", "--ldap", action = "store", type = "string", dest = "ldap", default = None )
    parser.add_option( "-p", "--product", action = "store", type = "string", dest = "product", default = None )
    parser.add_option( "-t", "--type", action = "store", type = "string", dest = "type", default = None )
    parser.add_option( "-c", "--cube", action = "store", type = "string", dest = "cube", default = None )
    parser.add_option( "-i", "--timeinterval", action = "store", type = "int", dest = "timeinterval" , default = 30 )
    parser.add_option( "-m", "--mine", action = "store_true", dest = "mine" )
    parser.add_option( "-f", "--config", action = "store", type = "string", dest = "config", default = "config.xml" )
    ( options, args ) = parser.parse_args( sys.argv[1:] )
    ldap , product = parseConfig( options.config )
    if None == ldap or None == product:
        ldap = options.ldap
        product = options.product
    if options.mine:
        ldap = MachineTools.getCurrentUserName()
    startMonitorSys( ldap, product, options.type, options.cube, options.timeinterval )
