#!/usr/bin/python

from lib import *
import sys
import os
import md5
import glob

conf_file = sys.argv[1]
schema_dir = "../schema"
results_root_dir = "../results"

dir_name, file_name = os.path.split(conf_file)
file_main, file_suffix_name = os.path.splitext(file_name)
results_dir = "../results/%s" % file_main

for state_file in glob.glob("%s/*" % results_dir):
    os.unlink(state_file)

print "0Info: state(%s) deleted" % state_name
