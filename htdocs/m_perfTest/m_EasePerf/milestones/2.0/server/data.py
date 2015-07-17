#!/usr/bin/python
# coding: utf-8
# filename: data.py
##################################
# @author yinxj
# @date 2013-07-10

import logging
import os
import sys
import commands
import threading
import subprocess
import re
import datetime
import time
import dbOperator

class Data:
    def __init__(self,cubeName,ldap,product,type,logger):
        self.cubeName=cubeName
        self.ldap = ldap
        self.product = product
        self.type = type
        self.logger=logger
        self.logFiles=None
        self.gcLogFiles=None
        self.commandKeys=None
        self.processes=None

    def validate(self):
        keys={}
        for logFile in self.logFiles+self.gcLogFiles:
            for key in logFile.numberKeys+logFile.stringKeys:
                if keys.has_key(key):
                    self.logger.warning('%s: key=%s conflict @ %s and %s' % (self.cubeName, key, keys[key],logFile.filePattern))
                else:
                    keys[key]=logFile.filePattern
        for process in self.processes:
            for key in process.keys:
                if keys.has_key(key):
                    self.logger.warning('%s: key=%s conflict @ %s and %s' % (self.cubeName, key, keys[key],','.join(process.alias)))
                else:
                    keys[key]=','.join(process.alias)
        for commandKey in self.commandKeys:
            if keys.has_key(commandKey.key):
                self.logger.warning('%s: key=%s conflict @ %s and %s' % (self.cubeName, key, keys[key],key))
            else:
                keys[commandKey.key]=[commandKey.key]
        return True

    def generateProcessKeys( self ):
        if len( self.processMonitorItems ) == 0 or len( self.processes ) == 0:
            return
        for process in self.processes:
            allprocessnames = []
            allprocessnames.extend( process.alias )
            allprocessnames.append( "otheruser" )
            for alias in allprocessnames:
                for item in self.processMonitorItems:
                    self.processKeys.append( alias + '.p' + item )

    # 入口
    def monitor(self):
        self.monitorAll(self.logFiles)
        self.monitorAll(self.gcLogFiles)
        self.monitorAll(self.processes)
        self.monitorAll(self.commandKeys)

    def monitorAll(self,objects):
        if not objects:
            return
        for object in objects:
            threading.Thread(target=object.monitor).start()

