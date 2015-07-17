#!/usr/bin/python
#encoding:utf-8

import os
import time
import sys
import glob

if len(sys.argv[1:]) != 1:
    print "Usage: %s desc" % sys.argv[0]

desc = sys.argv[1]
str_pattern = "./table_data/case_*.%s" % desc
files = glob.glob(str_pattern)

if len(files) == 0:
    sys.exit(1)

file = files[0] 
os.system("./TestDriver.py delete")
os.system("./TestDriver.py write %s" % file)
print "文件(%s)内容已导到数据库" % file
