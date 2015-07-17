#!/usr/bin/python
# coding=utf-8
# filename: processor.py
##################################
# @author yinxj
# @date 2012-06-06
##################################
# 性能测试工具收集
# log数据处理
# usage:
# db.insert(proccessor.getStatisticData(logLines))
# Log文件中最后的一个timeInterval的数据将不会即时返回，这用来防止数据库中会插入两个相同时刻的问题
##################################
import re, time, datetime


# 假设log的时间单调递增
class LogProcessor:
    def __init__( self, whiteList, timeInterval = 30 , startdate = None, notimestamp = False, logger = None ):
        self.inputDayTimestamp = None
        self.lastMaxTimestamp = 0  # 当前最大timestamp
        self.initDayTimestamp = int( time.mktime( datetime.date.today().timetuple() ) )  # 程序初始化日期timestamp
        self.timeInterval = timeInterval  # sampler interval
        self.logger = logger
        self.strLogs = []
        self.numberLogs = []
        self.whiteList = whiteList
        self.__threshold = 3600  # day grow when find timestamp plus
        self.noTimeStamp = notimestamp  # if this is true, will ignore timestamp in log file and use system timestamp
        self.timePattern = None  # log time pattern
        # patterns = [hh:mm:ss.sss, YYMMDD hhmmss, YYYY-MM-DD hh:mm:ss, MMDDhhmmss]
        self.timePatterns = ['[^\d]*(\d{2}):(\d{2}):(\d{2})\.\d{3}.*', '[^\d]*\d{6} (\d{2})(\d{2})(\d{2}).*', '[^\d]*\d{4}-\d{2}-\d{2} (\d{2}):(\d{2}):(\d{2}).*', '[^\d]*\d{4}(\d{2})(\d{2})(\d{2}).*','\[\d{2}/\w*/\d{4}:(\d{2}):(\d{2}):(\d{2}).*']
        # parse input startdate
        if None != startdate:
            startdate = str( startdate )
            try:
                if startdate.isdigit():
                    startdate = int( startdate )
                    self.inputDayTimestamp = int( time.mktime( datetime.date.today().timetuple() ) ) - startdate * 24 * 3600
                else:
                    startdate = datetime.datetime.strptime( startdate , "%Y-%m-%d" )
                    self.inputDayTimestamp = int( time.mktime( startdate.timetuple() ) )
            except:
                pass

    def getStatisticData( self, logLines ):
        for line in logLines:
            index = line.find( '@@ANALYSIS@@' )
            if -1 == index:
                continue

            timestamp = -1
            if self.noTimeStamp:
                timestamp = int( time.mktime( datetime.datetime.now().timetuple() ) )
            else:
                timestamp = self.getDayTimeStamp( line[:index] )
            if -1 == timestamp:
                self.logger.error( '@@LogProcessor@@ unknown time format: line=%s' % line )
                continue
            keys_values = re.findall( r'([\w\.\-_\{\}]+)\s*=\s*([\w\.]+)', line )
            for keyRes, valueRes in keys_values:
                if keyRes not in self.whiteList:
                    continue
                # number log
                # considering the format: 34.5
                if valueRes.lower() == valueRes.upper():
                    # 若numberLogs为空或当前log的时间应属于下一时段
                    if 0 == len( self.numberLogs ) or abs( timestamp - self.numberLogs[-1][0] ) >= self.timeInterval:
                        self.numberLogs.append( [timestamp - timestamp % self.timeInterval, {}] )
                    try:
                        keyvalue = float( valueRes )
                        # 若当前时段的log中第一次出现该key，则增加该key
                        if not self.numberLogs[-1][1].has_key( keyRes ):
                            self.numberLogs[-1][1][keyRes] = [ keyvalue ]
                        # 若已存在该key，则添加该值
                        else:
                            self.numberLogs[-1][1][keyRes].append( keyvalue )
                    except:
                        print "this is the really failed line: " + line
                        print valueRes + ' is not digit'
                # string log
                else:
                    # 若strLogs为空或当前log的时间应属于下一时段
                    if 0 == len( self.strLogs ) or abs( timestamp - self.strLogs[-1][0] ) >= self.timeInterval:
                        self.strLogs.append( [timestamp - timestamp % self.timeInterval, {}] )
                    # 若该key与对应的value组成的key+value未记录，则增加该key+value，并记数为1
                    targetkey = keyRes + '^' + valueRes
                    if not self.strLogs[-1][1].has_key( targetkey ):
                        self.strLogs[-1][1][targetkey] = 1
                    # 若该key对应的value组成的key+value已记录，则该key+value记数加1
                    else:
                        self.strLogs[-1][1][targetkey] += 1
        return self.popData()

    # input: different log time pattern
    # output: timeNum=14*3600+37*60+11
    def getDayTimeStamp( self, timeStr ):
        if self.timePattern:
            match = self.timePattern.match( timeStr )
            if match == None:
                return -1
            return int( match.group( 1 ) ) * 3600 + int( match.group( 2 ) ) * 60 + int( match.group( 3 ) )
        for timepattern in self.timePatterns:
            match = re.match( timepattern, timeStr )
            if match == None:
                continue
            self.timePattern = re.compile( timepattern )
            return int( match.group( 1 ) ) * 3600 + int( match.group( 2 ) ) * 60 + int( match.group( 3 ) )
        return -1

    # input: timeNum=14*3600+37*60+11
    # output: '20120608 12:03:40'
    def formateTimeStamp( self, timestamp ):
        rettimestamp = timestamp
        # 如果timestamp没有包含日期，则加上日期
        if rettimestamp < 86400:
            if None != self.inputDayTimestamp:
                rettimestamp = self.inputDayTimestamp + rettimestamp
            else:
                # 直接使用initDayTimestamp作为天的timestamp，没有使用系统天的timestamp
                # 是考虑到分析文件会有滞后问题；该timestamp并不是最终时间需要和lastMaxTimestamp进行比较和更新
                rettimestamp = self.initDayTimestamp + rettimestamp

        # 更新 rettimestamp，如果差值超过阀值，则将时间增加一天（用于跨天）
        while self.lastMaxTimestamp - rettimestamp > self.__threshold:
            rettimestamp += 86400
        # 更新 last maxtimestamp
        if self.lastMaxTimestamp < rettimestamp : self.lastMaxTimestamp = rettimestamp
        return rettimestamp

    def popData( self ):
        res = self.popNumberData()
        res.extend( self.popStrData() )
        # print res
        return res

    def popNumberData( self ):
        '''
        取出数值型结果:保留最后一次
        '''
        res = []
        if self.numberLogs:
            datalogs = self.numberLogs[:-1]
            self.numberLogs = [ self.numberLogs[-1] ]
            for data in datalogs:
                logTime = self.formateTimeStamp( data[0] )
                for keyName, keyValues in data[1].items():
                    keyValues.sort()
                    length = len( keyValues )
                    res.append( [logTime, keyName + '.min', keyValues[0]] )
                    res.append( [logTime, keyName + '.max', keyValues[-1]] )
                    res.append( [logTime, keyName + '.avg', float( sum( keyValues ) ) / length] )
                    res.append( [logTime, keyName + '.90', keyValues[int( 0.9 * length + 0.5 ) - 1]] )
                    res.append( [logTime, keyName + '.99', keyValues[int( 0.99 * length + 0.5 ) - 1]] )
                    res.append( [logTime, keyName + '.qps', float( length ) / self.timeInterval] )
        return res

    def popStrData( self ):
        '''
        取出字符型结果:保留最后一次
        '''
        res = []
        if self.strLogs:
            datalogs = self.strLogs[:-1]
            self.strLogs = [ self.strLogs[-1] ]
            for data in datalogs:
                logTime = self.formateTimeStamp( data[0] )
                for keyName, keyValue in data[1].items():
                    res.append( [logTime, keyName, float( keyValue ) / self.timeInterval] )
        return res
