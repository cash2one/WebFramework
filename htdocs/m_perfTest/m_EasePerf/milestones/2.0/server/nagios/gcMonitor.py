#!/usr/bin/python
# coding: utf-8
# filename: gcMonitor.py
##################################
# @author yinxj
# @date 2013-07-23
##################################
# for nagios

import os
import time
import subprocess
import logging
import sys
import optparse

logger=None
logFile='gcMonitor.log'

class GCMonitor():
    def __init__(self,gcLogFile,keys):
        self.gcLogFile=gcLogFile
        self.keys=keys
        #
        self.lineToProcessFirstTime=1000
        self.defaultReadSize=10*1000*1000
        self.prePosition=0
        self.preFileSize=0
        #
        self.flag=gcLogFile.strip('/').replace('/','.')
        self.filter='%s.filter' % self.flag
        self.sourceGCFile='%s.source.gc.log.%s' % (self.flag,time.time())
        self.resGCFile='%s.res.gc.log.%s' % (self.flag,time.time())
        #
        self.devnull=open('/dev/null','w')

    def loadFilter(self):
        if not os.path.exists(self.filter):
            logger.info('%s: file not exists, this is first time' % self.filter)
        else:
            f=open(self.filter)
            r=f.readline().strip().split()
            self.prePosition=int(r[0])
            self.preFileSize=int(r[1])
            curFileSize=os.stat(self.gcLogFile).st_size
            if self.preFileSize>curFileSize:
                logger.warning('%s: file size less than last time, reset prePosition and preFileSize to 0' % self.gcLogFile)
                self.prePosition=0
                self.preFileSize=0
        logger.info('prePosition=%s' % self.prePosition)
        logger.info('preFileSize=%s' % self.preFileSize)

    def saveFilter(self):
        f=open(self.filter,'w')
        f.write('%s\t%s\n' % (self.prePosition,self.preFileSize))
        f.close()

    def prepareSouceFile(self):
        if self.prePosition==0:
            cmd='tail -%s %s > %s' % (self.lineToProcessFirstTime,self.gcLogFile,self.sourceGCFile)
            logger.info('cmd=%s' % cmd)
            subprocess.Popen( args = cmd, shell = True, stdout = self.devnull, stderr = self.devnull ).communicate()
        else:
            f=open(self.gcLogFile)
            f.seek(self.prePosition)
            sf=open(self.sourceGCFile,'w')
            while True:
                data=f.read(self.defaultReadSize)
                if len(data)==0:
                    break
                sf.write(data)
            f.close()
            sf.close()
        f=open(self.gcLogFile)
        f.seek(-1,os.SEEK_END)
        self.prePosition=f.tell()
        f.close()
        self.preFileSize=os.stat(self.gcLogFile).st_size

    def getResFile(self):
        cmd='java -jar lib/gcviewer.jar %s %s detail' % (self.sourceGCFile,self.resGCFile)
        logger.info('cmd=%s' % cmd)
        subprocess.Popen( args = cmd, shell = True, stdout = self.devnull, stderr = self.devnull ).communicate()
        if not os.path.exists(self.resGCFile):
            self.removeTmpFiles()
            fail('gcviewer: no result found')

    def parseResFile(self):
        res={}
        f=open(self.resGCFile)
        while True:
            line=f.readline()
            if line=='':
                break
            data=line.strip().split('\t')
            try:
                if data[1] in self.keys:
                    res[data[1]]=data[2]
            except:
                pass
        f.close()
        return res

    def printRes(self,res):
        r=[]
        for key in self.keys:
            if res.has_key(key):
                r.append(res[key])
            else:
                r.append('NaN')
                logger.warning('key=%s: cannot get value, return NaN' % key)
        print '\t'.join(r)

    def removeTmpFiles(self):
        cmd='rm -f %s %s' % (self.sourceGCFile,self.resGCFile)
        logger.info('cmd=%s' % cmd)
        subprocess.Popen( args = cmd, shell = True, stdout = self.devnull, stderr = self.devnull ).communicate()

    def monitor(self):
        if not os.path.exists(self.gcLogFile):
            logger.info('%s: file not exists' % self.gcLogFile)
            self.saveFilter()
            fail('%s: file not exists' % self.gcLogFile)
        self.loadFilter()
        self.prepareSouceFile()
        self.saveFilter()
        if os.stat(self.sourceGCFile).st_size<10:
            self.removeTmpFiles()
            fail('%s: no new gc log after last time' % self.gcLogFile)
        self.getResFile()
        res=self.parseResFile()
        self.printRes(res)
        self.removeTmpFiles()

def getLogger():
    global logger
    logger = logging.getLogger()
    hdlr = logging.FileHandler(logFile )
    formatter = logging.Formatter( '%(asctime)s %(levelname)s %(message)s' )
    hdlr.setFormatter( formatter )
    logger.addHandler( hdlr )
    logger.setLevel( logging.DEBUG )

def fail(message):
    logger.warning(message)
    print 'NaN'
    exit(-1)


def main( argv ): 
    help='''
python gcMonitor.py gcLogFile key1 key2 key3 ... keyN
  - gcLogFile: file of gc log
  - key1, key2, key3, ..., keyN: keys from doc/gc-op.keys
-------------------------------------------------------------------------------------------------
output:
  - value1 value2 value3 ... valueN: values of key1, key2, key3, ..., keyN
  - value1 NaN value3 ... valueN: as above, while "NaN" means cannot get value of the corresponding key
  - NaN: failed
          - gcLogFile not exists
          - no gc event happens after previous check
-------------------------------------------------------------------------------------------------
e.g.: 
python gcMonitor.py /disk2/ds/jvm.log gc.tenuredUsed.avg gc.time.max
output: "85 15000" or "85 NaN" or "NaN 15000" or "NaN NaN" or "NaN"
-------------------------------------------------------------------------------------------------
recommended jvm parameters: 
-Xloggc:<file> -XX:+PrintGCDetails -XX:+PrintGCTimeStamps -XX:+PrintGCDateStamps
'''

    reload( sys )
    sys.setdefaultencoding( 'utf-8' )
    parser = optparse.OptionParser( usage =help )
    ( options, args ) = parser.parse_args( sys.argv[1:] )
    getLogger()
    if len(args)>=2:
        gcMonitor=GCMonitor(args[0],args[1:])
        gcMonitor.monitor()
    else:
        fail('wrong usage: see help with "-h"')

if __name__ == "__main__":
    main( sys.argv )
