#!/usr/bin/python
# coding: utf-8
# filename: commandKey.py
##################################
# @author yinxj
# @date 2013-07-11

import time
import commands
from dbOperator import DBOperator

class CommandKey:
    def __init__(self,ldap,product,type,key,command,alarmers,logger,cubeName,debug,timeInterval):
        self.key=key
        self.timeInterval=timeInterval
        self.command=command
        self.alarmers=alarmers
        self.dbop=DBOperator(ldap,product,type,cubeName,[key],[],[])
        self.logger=logger
        self.cubeName=cubeName
        self.debug=debug

    def validate(self):
        if self.key=='':
            logger.warning('%s: commandKey未配置key, 不做command的收集')
            self.dbop.close()
            return False
        if self.command=='':
            logger.warning('%s: commandKey未配置command, 不做command的收集')
            self.dbop.close()
            return False
        return True

    def toString(self):
        str=''
        str+='key=%s\n' % self.key
        str+='timeInterval=%s\n' % self.timeInterval
        str+='command=%s\n' % self.command
        str+='alarmers=[%s]\n' % ','.join([alarmer.toString() for alarmer in self.alarmers])
        return str

    def monitor(self):
        while True:
            currentTime = int( time.time() )
            currentTime = currentTime - currentTime % self.timeInterval
            value = self.getDataFromCommand()
            if value:
                # add timestamp
                insertData = [[currentTime, self.key, value]]
                self.logger.info( '%s: @@commandKeyHandler@@ result=%s' % ( self.cubeName, insertData ) )
                self.alarm(insertData)
                if not self.debug:
                    self.dbop.insert( insertData )
                self.logger.info( '%s: @@commandKeyHandler@@ result inserted to database' % self.cubeName )
            else:
                self.logger.warning( '%s: @@commandKeyHandler@@ no data get' % self.cubeName )
            endTime = int( time.time() )
            sleepTime = self.timeInterval - ( endTime - currentTime )
            if sleepTime <= 0:
                continue
            self.logger.info( '%s: @@commandKeyHandler@@ sleep, will wake up in %ss' % ( self.cubeName, sleepTime ) )
            time.sleep( sleepTime )

    def alarm(self,insertData):
        for data in insertData:
            for alarmer in self.alarmers:
                alarmer.alarm(data[2],self.cubeName,self.key)

    def getDataFromCommand(self):
        result=commands.getoutput(self.command).splitlines()
        result=result[0].split()
        result=result[0]
        try:
            return float(result)
        except:
            return None

