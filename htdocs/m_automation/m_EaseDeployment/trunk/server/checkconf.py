#!/usr/bin/python2.4 -u
#encoding=utf-8

import string
import ConfigParser
import inspect
import getopt
import os
import re
import sys
import commands
import time
import urllib
import log
reload(sys)
sys.setdefaultencoding('utf-8')

class checkconf:
 def __init__(self,conf_path):
  self.conf_path = conf_path
#  self.LOG = log.LOG("checkconf")

 def __del__(self):
  pass

 def check(self):
  '''check variable and all step of the conf '''
  self.log_checkinfo("start check conf")
  if not self.check_variable():
   self.log_checkinfo("变量定义错误")
   return False
  if not self.check_step():
   self.log_checkinfo("步骤定义错误")
   return False
  self.log_checkinfo("检查配置文件结束，配置文件定义正确")
  return True
  
 
 def is_line(self,line):
  ''' check if the line is not annotation and empty line'''
  line = line.strip()
  if not line:
   return False
  if line[0] == '#': 
   return False
  return True

 def check_variable(self): 
  ''' check variable define,
    if variable define is right return true,
    else return false '''
  try:
   f = open(self.conf_path,'r+');
   for line in f.read().splitlines():
    line = line.strip()
    if self.is_line(line) == False:
     continue
    if "=>" not in line:
     continue  
    #get varibale  and value
    key ,value = re.split('\s*=>\s*',line,1)
    
    #judge if key is none
    if(not key):
        self.log_checkinfo('变量必须包含名字')
        self.log_checkinfo(line)
        return False
    #judge  if varibale  start with $ and end with $
    if key[0] !='$' or key[-1] != '$':
        self.log_checkinfo('变量名必须以$开始，以$结束')
        self.log_checkinfo(line)
        return False
    # judge if varibale name only has $
    if len(key)==1:
        self.log_checkinfo('变量名字为空')
        self.log_checkinfo(line)
        return false 
    #judge if value is none
    if not value:
        self.log_checkinfo('变量值为空')
        self.log_checkinfo(line)
        return False
    #judge if value has [python:] or [shell:] 
    if value[0] == '[' and value[-1] == ']':
        if re.match("\[python:|\[shell:",value) is None:
            self.log_checkinfo('如果变量值以[开始，以]结束，那么[]之间的值必须以python: 或者shell:作为开始')
            self.log_checkinfo(line)
            return False
    elif value[0] == '[' and value[-1] !=']':
        self.log_checkinfo('变量值以[开始，但是没有以]结束')
        self.log_checkinfo(line)
   return True
  except Exception, e:
   self.log_error(" 检查变量时发生异常: %s" % e)
   return False
 
 def check_step(self):
  ''' check  step  define  include step check,collect '''
  try:
   flag = False #used to get if  { } is match
   stepdict = {}  # store step content
   linelist = open(self.conf_path,'r+').readlines()
   for i in range(len(linelist)):
    line = linelist[i].strip()
    if self.is_line(line) == False:
      continue
    if line[0] == '{': #step start
     if flag == True:
      self.log_checkinfo(' { } 不匹配,步骤或者集合定义中包含两个连续的 { { ')
      self.log_checkinfo('出错的行号=%s' % (i+1) )   
      return False
     else:
      flag = True
      continue
    elif line[0] == '}': # step end 
     if flag == False:
      self.log_checkinfo('{}不匹配, 步骤或者集合定义中包含两个连续的 }}')
      self.log_checkinfo('出错的行号=%s' % (i+1))
      return False
     else:
      flag = False 
      result = self.check_stepcontent(stepdict)
      stepdict.clear() # clear step content
      if result == False: 
       return result
      else:
       continue
    else: # process line not begin with { or } 
     if (flag == True) and (re.match('step\.|collect\.',line) is not None): # judge if { is appear
     #if flag == True: # judge if { is appear
      key, value = re.split('\s*:\s*',line,1)
      if not key:
       self.log_checkinfo('步骤定义缺少名字')
       self.log_checkinfo(line)
       return False
      # permit step could has no value
      #if not value:  
      # self.log_checkinfo('this content don\'t has value')
      # self.log_checkinfo(line)
      # return False
      if stepdict.__contains__(key): # check if in one step exist the same step name
       self.log_checkinfo("步骤或者集合的名字重复，请检查是否存在重复定义")
       self.log_checkinfo(line)
       return False
      stepdict[key] = value
     else:  #if { is not appear then continue
      continue
  except Exception, e:
   self.log_error("检查步骤或者集合定义时产生异常: %s" % e)
   return False
  return True

 def check_stepcontent(self,stepdict):
  ''' check step content define'''
  try:
   namespace = ('name','desc','cmd','cmd.sep','ignore_fail','check_before_run','check_after_run','hostname','exclude')
   temp = '-1'
   flagname = 0  #if step is not content name ,then return false
   flagcmd = 0 # if step is not content cmd.then return false
   for line in stepdict.items():
    #judge if step line start with step. check. collect.
    m = re.match('step\.|check\.|collect\.',line[0])
    if m is None:
     print flagshell
     self.log_checkinfo('步骤或者集合定义必须以 step.  check.  collect.作为开始')
     self.log_checkinfo(line) 
     return False
    if m.group() == temp or temp == '-1':
     temp = m.group()
    else:
     self.log_checkinfo('单个步骤或者集合的开始必须相同')
     self.log_checkinfo(temp)
     self.log_checkinfo(m.group())
     return False
    # judge value is define right if value defined [] 
    value = line[1]
    if  (value) and (value[0] == '['):
     value1,value2 = re.split('\s*:\s*',value,1)
     if not value1: 
      self.log_checkinfo('定义错误： %s' % value)
      return False
    # if not value2:
     # self.log_checkinfo('this define is not right %s' % value)
     # return False
     if value1[-1] !=']':
      self.log_checkinfo("定义错误： %s" % value)
      return False
    #judge if step has define name and cmd  and all name is unique
    steptype,name = re.split('\.',line[0],1)
    
    if name =='name':
     flagname = 1
    if name =='cmd':
     flagcmd = 1
    #judge if step name is in the namespace
    if not namespace.__contains__(name):
     self.log_checkinfo('步骤或者集合的属性在命令空间中不存在')
     self.log_checkinfo(line)
    # self.log_checkinfo(name)
     self.log_checkinfo('命名空间='+str(namespace))
     return False

   if flagname == 0:
    self.log_checkinfo('步骤或者集合没有定义名称')
    self.log_checkinfo(stepdict)
    return False
   if flagcmd == 0:
    self.log_checkinfo('步骤或者集合没有定义命令')
    self.log_checkinfo(stepdict)
    return False
  except Exception, e:
   self.log_error("检查步骤内容时产生异常: %s" % e)
   return False
  return True
     
 def log_checkinfo(self,msg):
  ''' log checkinfo method'''
 # self.LOG.info(msg)
  log.info("checkconfinfo",msg.__str__())
  #print msg

 def log_error(self,msg):
  ''' log error method '''
  #self.LOG.error(msg)
  log.error("checkconfinfo",msg.__str__())
  #print msg

if __name__ == "__main__":
 mycheck = checkconf('odfs.conf')
 mycheck.check_step()
