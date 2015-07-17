#!/usr/bin/python
# coding: utf-8
# filename: logFile.py
##################################
# @author yinxj
# @date 2013-07-11

import commands
import time

class Alarmer:
    phone=''
    email=''
    maxAlarmInterval=300
    def __init__(self,threshold,factor,logger):
        self.threshold=threshold
        self.factor=factor
        self.logger=logger
        self.min=None
        self.max=None
        self.lastAlarmTime=-1

    def alarm(self,value,cubeName,key):
        self.logger.debug('value=%s,cubeName=%s,key=%s,alarmer=%s' % (value,cubeName,key,self.toString()))
        message=None
        if self.factor=='+':
            if value>self.threshold:
                message='%s:%s=%s>threshold=%s, alarm from collect' % (cubeName,key,value,self.threshold)
        elif self.factor=='-':
            if value<self.threshold:
                message='%s:%s=%s<threshold=%s, alarm from collect' % (cubeName,key,value,self.threshold)
        elif self.factor=='++':
            # 第一次, 仅做初始化的工作, 不报警
            if not self.min:
                self.min=value
                self.max=value
                return
            if value<=self.max and value>=self.min:
                return
            if value<self.min:
                if value*self.threshold<self.min:
                    message='%s:%s=%s<min(%s)/threshold(%s), alarm from collect' % (cubeName,key,value,self.min,self.threshold)
                self.min=value
            elif value>self.max:
                if self.max*self.threshold<value:
                    message='%s:%s=%s>max(%s)*threshold(%s), alarm from collect' % (cubeName,key,value,self.max,self.threshold)
                self.max=value
        if message:
            t=time.time()
            if self.factor == '+' or self.factor == '-':
                if t-self.lastAlarmTime<self.maxAlarmInterval:
                    self.logger.warning('距离上次报警时间(%s)为(%s)s<%ss, 取消本次报警: %s' % (self.lastAlarmTime,t-self.lastAlarmTime,self.maxAlarmInterval,message))
                    return
            self.lastAlarmTime=t
            self.sendSMS(message)
            self.sendMail(message)

    def toString(self):
        return '[threshold=%s,factor=%s,min=%s,max=%s]' % (self.threshold,self.factor,self.min,self.max)

    def sendSMS(self,message):
        if self.phone[0]!='1':
            self.logger.warning('phone not set, sms not sent')
            return
        commands.getoutput('wget "http://sms.corp.youdao.com:8080/sms?phonenumber=%s&message=%s"' % (self.phone,message))
        self.logger.warning('message=%s, sms sent' % message)

    def sendMail(self,message):
        emailCmd='python /usr/local/bin/emailer.py -t %s -s "alarm from collect" -c "%s" -f %s' % (self.email,message,self.email)
        commands.getoutput(emailCmd)
        self.logger.warning('message=%s, email sent' % message)

