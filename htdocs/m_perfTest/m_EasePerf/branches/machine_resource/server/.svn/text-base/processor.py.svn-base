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
import string, os, sys
import re, time, datetime
import logging.config
import logging

# 假设log的时间单调递增
class Processor:
	def __init__( self, whiteList, timeInterval = 30 , startdate = None ):
		self.inputDayTimestamp = None
		self.lastMaxTimestamp = None
		self.timeInterval = timeInterval  # sampler interval
		self.logger = logging.getLogger( "mylog" )
		self.strLogs = []
		self.numberLogs = []
		self.whiteList = whiteList
		self.threshold = 3600  # to process diff time pattern
		self.timePatternNO = -1  # log time pattern. init -1, and will have an positive value after one match
		self.timePattern = ['[^\d]*(\d{2}):(\d{2}):(\d{2})\.\d{3}.*', '[^\d]*\d{6} (\d{2})(\d{2})(\d{2}).*', '[^\d]*\d{4}-\d{2}-\d{2} (\d{2}):(\d{2}):(\d{2}).*', '[^\d]*\d{4}(\d{2})(\d{2})(\d{2}).*']
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
			matchRes = re.match( r'(.*)@@ANALYSIS@@.*', line )
			if matchRes == None:
				 if line.find( '@@ANALYSIS@@' ) != -1:
					 self.logger.warn( 'parser has error!\n line is: %s\n' % line )
				 continue

			timestamp = self.time2num( matchRes.group( 1 ) )
			if timestamp == -1:
				self.logger.error( 'unknown time format\n' )
				continue
			keys_values = re.findall( r'([\w\.-_]+)\s*=\s*([\w]+)', line )
			for keyRes, valueRes in keys_values:
				if keyRes not in self.whiteList:
					continue
				# number log
				# considering the format: 34.5
				if valueRes.lower() == valueRes.upper():
					# 若numberLogs为空或当前log的时间应属于下一时段
					if self.numberLogs.__len__() == 0 or abs( timestamp - self.numberLogs[-1][0] ) >= self.timeInterval:
						self.numberLogs.append( [timestamp - timestamp % self.timeInterval, {}] )
					# 若当前时段的log中第一次出现该key，则增加该key
					if not self.numberLogs[-1][1].has_key( keyRes ):
						try:
						  float( valueRes )
						except:
						  print "this is the really failed line: " + line
						  print valueRes + ' is not digit'
						self.numberLogs[-1][1][keyRes] = [float( valueRes )]
					# 若已存在该key，则添加该值
					else:
						self.numberLogs[-1][1][keyRes] += [float( valueRes )]

				# string log
				else:
					# 若strLogs为空或当前log的时间应属于下一时段
					if self.strLogs.__len__() == 0 or abs( timestamp - self.strLogs[-1][0] ) >= self.timeInterval:
						self.strLogs.append( [timestamp - timestamp % self.timeInterval, {}] )
					# 若该key与对应的value组成的key+value未记录，则增加该key+value，并记数为1
					if not self.strLogs[-1][1].has_key( keyRes + '^' + valueRes ):
						self.strLogs[-1][1][keyRes + '^' + valueRes] = 1
					# 若该key对应的value组成的key+value已记录，则该key+value记数加1
					else:
						self.strLogs[-1][1][keyRes + '^' + valueRes] += 1
		return self.popData()

	# input: different log time pattern
	# output: timeNum=14*3600+37*60+11
	def time2num( self, timeStr ):
		if self.timePatternNO != -1:
			match = re.match( self.timePattern[self.timePatternNO], timeStr )
			if match == None:
				return -1
			return int( match.group( 1 ) ) * 3600 + int( match.group( 2 ) ) * 60 + int( match.group( 3 ) )
		# self.timePatternNO==-1
		for n in range( len( self.timePattern ) ):
			match = re.match( self.timePattern[n], timeStr )
			if match == None:
				continue
			self.timePatternNO == n
			return int( match.group( 1 ) ) * 3600 + int( match.group( 2 ) ) * 60 + int( match.group( 3 ) )
		# "unknown time format"
		return -1

	# input: timeNum=14*3600+37*60+11
	# output: '20120608 12:03:40'
	def time2stamp( self, timestamp ):
		rettimestamp = timestamp
		if timestamp < 24 * 3600:
			if None != self.inputDayTimestamp:
				rettimestamp = self.inputDayTimestamp + timestamp
			else:
				rettimestamp = int( time.mktime( datetime.date.today().timetuple() ) ) + timestamp
			# update last maxtimestamp
			if None == self.lastMaxTimestamp:
				self.lastMaxTimestamp = rettimestamp
			else:
				if self.lastMaxTimestamp - rettimestamp > self.threshold:
					rettimestamp += ( 24 * 3600 )
					if None != self.inputDayTimestamp:
						self.inputDayTimestamp += ( 24 * 3600 )
				if self.lastMaxTimestamp < rettimestamp :
					self.lastMaxTimestamp = rettimestamp
		return rettimestamp

	def popData( self ):
		res = []
		while self.numberLogs.__len__() > 1:
			res += self.popNumberData()
		while self.strLogs.__len__() > 1:
			res += self.popStrData()
		# print res
		return res


	def popNumberData( self ):
		res = []
		data = self.numberLogs.pop( 0 )
		logTime = self.time2stamp( data[0] )
		for keyName, keyValue in data[1].items():
			keyValue.sort()
			length = keyValue.__len__()
			res.append( [logTime, keyName + '.min', keyValue[0]] )
			res.append( [logTime, keyName + '.max', keyValue[-1]] )
			res.append( [logTime, keyName + '.avg', float( sum( keyValue ) ) / length] )
			res.append( [logTime, keyName + '.90', keyValue[int( 0.9 * length + 0.5 ) - 1]] )
			res.append( [logTime, keyName + '.99', keyValue[int( 0.99 * length + 0.5 ) - 1]] )
			res.append( [logTime, keyName + '.qps', float( length ) / self.timeInterval] )
		return res

	def popStrData( self ):
		res = []
		data = self.strLogs.pop( 0 )
		logTime = self.time2stamp( data[0] )
		for keyName, keyValue in data[1].items():
			res.append( [logTime, keyName, float( keyValue ) / self.timeInterval] )
		return res
