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

file = ""
if len(files) == 0:
    timestamp = int(time.time())
    file = "./table_data/case_%s.%s" % (timestamp, desc)
else:
   file = files[0] 

os.system("./TestDriver.py read %s" % file)
print "Data saved into file: ./table_data/%s" % file

html_file = "../html_data/%s.html" % os.path.basename(file)
os.system("./TestDriver.py output_html %s %s" % (file, html_file))
print "Html file is saved as %s" % html_file
