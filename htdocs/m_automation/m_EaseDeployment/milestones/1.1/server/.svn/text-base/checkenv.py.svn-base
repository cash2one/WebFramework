#!/usr/bin/python
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

class checkenv:
 def __init__(self):
  ''' construct'''
#  self.LOG = log.LOG("checkenv")

# def __del__(self):
 
 def display_cpu(self):
  '''display the utilization  ratio of cpu and process   '''
  output = commands.getoutput('top -n 3|grep "Cpu"')
  self.logcheckinfo(output)
  output = commands.getoutput('ps aux')
  self.logcheckinfo(output)
 
 def display_load(self):
  '''display load '''
  output = commands.getoutput('uptime')
  self.logcheckinfo(output)

 def display_mem(self):
  '''display memory '''
  output = commands.getoutput('free') 
  self.logcheckinfo(output)

 def display_io(self): 
  '''display io'''
  output = commands.getoutput('iostat')
  self.logcheckinfo(output)

 def display_disk(self,path):
  '''display disk of path'''
  output = commands.getoutput('df -h ' + path) 
  self.logcheckinfo(output)

 def check_port_isused(self, port):
  '''check port is used, if the port is used,return 1 ,else return 0'''
  output = commands.getoutput('netstat -l | grep :%d' %  int(port) )
  self.logcheckinfo(output)
  if output!="":
   return 1
  else:
   return 0

 def check_disk(self,path,disk_size):
  '''check disk needed, 
   if computer has the needed disk,return 1, else return 0
   the metric of disk_size is G'''
  output = commands.getoutput('df -h ' + path + '|grep ' + path + '|awk \'{print $3} \'' )
  free = int(output[0:-1])
  self.logcheckinfo('system has %s disk free' % free)
  if free >= int(disk_size) :
   self.logcheckinfo('the disk you need is %s ,which is less than %s' % (disk_size,free))
   return 1
  else : 
   self.logcheckinfo('the disk you need is %s ,which is more than %s' % (disk_size,free))
   return 0  

 def check_mem(self,mem_size):
  '''check memory needed,
    if  computer has the  memory you need return 1 ,else return return 0
    the metric of mem_size is K '''
  output = commands.getoutput('free|grep \'buffers/cache\' |awk \'{print $4}\'')
  self.logcheckinfo('system has %s memory free' % output)
  free = int(output)
  if free >= int(mem_size) :
   self.logcheckinfo('the memory you need is %s, which is less than %s' % (mem_size ,output))
   return 1
  else :
   self.logcheckinfo('the memory you need is %s ,which is more than %s' % (mem_size, output))
   return 0

 def check_webpage_contain(self,url, content):
  '''check url contain content '''
  try:
   rs = urllib.urlopen(url)
   content_type =  rs.headers.dict['content-type']
   start_num = content_type.rfind('charset=')
   encoding = content_type[start_num + 8:]
   print encoding
   url_source = rs.read().decode(encoding)
  # print url_source
   if url_source.find(content)>=0:
    return 1
   else:
    return 0 
  except Exception, e:
   self.log_Excption(e.__str__())

 def check_webpage_not_contain(self,url,content):
  '''check url not contain content '''
  result = self.check_webpage_contain(url,content)
  if result ==1:
   return 0
  else:
   return 1

 def check_file_exist(self,filepath):
  '''check file exist'''
  return os.path.isfile(filepath)

 def check_dir_exist(self, path):
  '''check directory exist'''
  return os.path.exists(path)

 def check_pname_exist(self,pname):
  '''check process name exist'''
  output = commands.getoutput('ps aux')
  self.logcheckinfo(output)
  restr = re.compile(pname)
  matchsize = len(restr.findall(output))
  print 'matchsize = %d' % matchsize
  if matchsize > 0:
   return 1
  else:
   return 0
 
 def check_file_content(self,filepath,content):
  '''check file contain content'''
  output = commands.getoutput('cat ' + filepath + '| grep ' + content)
  print output
  if output != "":
   return 1
  else:
   return 0

 def check_file_not_content(self,filepath,content):
  '''check config not contain content'''
  result = self.check_file_content(filepath,content)
  if result ==1:
   return 0
  else:
   return 1
 
 def logcheckinfo(self,result):
  '''display check result infomation'''
 # LOG.info(result)
  log.info("checkenvinfo",result.__str__())
  #print result
 
 def log_Excption(self,result):
  ''' display excption '''
  log.error("checkevninfo",result.__str__())
  #LOG.error(result)
  # print result
if __name__ == "__main__":
 mycheck = checkenv()
 print  mycheck.check_file_not_content('/disk2/guojing/common/db.py','cur1111sor')

