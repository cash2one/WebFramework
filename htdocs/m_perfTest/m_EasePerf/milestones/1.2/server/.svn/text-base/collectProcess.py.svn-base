#!/usr/bin/python
# coding: utf-8
# filename: collectProcess.py
##################################
# @author yinxj, cailm, zhangb
# @date 2013-01-07

import logging, os, sys, commands, threading, subprocess, re
import datetime, time

class Process:
    def __init__( self, logger ):
        self.logger=logger
        self.alias = []
        self.regPattern = ''
        self.processPath = ''
        self.pids = []
        self.oldPids = []
        # total mem of machine, unit: mb
        self.totalMem = 0
        self.curUser = None
        self.topN = 12
        # 用于指向获取进程pid的函数
        self.getAllPid = None
        # 若非pid指定进程信息, 则每次都调用系统命令, 用于check进程的正常状态
        self.currentPidCmdPath = []

    def configCheck( self ):
        if len( self.alias ) == 0:
            self.logger.warning('@@process@@ 未配置 process alias: 将不对进程做监控')
        if self.regPattern == '' and self.processPath == '' and len( self.pids ) == 0:
            self.logger.error('@@process@@ alias=%s: 每个process请至少用pid, processPath, regPattern中的一个来指定' % self.alias)
            exit( -1 )
        if len( self.pids ) != 0 and len( self.pids ) != len( self.alias ):
            self.logger.error('@@process@@ alias=%s, pid=%s: process由pid指定, 但pid个数与alias个数不相同' % ( self.alias, self.pids ))
            exit( -1 )
        # 若由pid指定process, 则regPattern及processPath无效
        if len( self.pids ) != 0:
            if len( self.regPattern ) != 0:
                self.logger.warning('@@process@@ alias=%s, pid=%s, regPattern=%s: process由pid指定, regPattern配置无效' % ( self.alias, self.pids, self.regPattern ))
                self.regPattern = ''
            if len( self.processPath ) != 0:
                self.logger.warning('@@process@@ alias=%s, pid=%s, processPath=%s: process由pid指定, processPath配置无效' % ( self.alias, self.pids, self.processPath ))
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
        # 后面两种应该没啥必要, 不过有备无患:)
        elif unit == 'mb':
            pass
        elif unit == 'gb':
            self.totalMem = self.totalMem * 1024
        else:
            self.logger.info( '@@process@@ %s: cannot get total mem' % self.alias )
            exit( -1 )
        # 获取当前用户名
        self.curUser = commands.getoutput('whoami').strip()

        # 指定获取进程的方法
        if len( self.pids ) != 0:
            self.getAllPid = self.getPid
        elif self.regPattern != '':
            if self.processPath != '':
                self.getAllPid = self.getPidByRegAndPath
            else:
                self.getAllPid = self.getPidByReg
        else:
            self.getAllPid = self.getPidByPath

    def getAllProcessData( self ):
        if len( self.pids ) == 0:
            self.refreshPidInfo()
        allprocessdata = []
        # 获取其他用户进行信息
        otheruserprocessinfo = self.getOtherUserMemCpu()
        allprocessdata.extend( otheruserprocessinfo )
        pids = self.getAllPid()
        if len( pids ) > len( self.alias ):
            self.logger.info( '@@process@@ alias=%s: 配置的进程数(%s) < 检测到的个数(%s), 暂不对进程做监控' % ( self.alias, len( self.alias ), len( pids ) ) )
            return allprocessdata
        if len( pids ) < len( self.alias ):
            self.logger.warning( '@@process@@ alias=%s: 检测到的进程数(%s) < 配置的个数(%s)' % ( self.alias, len( pids ), len( self.alias ) ) )
        if len( self.oldPids )==len( pids ):
            for i in range(len(pids)):
                if pids[i]!=self.oldPids[i]:
                    self.logger.info( '@@process@@ pid changed for alias=%s: %s -> %s' % (self.alias[i], self.oldPids[i], pids[i]))
        self.oldPids = pids
        # 获取配置进程信息
        userprocessinfo = self.getMemAndCpuData( pids )
        allprocessdata.extend( userprocessinfo )
        return allprocessdata

    def refreshPidInfo( self ):
        self.currentPidCmdPath = []
        # 获取当前user的进程pid, cmd, path的shell命令 (返回格式执行可见):
        # ps x --columns=100000 | sed -r 's/^\s*(.*)/\1/' | grep -P "^\s*\d" | grep -v -P "\d+:\d+\s+(ps|grep|sed|ssh|-bash|ls|sh)" | sed -r 's/^([0-9]*).*[0-9]+:[0-9]+\s+(.*)/echo -ne "\1\\\\t\2\\\\t";ls -l \/proc\/\1\/cwd/' | grep -v columns=100000 | sh | grep -P "^\d" | sed -r "s/([0-9]*)\t(.*)\t.*-> (.*)/\1\t\2\t\3/"
        # 1. ps -x (可改为用ps -xo) 注:ps 命令会有截断, 设为10w长应该可以对付一般情况, 默认为page length, 一般为4096... 可能取不到完整的commands
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
        self.logger.info( '@@process@@ alias=%s: update user\'s process info successfully, pidCmdPath.info=%s' % ( self.alias, self.currentPidCmdPath ) )

    def getPid( self ):
        return self.pids

    def getPidByReg( self ):
        pids = []
        for pid, cmd, path in self.currentPidCmdPath:
            if len( re.findall( self.regPattern, cmd ) ) != 0:
                pids.append( pid )
        self.logger.info( '@@process@@ ailas=%s: getPidByRegPattern=%s' % ( self.alias, pids ) )
        return pids

    def getPidByPath( self ):
        pids = []
        for pid, cmd, path in self.currentPidCmdPath:
            if path == '/' and self.processPath != '/':
                continue
            if self.processPath.strip( '/' ) in path.strip( '/' ):
                pids.append( pid )
        self.logger.info( '@@process@@ ailas=%s: getPidByPath=%s' % ( self.alias, pids ) )
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
        self.logger.info( '@@process@@ alias=%s, cmd=%s, result=%s' % ( self.alias, cmd, results ) )
        for result in results:
            lineinfos = result.split()
            # 正常情况下每行应只有\s\d\.
            # 若有进程已被kill掉, len(lineinfos)可能不为3
            if result.upper() != result.lower() or len( lineinfos ) != 3:
                self.logger.warning( '@@process@@ alias=%s: 获取到异常进程信息: result=%s' % ( self.alias, result ) )
                break
            memAndCpuData.append( [self.alias[currentPids.index( lineinfos[0] )] + '.pcpu', float( lineinfos[1] )] )
            memAndCpuData.append( [self.alias[currentPids.index( lineinfos[0] )] + '.pmem', float( lineinfos[2] ) * self.totalMem / 100] )
        self.logger.info( '@@process@@ alias=%s, memAndCpuData=%s' % ( self.alias, memAndCpuData ) )
        return memAndCpuData

    def getOtherUserMemCpu( self ):
        '''
        获取其他用户的cpu和内存信息
        '''
        otheruserinfos = []
        if not self.curUser:
            return otheruserinfos
        cmd = "top -n1 b | grep -e \"^ *[0-9]\+\" | grep -v %s" % ( self.curUser ) + "  | awk '{printf(\"%s %s %s\\n\",$2,$9,$10);}' "
        results = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE ).communicate()[0].strip().splitlines()
        otheruserinfo = {}
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
                if not otheruserinfo.has_key( username ):
                    otheruserinfo[username] = {'user_%s.pall' % ( username ): cpurate + memrate , 'user_%s.pcpu' % ( username ): cpurate, 'user_%s.pmem' % ( username ): memrate * self.totalMem / 100}
                else:
                    otheruserinfo[username]['user_%s.pall' % ( username )] += ( cpurate + memrate )
                    otheruserinfo[username]['user_%s.pcpu' % ( username ) ] += cpurate
                    otheruserinfo[username]['user_%s.pmem' % ( username ) ] += ( memrate * self.totalMem / 100 )
            except:
                pass
        # 排序
        otheruserinfolist = sorted( otheruserinfo.iteritems(), key = lambda ( username, uservalue ): uservalue['user_%s.pall' % ( username )], reverse = True )
        otheruserinfolist = otheruserinfolist[:self.topN]
        for username, uservalue in otheruserinfolist:
            otheruserinfos.append( [ 'user_%s.pcpu' % ( username ) , uservalue['user_%s.pcpu' % ( username )] ] )
            otheruserinfos.append( [ 'user_%s.pmem' % ( username ) , uservalue['user_%s.pmem' % ( username )] ] )
        self.logger.info( '@@process@@ otheruser processinfo , memAndCpuData=%s' % ( otheruserinfos ) )
        return otheruserinfos


