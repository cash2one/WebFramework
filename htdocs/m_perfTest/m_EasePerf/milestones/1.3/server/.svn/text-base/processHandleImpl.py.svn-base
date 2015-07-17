# encoding=utf-8
'''
性能测试工具收集

Created on 2013-4-8

@author: 张波
'''

import subprocess, re
import time
import perfUtils
from handleInterface import handleInterface

class ProcessInfoHandle( handleInterface ):
    '''
    处理进程信息
    '''
    MKEY_OPENFILE_NUMBER = 'fd'
    MKEY_THREAD_NUMBER = 'threadNumber'
    MKEY_PROCESS_GCINFO = 'gcinfo'
    def __init__( self, alias, regexpattern, processpath , pids, mitems, mitemfrequence, logger = None ):

        # 1，配置属性
        self.alias = alias  # 进程别名:类型为list
        self.regexPattern = regexpattern  # 正则匹配式
        self.processPath = processpath  # 进程目录
        self.pids = pids  # 进程ids：类型list
        self.mItems = mitems  # 进程监控子项
        self.mItemFrequence = mitemfrequence  # 进程监控子项执行频率
        self.logger = logger  # 日志处理


        # 2，内部状态
        self.__getPidsHandle = None  # 当前获取配置进程id函数

        # 进程映射信息
        self.__lastPidsMap = None  # 原来进程映射信息 alianame ->pid
        self.__lastPidsMapRef = None  # 进程映射信息 pid->aliasname

        self.__totalMem = 1  # 机器总内存大小
        self.__currentPidCmdPath = None  # 当前系统进程信息
        self.__currentPidCpuMem = None  # 当前系统进程信息

        self.__isInited = False  # 是否初始化

        self.__mItemHandles = []  # 进程子项监控处理函数列表
        self.__mItemHit = 0  # 进程子项监控处理标识

        self.__processMonitorItems = ["cpu", "mem"]
        self.__processMonitorGCItems = ["ygc", "fgc", "ygct", "fgct", "gct"]

        try:
            self.__devnull = open( '/dev/null', 'w' )  # 将结果不显示
        except:
            self.__devnull = None

############################################################
# 实现 handleInterface接口:initHandle,isValid,generateKeys
############################################################
    def isValid( self ):
        '''
        配置检查
        '''
        if not self.alias:
            if self.logger: self.logger.error( '@@process@@ 未配置 process alias: 将不对进程做监控' )
            return False

        if not self.regexPattern and not self.processPath and not self.pids:
            if self.logger: self.logger.error( '@@process@@ alias=%s: 每个process请至少用pid, processPath, regPattern中的一个来指定' % self.alias )
            return False

        if self.pids and len( self.pids ) != len( self.alias ):
            if self.logger: self.logger.error( '@@process@@ alias=%s, pid=%s: process由pid指定, 但pid个数与alias个数不相同' % ( self.alias, self.pids ) )
            return False
        return True

    def initHandle( self ):
        '''
        初始化函数
        '''
        if not self.__isInited:
            # 读取机器内存
            self.__totalMem = perfUtils.getMachineMemory()

            # 设置pid读取函数
            if self.pids:
                self.__getPidsHandle = self.__getConfigPids()
            else:
                if self.regexPattern:
                    if self.processPath:
                        self.__getPidsHandle = self.__getPidByRegAndPath
                    else:
                        self.__getPidsHandle = self.__getPidByReg
                else:
                    self.__getPidsHandle = self.__getPidByPath

            # 设置子项监控收集器:__getProcessCpuMemInfo这个频率和其他的不一样，所以单独处理
            if ProcessInfoHandle.MKEY_OPENFILE_NUMBER in self.mItems: self.__mItemHandles.append( self.__getProcessFdInfo )
            if ProcessInfoHandle.MKEY_THREAD_NUMBER in self.mItems: self.__mItemHandles.append( self.__getProcessThreadNumberInfo )
            if ProcessInfoHandle.MKEY_PROCESS_GCINFO in self.mItems: self.__mItemHandles.append( self.__getProcessGCInfo )

        self.__isInited = True

    def generateKeys( self ):
        '''
        generate keys
        '''
        retkeys = []
        for aliasname in self.alias:
            for item in self.__processMonitorItems:
                retkeys.append( aliasname + '.p' + item )
            if ProcessInfoHandle.MKEY_OPENFILE_NUMBER in self.mItems:
                retkeys.append( aliasname + '.p' + ProcessInfoHandle.MKEY_OPENFILE_NUMBER )
            if ProcessInfoHandle.MKEY_THREAD_NUMBER in self.mItems:
                retkeys.append( aliasname + '.p' + ProcessInfoHandle.MKEY_THREAD_NUMBER )
            if ProcessInfoHandle.MKEY_PROCESS_GCINFO in self.mItems:
                for item in self.__processMonitorGCItems:
                    retkeys.append( aliasname + ".p" + item )
        return retkeys

    def getAlias( self ):
        '''
        获取别名
        '''
        return self.alias

    def getDatas( self, pidcmdpathinfos = None, pidcpumeminfos = None ):
        '''
        获取进程信息
        '''

        datas = []
        # 检查是否有效
        if not self.isValid(): return datas

        # 获取进程，pid，cmd，path信息
        if None == pidcmdpathinfos: pidcmdpathinfos = perfUtils.getPidCmdPathInfos()
        # 获取进程 pid，user，%cpu，%mem信息
        if None == pidcpumeminfos: pidcpumeminfos = perfUtils.getPidCpuMemInfos()

        self.__currentPidCmdPath = pidcmdpathinfos
        self.__currentPidCpuMem = pidcpumeminfos

        # 获取目标pids
        curpids = self.__getPidsHandle()
        # 更新进程映射信息
        ret = self.__updatePidMapping( curpids )
        if ret:
            # 获取进程具体信息
            datas = self.__getProcssInfo( curpids )
        return datas

############################################################
# 私有函数
############################################################
    def __updatePidMapping( self, curpids ):
        '''
        更新进程映射信息
        '''
        if len( curpids ) != len( self.alias ):
            if self.logger: self.logger.error( '@@process@@ alias=%s: 配置的进程数与检测到的个数不同, 配置的个数为%s, 当前监控到的个数为%s, 暂不对进程做监控' % ( self.alias, len( self.alias ), len( curpids ) ) )
            return False
        if None == self.__lastPidsMap or None == self.__lastPidsMapRef:
            self.__lastPidsMap = {}
            self.__lastPidsMapRef = {}
            for index, aliasname in enumerate( self.alias ):
                self.__lastPidsMap[aliasname] = curpids[index]
                self.__lastPidsMapRef[ curpids[index] ] = aliasname
            if self.logger: self.logger.info( '@@process@@ pidmaping: %s' % ( self.__lastPidsMap ) )
        else:
            oldpids = self.__lastPidsMap.values()
            changedpids = []
            for curpid in curpids:
                if curpid not in oldpids:
                    changedpids.append( curpid )
            # 更新mapping信息
            if changedpids:
                changeindex = 0
                for aliasname, oldpid in self.__lastPidsMap.items():
                    if oldpid not in curpids:
                        newpid = changedpids[changeindex]
                        changeindex += 1
                        self.__lastPidsMap[aliasname] = newpid

                        del self.__lastPidsMapRef[oldpid]
                        self.__lastPidsMapRef[newpid] = aliasname
                        if self.logger: self.logger.warning( '@@process@@ pid changed for alias=%s: %s -> %s' % ( aliasname, oldpid, newpid ) )
        return True



    def __getConfigPids( self ):
        return self.pids

    def __getPidByReg( self ):
        pids = []
        for pid, cmd, path in self.__currentPidCmdPath:
            if len( re.findall( self.regexPattern, cmd ) ) != 0:
                pids.append( pid )
        if self.logger: self.logger.info( '@@process@@ ailas=%s: getPidByRegPattern=%s' % ( self.alias, pids ) )
        return pids

    def __getPidByPath( self ):
        pids = []
        for pid, cmd, path in self.__currentPidCmdPath:
            if path == '/' and self.processPath != '/':
                continue
            if self.processPath.strip( '/' ) in path.strip( '/' ):
                pids.append( pid )
        if self.logger: self.logger.info( '@@process@@ ailas=%s: __getPidByPath=%s' % ( self.alias, pids ) )
        return pids

    def __getPidByRegAndPath( self ):
        regPid = self.__getPidByReg()
        pathPid = self.__getPidByPath()
        pids = []
        for pid in regPid:
            if pid in pathPid:
                pids.append( pid )
        return pids

    def __getProcessCpuMemInfo( self, curpid, processname ):
        '''
        收集进行进程cpu和内存信息：
        '''
        pcpumeminfo = []
        try:
            pinfo = self.__currentPidCpuMem[curpid]
            # PID USER %CPU %MEM
            cpurate = float( pinfo[2] )
            memrate = float( pinfo[3] )
            pcpumeminfo.append( ( processname + '.pcpu', cpurate ) )
            pcpumeminfo.append( ( processname + '.pmem', memrate * self.__totalMem / 100 ) )
        except:
            pcpumeminfo = []
        return pcpumeminfo

    def __getProcessGCInfo( self, curpid, processname ):
        '''
        收集进行gc信息：目前只收集jstat 来收集gc信息
        '''
        pgcinfos = []
        cmd = "jstat -gcutil %s | tail  -n 1" % ( curpid )
        result = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE, stderr = self.__devnull ).communicate()[0]
        results = result.strip( "\r" ).strip( "\n" ).strip().split()
        # S0     S1     E      O      P     YGC     YGCT    FGC    FGCT     GCT
        if 10 == len( results ):
            pgcinfos.append( ( processname + '.pygc', float( results[5] ) ) )
            pgcinfos.append( ( processname + '.pypct', float( results[6] ) ) )
            pgcinfos.append( ( processname + '.pfgc', float( results[7] ) ) )
            pgcinfos.append( ( processname + '.pfgct', float( results[8] ) ) )
            pgcinfos.append( ( processname + '.pgct', float( results[9] ) ) )
        return pgcinfos

    def __getProcessFdInfo( self, curpid, processname ):
        '''
        收集进行gc信息：目前只收集jstat 来收集gc信息
        '''
        fdinfo = []
        cmd = " ls /proc/%s/fd -1 | wc -l" % ( curpid )
        result = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE, stderr = self.__devnull ).communicate()[0]
        result = result.strip( "\r" ).strip( "\n" ).strip()
        fdinfo.append( ( processname + '.p' + ProcessInfoHandle.MKEY_OPENFILE_NUMBER, float( result ) ) )
        return fdinfo

    def __getProcessThreadNumberInfo( self, curpid, processname ):
        '''
        收集进行gc信息：目前只收集jstat 来收集gc信息
        '''
        threadnumberinfo = []
        cmd = "ps -Lf %s | wc -l" % ( curpid )
        result = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE, stderr = self.__devnull ).communicate()[0]
        result = result.strip( "\r" ).strip( "\n" ).strip()
        threadnumberinfo.append( ( processname + '.p' + ProcessInfoHandle.MKEY_THREAD_NUMBER, float( result ) ) )
        return threadnumberinfo

    def __getProcssInfo( self, curpids ):
        '''
        获取进程信息
        '''
        processinfos = []
        try:
            for curpid in curpids:
                pidname = self.__lastPidsMapRef[curpid]
                # 获取内存和cpu
                processinfos.extend( self.__getProcessCpuMemInfo( curpid, pidname ) )
                # 处理子项
                if 0 == self.__mItemHit:
                    for mitemhandle in self.__mItemHandles:
                        processinfos.extend( mitemhandle( curpid, pidname ) )
                # 更新mitem监控频率
                self.__mItemHit = ( self.__mItemHit + 1 ) % self.mItemFrequence
        except:
            processinfos = []
        if self.logger: self.logger.info( '@@process@@ alias=%s, processinfos=%s' % ( self.alias, processinfos ) )
        return processinfos

class UserInfoHandle( handleInterface ):
    '''
    处理用户信息
    '''
    def __init__( self , logger = None ):
        self.logger = logger  # 日志处理

        # 内部数据
        self.__curUser = None  # 当前用户
        self.__totalMem = 1  # 机器总内存大小
        self.__userMonitorItems = ["cpu", "mem"]
        self.__topN = 8

        self.__isInited = False

############################################################
# 实现 handleInterface接口:initHandle,isValid,generateKeys,getAlias
############################################################
    def isValid( self ):
        '''
        配置检查
        '''
        return True

    def initHandle( self ):
        '''
        初始化
        '''
        if not self.__isInited:
            # 获取当前用户名
            self.__curUser = perfUtils.getCurUserName()
            # 读取机器内存
            self.__totalMem = perfUtils.getMachineMemory()

    def generateKeys( self ):
        '''
        generate keys
        '''
        retkeys = []
        for item in self.__userMonitorItems:
            retkeys.append( "pinfo_otherusers" + ".p" + item )
        return retkeys

    def getAlias( self ):
        '''
        获取别名
        '''
        return 'otheruser'

    def getDatas( self, pidcmdpathinfos = None, pidcpumeminfos = None ):
        '''
        获取监控数据
        '''

        datas = []
        # 检查是否有效
        if not self.isValid(): return datas

        # 获取进程，pid，cmd，path信息
        # if None == pidcmdpathinfos: pidcmdpathinfos = perfUtils.getPidCmdPathInfos()
        # 获取进程 pid，user，%cpu，%mem信息
        if None == pidcpumeminfos: pidcpumeminfos = perfUtils.getPidCpuMemInfos()

        userinfos = {}
        for pid, pinfo in pidcpumeminfos.iteritems():
            # PID USER %CPU %MEM
            try:
                username = str( pinfo[1] ).strip()
                if username == self.__curUser: continue
                cpurate = float( pinfo[2] )
                memrate = float( pinfo[3] )
                if not userinfos.has_key( username ):
                    userinfos[username] = {'pinfo_user_%s.pall' % ( username ): cpurate + memrate , 'pinfo_user_%s.pcpu' % ( username ): cpurate, 'pinfo_user_%s.pmem' % ( username ): memrate * self.__totalMem / 100 }
                else:
                    userinfos[username]['pinfo_user_%s.pall' % ( username )] += ( cpurate + memrate )
                    userinfos[username]['pinfo_user_%s.pcpu' % ( username ) ] += cpurate
                    userinfos[username]['pinfo_user_%s.pmem' % ( username ) ] += memrate * self.__totalMem / 100
            except:
                pass
        # 排序
        userinfoslist = sorted( userinfos.iteritems(), key = lambda ( username, uservalue ): uservalue['pinfo_user_%s.pall' % ( username )], reverse = True )
        userinfoslist = userinfoslist[:self.__topN]
        for username, uservalue in userinfoslist:
            datas.append( [ 'pinfo_user_%s.pcpu' % ( username ) , uservalue['pinfo_user_%s.pcpu' % ( username )] ] )
            datas.append( [ 'pinfo_user_%s.pmem' % ( username ) , uservalue['pinfo_user_%s.pmem' % ( username )] ] )
        return datas


class ProcessHandle( handleInterface ):
    '''
    快速进程处理
    '''
    def __init__( self , timeinterval, logger = None, dbop = None ):
        self.__timeInterval = timeinterval
        self.targetHandles = []
        self.logger = logger
        self.__dbop = dbop

############################################################
# 实现 handleInterface接口
# 实现接口：initHandle, isValid, startMonitor
############################################################
    def isValid( self ):
        '''
        配置检查
        '''
        return len( self.targetHandles ) > 0

    def initHandle( self ):
        '''
        初始化函数
        '''
        for processhandle in self.targetHandles:
            processhandle.initHandle()

    def generateKeys( self ):
        '''
        生成监控 keys
        '''
        retkeys = []
        for targethandle in self.targetHandles:
            retkeys.extend( targethandle.generateKeys() )
        return retkeys

    def startMonitor( self ):
        '''
        监控进程入口函数
        '''

        if not self.isValid():
            return
        # 初始化
        self.initHandle()

        lastsystime = None
        queryintervaltime = self.__timeInterval / 3 + 1
        while True:
            # 获取当前时间
            curtime = int( time.time() )
            systime = curtime - curtime % self.__timeInterval

            # 等待一定时间，轮询间隔为 timeInterval的 1/3,避免
            # 程序调度本身需要一定时间，导致数据没有插入，有其是在负载高的时候)
            if lastsystime == systime:
                time.sleep( queryintervaltime )
                continue
            lastsystime = systime

            # 获取进程信息
            pidcmdpathinfos = perfUtils.getPidCmdPathInfos()
            pidcpumeminfos = perfUtils.getPidCpuMemInfos()
            for targethandle in self.targetHandles:
                # 获取进程信息
                processdatas = targethandle.getDatas( pidcmdpathinfos, pidcpumeminfos )
                # 添加timestamp信息
                if processdatas:
                    insertData = []
                    for data in processdatas:
                        insertData.append( ( systime, data[0], data[1] ) )
                    if self.logger: self.logger.info( '@@processHandler@@ alias: %s\tresult=%s' % ( targethandle.getAlias(), insertData ) )
                    # 将结果插入到数据库中
                    if self.__dbop:
                        self.__dbop.insert( insertData )
                        if self.logger: self.logger.info( '@@processHandler@@ alias: %s result inserted to database' % targethandle.getAlias() )
                else:
                    if self.logger: self.logger.info( '@@processHandler@@ alias: %s no process data get' % targethandle.getAlias() )
                if self.logger: self.logger.info( '@@processHandler@@ alias: %s sleep, will wake up in %ss' % ( targethandle.getAlias(), self.__timeInterval ) )

############################################################
# 自有函数
############################################################
    def addTargetHandle( self, targethandle ):
        '''
        添加需要处理handle
        '''
        self.targetHandles.append( targethandle )
