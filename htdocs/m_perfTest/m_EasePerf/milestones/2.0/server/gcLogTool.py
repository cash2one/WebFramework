#!/usr/bin/python
# coding=utf-8
# filename: gcLogTool.py
#####################################################################################
# @author yinxj
# @date 2013-03-29
#####################################################################################
# GC数据收集工具: 使用GCViewer.jar
# step 1. 将logLines写入文件 srcGCTmpFile
# step 2. 将srcGCTmpFile传给GCViewer.jar处理
# step 3. 读GCViewer.jar处理后给出的resGCTmpFile
#
# resGCTmpFile格式为:
# timestamp\tkey\tvalue
# timestamp为标准的timestamp或服务启动的time, unit: second
# key以"gc."开头, 其中不需要展示的key用self.keys进行过滤
# value为double类型, boolean类型用0, 1分别表示false, true
#
# 若无标准时间, 且服务有重启导致时间从0开始重计的情况已在gcviewer中进行处理
# 因此在此处只需要处理新的时间比已插入的时间小的情况
#####################################################################################
# usage:
# gcLogTool=GCLogTool(logger,gcKeys)
# gcLogTool.insert(proccessor.getStatisticData(logLines))
#####################################################################################

import string
import os
import sys
import re
import time
import datetime
import logging
import commands
import subprocess

class GCLogTool:
    def __init__( self, logger=None, gcKeys=[] ):
        self.logger=logger
        self.startTimestamp=0
        self.lastSeconds=0
        self.keys=gcKeys
        self.timeType=-1
        # 希望最多每LIMIT_LEN行做一次处理: 提高统计值的分辨率过低
        self.LIMIT_LEN=300
        # 对传入的logLines最多处理的次数
        self.maxNumber=10
        # (timeType=2)用于将起始时间平移至服务时间, 服务重启前后的时间则不再平移(忽略服务启动时间)
        self.hasInited=False
        self.devnull=open('/dev/null','w')

    def getStatisticData( self, logLines):
        if len(logLines)==0:
            return []
        statisticData=[]
        length=len(logLines)
        self.logger.info('@@GCLogProcessor@@ log.len=%s' % length)
        N=length/self.LIMIT_LEN+1
        if N>self.maxNumber:
            N=self.maxNumber
        for n in range(N):
            statisticData = statisticData + self.getData(logLines[n*length/N:(n+1)*length/N])
        return statisticData

    def getData( self, logLines ):
        statisticData=[]
        # step 1
        # avoid file name conflict
        curTime=time.time()
        srcGCTmpFile='%s.%s.%s' % ('gc.tmp.src',curTime,len(logLines))
        fsrc=open(srcGCTmpFile,'w')
        fsrc.write('\n'.join(logLines))
        fsrc.close()
        self.logger.info('@@GCLogProcessor@@ prepared file for gcviewer')
        resGCTmpFile='%s.%s.%s' % ('gc.tmp.res',curTime,len(logLines))
        # step 2
        processTime=time.time()
        cmd='java -jar ./nagios/lib/gcviewer.jar %s %s detail' % (srcGCTmpFile,resGCTmpFile)
        subprocess.Popen( args = cmd, shell = True, stdout = self.devnull, stderr = self.devnull ).communicate()
        self.logger.info('@@GCLogProcessor@@ gcviewer done, gcviewer.time=%s' % (time.time()-processTime))
        # step 3
        self.refreshTime(resGCTmpFile)
        resf=open(resGCTmpFile)
        while True:
            line=resf.readline()
            if line=='':
                break
            data=line.strip().split('\t')
            if data[1] not in self.keys:
                continue
            try:
                value=float(data[2])
            except:
                continue
            # self.timeType==1时, startTimestamp=0
            statisticData.append([float(data[0])+self.startTimestamp,data[1],value])
        if self.timeType==2 and len(statisticData)>0:
            self.lastSeconds=statisticData[-1][0]-self.startTimestamp
        commands.getoutput('rm -f %s %s' % (resGCTmpFile,srcGCTmpFile))
        return statisticData

    def refreshTime(self,resGCTmpFile):
        if self.timeType==1:
            return
        resf=open(resGCTmpFile)
        line=resf.readline()
        if line=='':
            return
        t=float(line.strip().split('\t')[0])
        resf.close()
        if t<100000000:
            self.timeType=2
            if t<self.lastSeconds:
                self.startTimestamp = self.startTimestamp + self.lastSeconds
            if not self.hasInited:
                self.startTimestamp = time.time() - t;
                self.hasInited=True
        else:
            self.timeType=1
