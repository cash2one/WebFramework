# encoding=utf-8
'''
性能测试工具收集
日志处理模块:该模块数据处理方式参考yxj（list方式）

Created on 2013-4-7

@author: 张波
'''

import re
from fileHandleImpl import fileHandleImpl


class logHandleImpl( fileHandleImpl ):
    '''
    服务程序日志处理实现
    '''

    def __init__( self, logfile , nickname, timeinterval, numberkeys, strkeys, logger , dbop , starthead = False, startdate = None, notimestamp = False, enablemultikey = False, datadelaytime = 0 ):
        '''
        Constructor
        '''
        fileHandleImpl.__init__( self, logfile, nickname, timeinterval, logger, dbop, starthead, startdate, notimestamp )

        self.__dataDelayTime = timeinterval  # 数据延时显示时间
        if datadelaytime > timeinterval:
            self.__dataDelayTime = timeinterval * ( int( ( datadelaytime + timeinterval - 1 ) / timeinterval ) )
        # 监控项
        self.__enableMultiKey = enablemultikey  # 是否支持单行内处理多个key
        self.__numberKeys = numberkeys
        self.__strKeys = strkeys
        self.__monitorKeys = []  # 为了方面内部使用
        self.__monitorKeys.extend( self.__numberKeys )
        self.__monitorKeys.extend( self.__strKeys )
        self.__monitorKeys = dict.fromkeys( list( set( self.__monitorKeys ) ), True )
        self.__valuePatternSingle = re.compile( r'\s+([\w\.-_]+)\s*=\s*([\w\.]+)' )
        self.__valuePatternAll = re.compile( r'.*\s+([\w\.-_]+)\s*=\s*([\w\.]+)' )
        self.__regexStartIndex = 0  # 正则匹配位置
        # 初始化匹配函数
        if enablemultikey:
            self.extractValesPairs = self.__extractAllValesPairs
        else:
            self.extractValesPairs = self.__extractSingleValesPairs


############################################################
# override fileHandleImpl函数
############################################################
    def initHandle( self ):
        '''
        初始化: overrider 父类初始化函数
        '''
        # 调用父类初始化
        fileHandleImpl.initHandle( self )

        # 初始化为列表处理
#        self.__initHandleByList()

        # 初始化为字典处理
        self.__initHandleByDict()

    def isValid( self ):
        '''
        配置是否合法:override 父类方法
        '''

        if not fileHandleImpl.isValid( self ):
            return False

        # string类型和number类型不能有相同项，否则无法区分
        if set( self.__numberKeys ) & set( self.__strKeys ):
            return False
        return True

    def isLineValid( self, line ):
        '''
        行是否合法
        '''
        # @@ANALYSIS@@ size = 12
        ANALYSIS_LENGTH = 12
        self.__regexStartIndex = line.find( '@@ANALYSIS@@' )
        if self.__regexStartIndex < 0:
            self.__regexStartIndex = 0
            return False
        self.__regexStartIndex += ANALYSIS_LENGTH
        return True


############################################################
# 私有函数
############################################################
    def __initHandleByList( self ):
        '''
        初始化数据处理存放方式为:list
        '''
        # 数据存放方式是字列表的，[ (timestamp :{ key :  values}),]
        self.__strLogs = [( 0, {} ), ]  # 字符类型
        self.__numberLogs = [( 0, {} ), ]  # 数值类型
        self.processLine = self.__processLineWithList
        self.calcData = self.__calcDataWithList

    def __initHandleByDict( self ):
        '''
        初始化数据处理存放方式为: dict
        '''
        # 数据存放方式是字典的，{  timestamp :{ key :  values} }
        self.__strLogs = {}  # 字符类型
        self.__strLogMaxTimeStamp = 0  # 字符类型，最大timestamp
        self.__numberLogs = {}  # 数值类型
        self.__numberLogMaxTimeStamp = 0  # 数值类型，最大timestamp

        self.processLine = self.__processLineWithDict
        self.calcData = self.__calcDataWithDict

    def __extractSingleValesPairs( self, line ):
        '''
        获取[(itemkey, itemvalue),]：只能提前单对（性能上比__extractAllValesPairs好1倍）
        '''
        matchresult = self.__valuePatternSingle.match( line, self.__regexStartIndex )
        if matchresult:
            return[ matchresult.groups() ]
        return []

    def __extractAllValesPairs( self, line ):
        '''
         获取[(itemkey, itemvalue),]：提取所有
        '''
        matchresult = self.__valuePatternAll.findall( line, self.__regexStartIndex )
        return matchresult



    def __processLineWithList( self, line, timestamp ):
        '''
        处理单行: 使用match，替换findall,时间消耗只有原来40%，当然无法处理一行多个的情况，目前的log中，没有此类情况
        '''
        keys_values = self.extractValesPairs( line )
        for itemkey, itemvalue in keys_values:
            if itemkey not in self.__monitorKeys:
                return
            # 数据存放方式是字典型的， timestamp :{ key :  values}
            # 重新计算timestamp，结果为timeinteval的倍数
            timestamp = timestamp - timestamp % self.timeInterval
            # 数值类型log：  需要考虑浮点型 34.5
            if itemvalue.lower() == itemvalue.upper():
                # 若numberLogs为空或当前log的时间应属于下一时段
                if timestamp != self.__numberLogs[-1][0]:
                    self.__numberLogs.append( ( timestamp, {} ), )
                try:
                    keyvalue = float( itemvalue )
                    # 若当前时段的log中第一次出现该key，则增加该key
                    if not self.__numberLogs[-1][1].has_key( itemkey ):
                        self.__numberLogs[-1][1][itemkey] = [ keyvalue ]
                    # 若已存在该key，则添加该值
                    else:
                        self.__numberLogs[-1][1][itemkey].append( keyvalue )
                except:
                    if self.logger: self.logger.info( '@@logHandler@@ file: %s ,%s is not digit. line: %s' % ( self.getAlias(), itemvalue, line ) )
            # string 类型log
            else:
                # 若numberLogs为空或当前log的时间应属于下一时段
                if timestamp != self.__strLogs[-1][0]:
                    self.__strLogs.append( ( timestamp, {} ), )
                # 若该key与对应的value组成的key+value未记录，则增加该key+value，并记数为1
                targetkey = itemkey + '^' + itemvalue
                if not self.__strLogs[-1][1].has_key( targetkey ):
                    self.__strLogs[-1][1][targetkey] = 1
                # 若该key对应的value组成的key+value已记录，则该key+value记数加1
                else:
                    self.__strLogs[-1][1][targetkey] += 1

    def __calcDataWithList( self ):
        '''
        计算数据
        '''
        res = []
        # 计算数值型结果:保留最后一次
        if self.__numberLogs:
            datalogs = self.__numberLogs[:-1]
            self.__numberLogs = [ self.__numberLogs[-1] ]
            for timestamp, datas in datalogs:
                for keyName in datas:
                    keyValues = datas[keyName]
                    keyValues.sort()
                    length = len( keyValues )
                    res.append( ( timestamp, keyName + '.min', keyValues[0] ) )
                    res.append( ( timestamp, keyName + '.max', keyValues[-1] ) )
                    res.append( ( timestamp, keyName + '.avg', float( sum( keyValues ) ) / length ) )
                    res.append( ( timestamp, keyName + '.90', keyValues[int( 0.9 * length + 0.5 ) - 1] ) )
                    res.append( ( timestamp, keyName + '.99', keyValues[int( 0.99 * length + 0.5 ) - 1] ) )
                    res.append( ( timestamp, keyName + '.qps', float( length ) / self.timeInterval ) )
        # 计算字符型结果:保留最后一次
        if self.__strLogs:
            datalogs = self.__strLogs[:-1]
            self.__strLogs = [ self.__strLogs[-1] ]
            for timestamp, datas in datalogs:
                for keyName in datas:
                    keyValue = datas[keyName]
                    res.append( ( timestamp, keyName, float( keyValue ) / self.timeInterval ) )
        return res


    def __processLineWithDict( self, line, timestamp ):
        '''
        处理单行: 使用match，替换findall,时间消耗只有原来40%，当然无法处理一行多个的情况，目前的log中，没有此类情况
        '''
        keys_values = self.extractValesPairs( line )
        for itemkey, itemvalue in keys_values:
            if itemkey not in self.__monitorKeys:
                return
            # 数据存放方式是字典型的， timestamp :{ key :  values}
            # 重新计算timestamp，结果为timeinteval的倍数
            timestamp = timestamp - timestamp % self.timeInterval
            # 数值类型log：  需要考虑浮点型 34.5
            if itemvalue.lower() == itemvalue.upper():
                # 更新__numberLogMaxTimeStamp
                if self.__numberLogMaxTimeStamp < timestamp: self.__numberLogMaxTimeStamp = timestamp
                try:
                    keyvalue = float( itemvalue )
                    # 如果timestamp不存在，则添加该timestamp数据
                    if not self.__numberLogs.has_key( timestamp ): self.__numberLogs[timestamp] = {}

                    # 若当前时段的log中第一次出现该key，则增加该key
                    if not self.__numberLogs[timestamp].has_key( itemkey ):
                        self.__numberLogs[timestamp][itemkey] = [ keyvalue ]
                    else:
                        self.__numberLogs[timestamp][itemkey].append( keyvalue )
                except:
                    if self.logger: self.logger.info( '@@logHandler@@ file: %s ,%s is not digit. line: %s' % ( self.getAlias(), itemvalue, line ) )
            # string 类型log
            else:
                # 更新__strLogMaxTimeStamp
                if self.__strLogMaxTimeStamp < timestamp: self.__strLogMaxTimeStamp = timestamp

                keyvalue = float( itemvalue )
                # 如果timestamp不存在，则添加该timestamp数据
                if not self.__strLogs.has_key( timestamp ): self.__strLogs[timestamp] = {}

                # 若该key与对应的value组成的key+value未记录，则增加该key+value，并记数为1
                targetkey = itemkey + '^' + itemvalue
                if not self.__strLogs[timestamp].has_key( targetkey ):
                    self.__strLogs[timestamp][targetkey] = 1
                else:
                    self.__strLogs[timestamp][targetkey] += 1
                # 若numberLogs为空或当前log的时间应属于下一时段
                if timestamp != self.__strLogs[-1][0]:
                    self.__strLogs.append( ( timestamp, {} ), )
                # 若该key与对应的value组成的key+value未记录，则增加该key+value，并记数为1

    def __calcDataWithDict( self ):
        '''
        计算数据:,保留self.__dataDelayTime数据，可能在以后被更新
        '''
        res = []
        # 计算数值型结果
        reserverdata = {}
        for timestamp, datas in self.__numberLogs.iteritems():
            # timestamp在 [__numberLogMaxTimeStamp - __dataDelayTime,__numberLogMaxTimeStamp]将保留起来以后处理
            if self.__numberLogMaxTimeStamp - timestamp < self.__dataDelayTime:
                reserverdata[timestamp] = datas
                continue
            for keyName in datas:
                keyValues = datas[keyName]
                keyValues.sort()
                length = len( keyValues )
                res.append( ( timestamp, keyName + '.min', keyValues[0] ) )
                res.append( ( timestamp, keyName + '.max', keyValues[-1] ) )
                res.append( ( timestamp, keyName + '.avg', float( sum( keyValues ) ) / length ) )
                res.append( ( timestamp, keyName + '.90', keyValues[int( 0.9 * length + 0.5 ) - 1] ) )
                res.append( ( timestamp, keyName + '.99', keyValues[int( 0.99 * length + 0.5 ) - 1] ) )
                res.append( ( timestamp, keyName + '.qps', float( length ) / self.timeInterval ) )
        #  保留数据
        self.__numberLogs = reserverdata

        # 计算字符型结果
        reserverdata = {}
        for timestamp, datas in self.__strLogs.iteritems():
            # timestamp在 [__strLogMaxTimeStamp - __dataDelayTime,__strLogMaxTimeStamp]将保留起来以后处理
            if self.__strLogMaxTimeStamp - timestamp < self.__dataDelayTime:
                reserverdata[timestamp] = datas
                continue
            for keyName in datas:
                keyValue = datas[keyName]
                res.append( ( timestamp, keyName, float( keyValue ) / self.timeInterval ) )
        # 保留数据
        self.__strLogs = reserverdata
        return res
