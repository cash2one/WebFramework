# encoding=utf-8
'''
性能测试工具收集
文件处理模块：
    按行处理，可以处理带时间戳，也可以不带时间戳的
    1,文件读取多行实现和yxj讨论结果
    2,关于daytimestamp及正则参考yxj

Created on 2013-4-7

@author: 张波
'''


import os
import datetime, time
import re
from handleInterface import handleInterface

class fileHandleImpl( handleInterface ):
    '''
   文件处理实现
    '''

    def __init__( self, logfile , nickname, timeinterval, logger , dbop , starthead = False, startdate = None, notimestamp = False ):
        '''
        Constructor
        '''

        self.timeInterval = timeinterval  # 时间间隔
        self.logger = logger  # 处理日志器
        self.__dbop = dbop

        # 1,log文件配置与读取
        self.__nickName = nickname  # 文件别名
        self.__logFile = str( logfile ).strip()  # 日志文件
        self.__isStartHead = starthead  # 是否从文件起始位置读取，如果为False则从文件末尾还是读取
        # 文件内部读取状态
        self.__targetFile = ''  # 真实日志文件
        self.__readPosition = 0  # 当前文件读取位置
        self.__lastFileSize = 0
        self.__defaultReadSize = 16 * 1024 * 1024  # 文件读取大小

        # 2,时间戳配置部分
        # 为什么date 和day的时间戳分开，而没有采用一次正则匹配，time.mktime( time.strptime(..；是基于效率的考虑
        # 进过测试，一次和分开的效率方面差距7倍以上，所以不推荐；
        # 最佳的方式是：只读取day的time，批量读取date的，内部维护时间戳增长
        # 初始日期时间戳
        self.startDayTimestamp = startdate  # log开始的日期timestamp
        self.__noTimestamp = notimestamp  # 明确表明没有时间信息：如果该值为True则使用系统时间（在没有startday使用系统时间，如果有则只是用daytimestamp）
        # 时间戳正则匹配：内部维护状态
        self.__datePattern = None  # 日期匹配正则(返回月和日)
        self.__datePatterns = ['[^\d]*\d{4}-(\d{2})-(\d{2}).\d{2}:\d{2}:\d{2}[^\d].*',
                               '[^\d]*\d{2}(\d{2})(\d{2}) \d{2}\d{2}\d{2}[^\d].*',
                               '[^\d]*(\d{2})(\d{2})\d{2}\d{2}\d{2}[^\d].*']
        self.__timePattern = None  # 时间戳匹配正则(返回 时，分，秒)
        self.__timePatterns = ['[^\d]*(\d{2}):(\d{2}):(\d{2})\.\d{3}[^\d].*',
                               '[^\d]*\d{6} (\d{2})(\d{2})(\d{2})[^\d].*',
                               '[^\d]*\d{4}-\d{2}-\d{2}.(\d{2}):(\d{2}):(\d{2})[^\d].*',
                               '[^\d]*\d{4}(\d{2})(\d{2})(\d{2})[^\d].*']
        # 时间戳内部维护状态
        self.__lastMaxTimestamp = 0  # 记录最大的timestamp，主要是跨天会使用到
        self.__defaultDayTimestamp = None  # 记录缺省的天timestamp，只有在使用默认时间的时候使用
        self.__threshold = 3600  # day grow when find timestamp plus
        self.__timestampInitFlag = False  # 时间戳是否初始化标识
        self.__timestampInitTryCount = 6  # 时间戳最大尝试次数，如果超过该次数，则默认使用系统timestamp


############################################################
# 实现 handleInterface接口
# 实现接口：initHandle, isValid, startMonitor, getAlias
# 未实现接口：generateKeys
############################################################
    def initHandle( self ):
        '''
        初始化
        '''
        # 更新nickname，如果为空则使用logfile
        if not self.__nickName:
            self.__nickName = os.path.splitext( os.path.basename( os.path.basename( self.__logFile ) ) )

        # 更新startDayTimestamp
        if None != self.startDayTimestamp:
            startdate = str( self.startDayTimestamp )
            try:
                if startdate.isdigit():
                    startdate = int( startdate )
                    self.startDayTimestamp = int( time.mktime( datetime.date.today().timetuple() ) ) - startdate * 24 * 3600
                else:
                    startdate = datetime.datetime.strptime( startdate , "%Y-%m-%d" )
                    self.startDayTimestamp = int( time.mktime( startdate.timetuple() ) )
            except:
                self.startDayTimestamp = None

        # 更新文件名称
        self.updateFile()

        # 若文件存在,且不需要分析整个log，则指向末尾, 否则指向开头并等待文件生成
        if os.path.exists( self.__targetFile ) and not self.__isStartHead:
            filehandle = open( self.__targetFile )
            filehandle.seek( 0, os.SEEK_END )
            self.__readPosition = filehandle.tell()
            filehandle.close()

    def isValid( self ):
        '''
        配置是否合法
        '''
        # 文件未配置或者是空，则不合法
        if not self.__logFile:
            return False
        return True
    
    def getAlias( self ):
        '''
        获取别名
        '''
        return self.__nickName

    def startMonitor( self ):
        '''
        分析配置的log文件信息
        '''

        if not self.isValid():
            return
        # 初始化
        self.initHandle()

        # 开始监控logfile
        while True:
            starttime = time.time()
            # 更新文件信息
            self.updateFile()

            # 处理文件不存在情况
            if not os.path.exists( self.__targetFile ):
                time.sleep( self.timeInterval )
                if self.logger: self.logger.warn( '@@logHandler@@ file: %s file=%s待监控文件不存在, 暂不做监控, 等待文件出现ing...' % ( self.getAlias(), self.__targetFile ) )
                continue

            # 处理文件信息
            self.logHandler()

            # 等待一定时间
            indeedsleeptime = time.time() - starttime + self.timeInterval
            if self.logger: self.logger.info( '@@logHandler@@ file: %s, will wake up after %s seconds' % ( self.getAlias(), self.timeInterval ) )
            if indeedsleeptime > 0:
                time.sleep( indeedsleeptime )

############################################################
# 带timestamp文件类通用处理函数：可以被override
############################################################
    def updateFile( self ):
        '''
        更新文件名称和读取位置：文件名发生了变更，文件变小则从头开始读取
        '''
        timepattern = re.match( ".*@@(.*)@@", self.__logFile )
        if timepattern:
            timepattern = timepattern.group( 1 )
            timepattern = datetime.datetime.strftime( datetime.datetime.now(), timepattern )
            logfile = re.sub( "@@.*@@", timepattern , self.__logFile )
            if self.__targetFile != logfile:
                self.__targetFile = logfile
                self.__readPosition = 0
        else:
            self.__targetFile = self.__logFile
        # 如果目标文件大小变小，则认为是新文件（对于模板文件则不考虑这种情况）
        if os.path.exists( self.__targetFile ):
            curfilesize = os.stat( self.__targetFile ).st_size
            if self.__lastFileSize > curfilesize:
                self.__readPosition = 0
            self.__lastFileSize = curfilesize

    def readFileData( self ):
        '''
        读取新增文件大小,默认读取64m,没有读取更多是因为读取太多数据后，后续处理也跟不上，所以就不考虑了，这个可以满足很多的情况了。
        如果log少的hua，qps5k是没有问题的
        return : newadddata ,readmore
        '''

        readdata = ''
        readmore = False

        if self.__targetFile and os.path.exists( self.__targetFile ):
            try:
                filehandle = open( self.__targetFile )
                filehandle.seek( self.__readPosition )
                readdata = filehandle.read( self.__defaultReadSize )

                # 如果本次读取大小为DEFAULT_READ_SIZE(表明还有数据可供读取)，则继续读取，反之则不再读取
                readdatasize = len( readdata )
                if readdatasize >= self.__defaultReadSize:
                    readmore = True

                # 查找最后一行位置，并将文件游标移动到文件当前文件最后的一个完整行
                index = readdata.rfind( '\n' )
                if index > 0:
                    filehandle.seek( index + 1 - readdatasize, os.SEEK_CUR )
                    readdata = readdata[0:index]

                # 更新文件位置
                self.__readPosition = filehandle.tell()
                filehandle.close()
                if self.logger: self.logger.info( '@@logHandler@@ file: %s ,readsize: %s, defaultsize: %s, curposition: %s' % ( self.getAlias(), readdatasize, self.__defaultReadSize, self.__readPosition ) )
            except:
                pass
        return readdata, readmore

    def logHandler( self ):
        '''
        分析log文件数据（当前文件末尾）：  log文件数据读取，一次读取到文件末尾（对于当行log大于self.readFileSize,直接忽略该行  默认为 16m）
        '''

        while True:
            # 读取文件新增内容
            newcontent, readmore = self.readFileData()

            if newcontent:
                result = self.processLines( newcontent.splitlines() )
                if result:
                    if self.logger: self.logger.info( '@@logHandler@@ file: %s ,result: %s' % ( self.getAlias(), result ) )
                    if self.__dbop:
                        self.__dbop.insert( result )
                        if self.logger: self.logger.info( '@@logHandler@@ file: %s ,result inserted to database' % self.getAlias() )
            if not readmore:
                break

    def initTimestamp( self, timestampline ):
        '''
        初始化时间戳处理
        '''
        if not self.__timestampInitFlag:
            # 初始化date 匹配正则
            for timepattern in self.__datePatterns:
                match = re.match( timepattern, timestampline )
                if match:
                    self.__datePattern = re.compile( timepattern )
                    break
            # 初始化timestamp匹配正则
            for timepattern in self.__timePatterns:
                match = re.match( timepattern, timestampline )
                if match:
                    self.__timePattern = re.compile( timepattern )
                    break
            # 如果timePattern匹配上，则认为初始化完成
            # 另外，如果尝试次数小于0，也认为初始化成功,系统会自动使用系统时间
            if self.__timePattern:
                self.__timestampInitFlag = True
            else:
#                self.__timestampInitTryCount -= 1
#                if self.__timestampInitTryCount < 0:
#                    self.__timestampInitFlag = True
                self.__datePattern = None
        return self.__timestampInitFlag

    def getTimeStamp( self, timestampline, datetimestamp = None ):
        '''
        获取文本中的时间戳
        '''
        if None == datetimestamp:
            # 读取文本中date时间戳
            datetimestamp = self.__getLastDateTimestamp( timestampline )
        # 读取文本中时间戳信息：（不包括日期部分）
        daytimestamp = self.__getDayTimestamp( timestampline )
        # 将时间戳信息:格式化
        if None != datetimestamp and  None != daytimestamp:
            return self.__formatTimeStamp( datetimestamp , daytimestamp )
        return None


    def processLines( self, logLines ):
        '''
        获取统计结果
        说明：没有在进入循环前，初始化timestamp和获取datetimestamp，有因为输入的行，可能是无效行
        '''
        retdatas = []
        if not logLines:
            return retdatas

        datetimestamp = None  # 这个东东的存在是为了提高效率
        for line in logLines:
            if not self.isLineValid( line ):
                continue

            # 初始化时间戳信息,由于文件内容，不知道什么时候，才将date和day的正则弄完，所以行都需要处理
            if not self.initTimestamp( line ):
                continue
            # 更新当前日期时间戳，主要是为了提高效率（避免在getTimeStamp重复调用）
            if None == datetimestamp:
                datetimestamp = self.__getLastDateTimestamp( line )

            timestamp = self.getTimeStamp( line , datetimestamp )
            if None == timestamp:
                if self.logger: self.logger.error( '@@logHandler@@ file: %s ,unknown time format' % self.getAlias() )
                continue
            self.processLine( line, timestamp )
        return self.calcData()

    def isLineValid( self, line ):
        '''
        行是否合法:强烈建议认真override该函数，默认对于返回true的所有行，进行timestamp进行匹配
        '''
        return True

    def processLine( self, line, timestamp ):
        '''
        处理单行
        '''
        pass

    def calcData( self ):
        '''
        计算数据
        '''
        return []



############################################################
# 私有函数
############################################################
    def __getDateTimestamp( self, timestampline ):
        '''
        获取日期的timestamp，有4中方式,优先级1,2,3,4
        1，如果设置指定了startday，则返回startday
        2，如果明确表明没有时间信息，则使用系统timestamp
        3，如果可以正则匹配到，则返回正则匹配结果：如果已经为正则匹配，为匹配上，则返回none
        4，如果1,2,3都不满足，则返回系统日期timestamp（由于处理可能滞后，所以这里的系统时间始终返回的是第一次的，以后也不会改变）
        '''

        if self.startDayTimestamp:
            return self.startDayTimestamp
        else:
            if self.__noTimestamp:
                # 默认返回系统日期timestmap
                return int( time.mktime( datetime.date.today().timetuple() ) )
            # 有限进行正则匹配
            if self.__datePattern:
                match = self.__datePattern.match( timestampline )
                if match:
                    # 返回的时只有月，日
                    curdatetime = time.strptime( str( datetime.datetime.now().year ) + match.group( 1 ) + match.group( 2 ), "%Y%m%d" )
                    return int( time.mktime( curdatetime ) )
                else:
                    return None
            else:
                # 默认返回系统日期timestmap
                if not self.__defaultDayTimestamp:self.__defaultDayTimestamp = int( time.mktime( datetime.date.today().timetuple() ) )
                return self.__defaultDayTimestamp

    def __getLastDateTimestamp( self, timestampline ):
        '''
        获取日期的timestamp，有4中方式,优先级1,2,3,4
        1，如果设置指定了startday，则返回startday
        2，如果明确表明没有时间信息，则使用系统timestamp
        3，如果可以正则匹配到，则返回正则匹配结果：如果已经为正则匹配，为匹配上，则返回none
        4，如果1,2,3都不满足，则返回系统日期timestamp（由于处理可能滞后，所以这里的系统时间始终返回的是第一次的，以后也不会改变）
        
        注意，1，2，4 使用方式其实是一致的，考虑到进一步提升效率，主动更新个值
        '''

        if self.startDayTimestamp:
            # 增加对startDayTimestamp判读，主动更新
            while self.__lastMaxTimestamp - self.startDayTimestamp > 86400:
                self.startDayTimestamp += 86400
            return self.startDayTimestamp
        else:
            if self.__noTimestamp:
                # 这里将使用方式1
                self.startDayTimestamp = int( time.mktime( datetime.date.today().timetuple() ) )
                return self.startDayTimestamp
            # 优先进行正则匹配
            if self.__datePattern:
                match = self.__datePattern.match( timestampline )
                if match:
                    # 返回的时只有月，日
                    curdatetime = time.strptime( str( datetime.datetime.now().year ) + match.group( 1 ) + match.group( 2 ), "%Y%m%d" )
                    return int( time.mktime( curdatetime ) )
                else:
                    return None
            else:
                # 这里将使用方式1
                self.startDayTimestamp = int( time.mktime( datetime.date.today().timetuple() ) )
                return self.startDayTimestamp

    def __getDayTimestamp( self, timestampline ):
        '''
        获取 day的时间戳:
        1,如果明确指定没有时间信息，则返回系统timestamp
        2,优先正则匹配，如果匹配上则返回匹配timestamp
        反之返回none
        '''
        if self.__noTimestamp:
            return int( time.time() )

        # 使用正则匹配
        if self.__timePattern:
            match = self.__timePattern.match( timestampline )
            if match:
                return int( match.group( 1 ) ) * 3600 + int( match.group( 2 ) ) * 60 + int( match.group( 3 ) )
            else:
                return None
        return int( time.time() )

    # input: timeNum=14*3600+37*60+11
    # output: '20120608 12:03:40'
    def __formatTimeStamp( self, datetimestamp , daytimestamp ):
        '''
       格式化时间戳：将输入的时间戳格式化
        '''
        rettimestamp = daytimestamp
        if daytimestamp < 86400:
            rettimestamp += datetimestamp
        # 更新 rettimestamp，如果差值超过阀值，则将时间增加一天（用于跨天）
        # 为什么有了daytimestamp，还会调用下面呢？这个对于log，该log中只有day的time，而没有datetimestamp
        # 在首次跨天，比如周3，datetimestamp是周1，所以需要更新；之后datetimestamp会自更新为周2，此时计算后rettimestamp不会更新(语句为false)
        # 对于log中有datetimestamp，如果timestamp波动__threshold，在这个其实是没有影响的
        while self.__lastMaxTimestamp - rettimestamp > self.__threshold:
            rettimestamp += 86400
        # 更新 last maxtimestamp
        if self.__lastMaxTimestamp < rettimestamp : self.__lastMaxTimestamp = rettimestamp

        return rettimestamp
