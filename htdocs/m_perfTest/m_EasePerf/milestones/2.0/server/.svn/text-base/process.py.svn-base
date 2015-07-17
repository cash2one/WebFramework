#!/usr/bin/python
# coding: utf-8
# filename: process.py
##################################
# @author yinxj, cailm, zhangb
# @date 2013-01-07

import logging
import os
import sys
import commands
import threading
import subprocess
import re
import datetime
import time
from dbOperator import DBOperator

class Process:
    def __init__( self, ldap, product, type, alias, regPattern, processPath, pids, keys, keyAlarmers, logger, cubeName, debug,timeInterval):
        self.alias = alias
        self.regPattern = regPattern
        self.processPath = processPath
        self.pids = pids
        self.oldPids = []
        self.keys=keys
        self.keyAlarmers=keyAlarmers
        self.dbop=DBOperator(ldap,product,type,cubeName,self.getDBKeys(alias,keys),[],[])
        self.logger=logger
        self.cubeName=cubeName
        self.debug=debug
        self.timeInterval=timeInterval
        # total mem of machine, unit: mb
        self.totalMem = 0
        self.curUser = None
        self.topN = 12
        # 用于指向获取进程pid的函数
        self.getAllPid = None
        # 若不是直接指定pid, 则每次都调用系统命令, 用于check进程正常 -> 优化: 先尝试用上次的pid, 找不到后再通过regPattern和processPath去更新pid
        self.currentPidCmdPath = []

    def getDBKeys(self,alias,keys):
        dbKeys=[]
        for als in alias:
            for key in keys:
                dbKeys.append(als+'.p'+key)
        return dbKeys

    def validate( self ):
        if len( self.alias ) == 0:
            self.logger.warning('@@process@@ %s: 未配置 process alias: 将不对进程做收集' % self.cubeName)
            self.dbop.close()
            return False
        if self.regPattern == '' and self.processPath == '' and len( self.pids ) == 0:
            self.logger.warning('@@process@@ %s: alias=%s: process配置不正确, 暂不做收集--每个process请至少用pid, processPath, regPattern中的一个来指定' % (self.cubeName,self.alias))
            return False
        if len( self.pids ) != 0 and len( self.pids ) != len( self.alias ):
            self.logger.warning('@@process@@ %s: alias=%s, pid=%s, process由pid指定, 但pid个数与alias个数不相同' % (self.cubeName, self.alias, self.pids ))
            self.dbop.close()
            return False
        # 若由pid指定process, 则regPattern及processPath无效
        if len( self.pids ) != 0:
            if len( self.regPattern ) != 0:
                self.logger.warning('@@process@@ %s: alias=%s, pid=%s, regPattern=%s: process由pid指定, regPattern配置无效' % ( self.cubeName, self.alias, self.pids, self.regPattern ))
                self.regPattern = ''
            if len( self.processPath ) != 0:
                self.logger.warning('@@process@@ %s: alias=%s, pid=%s, processPath=%s: process由pid指定, processPath配置无效' % (self.cubeName, self.alias, self.pids, self.processPath ))
                self.processPath = ''
        self.postInit()
        return True

    def toString(self):
        str=''
        str+='keys=%s\n' % ','.join(self.keys)
        str+='alias=%s\n' % ','.join(self.alias)
        str+='pids=%s\n' % ','.join(self.pids)
        str+='regPattern=%s\n' % self.regPattern
        str+='processPath=%s\n' % self.processPath
        str+='timeInterval=%s\n' % self.timeInterval
        str+='keyAlarmers='
        for key,alarmers in self.keyAlarmers.items():
            str+='%s:[' % key
            str+=','.join([alarmer.toString() for alarmer in alarmers])
            str+='],'
        str=str[0:-1]+'\n'
        return str

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
            self.logger.info( '@@process@@ %s: %s cannot get total mem' % (self.cubeName,self.alias ))
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

    def monitor(self):
        while True:
            currentTime = int( time.time() )
            currentTime = currentTime - currentTime % self.timeInterval
            processDatas = self.getAllProcessData()
            if len( processDatas ) > 0:
                # add timestamp
                insertData = []
                for data in processDatas:
                    insertData.append( [currentTime, data[0], data[1]] )
                self.logger.info( '@@processHandler@@ %s: result=%s' % ( self.cubeName, insertData ) )
                self.alarm(insertData)
                # insert, 与logHandler共用dbop
                if not self.debug:
                    self.dbop.insert( insertData )
                self.logger.info( '@@processHandler@@ %s: result inserted to database' % self.cubeName )
            else:
                self.logger.info( '%s: @@processHandler@@ no process data get' % self.cubeName )
            endTime = int( time.time() )
            sleepTime = self.timeInterval - ( endTime - currentTime )
            if sleepTime <= 0:
                continue
            self.logger.info( '@@processHandler@@ %s: sleep, will wake up in %ss' % ( self.cubeName, sleepTime ) )
            time.sleep( sleepTime )

    def alarm(self,result):
        # process 的数据较少, 可以这样干
        for key,alarmers in self.keyAlarmers.items():
            # 所有alias
            if key in self.keys:
                for res in result:
                    if key in res[1]:
                        for alarmer in alarmers:
                            alarmer.alarm(res[2],self.cubeName,key)
            # 仅对某alias
            else:
                # zhangb的前台遗留问题, pid相关的key形式为: alias+'.p'+key
                for res in result:
                    if key.replace(key[key.rfind('.')],'.p')==res[1]:
                        for alarmer in alarmers:
                            alarmer.alarm(res[2],self.cubeName,key)

    def getAllProcessData( self ):
        if len(self.pids)!=0:
            pids=self.getAllPid()
        elif self.validOldPids():
            pids = self.oldPids
        elif len( self.pids ) == 0:
            self.refreshPidInfo()
            pids = self.getAllPid()
        if len( pids ) > len( self.alias ):
            self.logger.info( '@@process@@ %s: alias=%s, 配置的进程数(%s) < 检测到的个数(%s), 暂不对进程做收集' % ( self.cubeName,self.alias, len( self.alias ), len( pids ) ) )
            return []
        if len( pids ) < len( self.alias ):
            self.logger.warning( '@@process@@ %s: alias=%s 检测到的进程数(%s) < 配置的个数(%s)' % ( self.cubeName, self.alias, len( pids ), len( self.alias ) ) )
        if len(pids)==0:
            self.logger.warning( '@@process@@ %s: alias=%s 检测到的进程数为0, 暂不对进程做收集' % ( self.cubeName, self.alias ) )
            return []
        if len( self.oldPids )==len( pids ):
            for i in range(len(pids)):
                if pids[i]!=self.oldPids[i]:
                    self.logger.info( '@@process@@ %s: pid changed for alias=%s: %s -> %s' % (self.cubeName,self.alias[i], self.oldPids[i], pids[i]))
        self.oldPids = pids
        # 获取配置进程数据
        memAndCpuData = self.getMemAndCpuData( pids )
        fdsData=self.getFdsData(pids)
        netLinksData=self.getNetLinksData(pids)
        threadsData=self.getThreadsData(pids)
        return memAndCpuData+fdsData+netLinksData+threadsData

    def validOldPids(self):
        '''
            是否所有进程都在
        '''
        if len(self.oldPids)==0:
            self.logger.debug( '@@process@@ %s: alias=%s, valid=False' % ( self.cubeName,self.alias ) )
            return False
        for pid in self.oldPids:
            # check该进程是否存在
            try:
                os.kill(int(pid),0)
            except OSError:
                self.logger.debug( '@@process@@ %s: alias=%s, pid=%s, valid=False' % ( self.cubeName,pid,self.alias ) )
                return False
        self.logger.debug( '@@process@@ %s: alias=%s, valid=True' % ( self.cubeName,self.alias ) )
        return True

    def refreshPidInfo( self ):
        self.currentPidCmdPath = []
        # 获取当前user的进程pid, cmd, path的shell命令 (返回格式执行可见):
        # ps x --columns=100000 | sed -r 's/^\s*(.*)/\1/' | grep -P "^\s*\d" | grep -v -P "\d+:\d+\s+(ps|grep|sed|ssh|-bash|ls|sh)" | sed -r 's/^([0-9]*).*[0-9]+:[0-9]+\s+(.*)/echo -ne "\1\\\\t\2\\\\t";ls -l \/proc\/\1\/cwd/' | grep -v columns=100000 | sh | grep -P "^\d" | sed -r "s/([0-9]*)\t(.*)\t.*-> (.*)/\1\t\2\t\3/"
        # 1. ps -x (可改为用ps -xo) 注:ps 命令会有截断, 设为10w长可对付一般情况, os默认为page length, 一般为4096, 可能取不到完整的commands
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
        self.logger.info( '@@process@@ %s: alias=%s, update user\'s process info successfully, pidCmdPath.info=%s' % ( self.cubeName,self.alias, self.currentPidCmdPath ) )

    def getPid( self ):
        return self.pids

    def getPidByReg( self ):
        pids = []
        for pid, cmd, path in self.currentPidCmdPath:
            if len( re.findall( self.regPattern, cmd ) ) != 0:
                pids.append( pid )
        self.logger.info( '@@process@@ %s: ailas=%s, getPidByRegPattern=%s' % (self.cubeName, self.alias, pids ) )
        return pids

    def getPidByPath( self ):
        pids = []
        for pid, cmd, path in self.currentPidCmdPath:
            if path == '/' and self.processPath != '/':
                continue
            if self.processPath.strip( '/' ) in path.strip( '/' ):
                pids.append( pid )
        self.logger.info( '@@process@@ %s: ailas=%s, getPidByPath=%s' % ( self.cubeName,self.alias, pids ) )
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
        self.logger.debug('@@process@@ %s: cmd=%s' % (self.cubeName,cmd))
        results = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE ).communicate()[0].strip().splitlines()
        for result in results:
            lineinfos = result.split()
            # 正常情况下每行应只有\s\d\.
            # 若有进程已被kill掉, len(lineinfos)可能不为3
            if result.upper() != result.lower() or len( lineinfos ) != 3:
                self.logger.warning( '@@process@@ %s: alias=%s: 获取到异常进程信息: result=%s' % (self.cubeName, self.alias, result ) )
                break
            memAndCpuData.append( [self.alias[currentPids.index( lineinfos[0] )] + '.pcpu', float( lineinfos[1] )] )
            memAndCpuData.append( [self.alias[currentPids.index( lineinfos[0] )] + '.pmem', float( lineinfos[2] ) * self.totalMem / 100] )
        self.logger.info( '@@process@@ %s: alias=%s, memAndCpuData=%s' % (self.cubeName, self.alias, memAndCpuData ) )
        return memAndCpuData

    def getFdsData(self,pids):
        fdsData=[]
        if 'fds' not in self.keys:
            return fdsData
        for pid in pids:
            cmd='ls -l /proc/%s/fd | wc -l' % pid
            self.logger.debug('@@process@@ %s: cmd=%s' % (self.cubeName,cmd))
            res=subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE, stderr = subprocess.PIPE ).communicate()[0].splitlines()[0].split()
            if len(res)==0 or not res[0].isdigit():
                continue
            else:
                fdsData.append([self.alias[pids.index(pid)]+'.pfds',int(res[0])])
        return fdsData

    def getNetLinksData(self,pids):
        netLinksData=[]
        if 'netLinks' not in self.keys:
            return netLinksData
        for pid in pids:
            cmd='netstat -tnp | grep %s | grep ESTABLISHED | wc -l' % pid
            self.logger.debug('@@process@@ %s: cmd=%s' % (self.cubeName,cmd))
            res=subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE, stderr = subprocess.PIPE ).communicate()[0].splitlines()[0].split()
            if len(res)==0 or not res[0].isdigit():
                continue
            else:
                netLinksData.append([self.alias[pids.index(pid)]+'.pnetLinks',int(res[0])])
        return netLinksData

    def getThreadsData(self,pids):
        threadsData=[]
        if 'threads' not in self.keys:
            return threadsData
        for pid in pids:
            cmd='pstree -p %s | wc -l' % pid
            self.logger.debug('@@process@@ %s: cmd=%s' % (self.cubeName,cmd))
            res=subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE, stderr = subprocess.PIPE ).communicate()[0].splitlines()[0].split()
            if len(res)==0 or not res[0].isdigit():
                continue
            else:
                threadsData.append([self.alias[pids.index(pid)]+'.pthreads',int(res[0])])
        return threadsData

    def getOtherUserMemCpu( self ):
        '''
        获取其他用户的cpu和内存信息
        '''
        otheruserinfos = []
        if not self.curUser:
            return otheruserinfos
        cmd = "top -n1 b | grep -e \"^ *[0-9]\+\" | grep -v %s" % ( self.curUser ) + "  | awk '{printf(\"%s %s %s\\n\",$2,$9,$10);}' "
        self.logger.debug('@@process@@ %s: cmd=%s' % (self.cubeName,cmd))
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
        self.logger.info( '@@process@@ %s: otheruser processinfo , memAndCpuData=%s' % (self.cubeName, otheruserinfos ) )
        return otheruserinfos


