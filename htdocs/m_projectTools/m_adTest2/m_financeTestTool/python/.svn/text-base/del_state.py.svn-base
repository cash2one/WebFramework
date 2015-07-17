#!/usr/bin/python

from lib import *
import sys
import os
import md5

conf_file, state_name = sys.argv[1:3]
schema_dir = "../schema"
results_root_dir = "../results"

dir_name, file_name = os.path.split(conf_file)
file_main, file_suffix_name = os.path.splitext(file_name)
results_dir = "../results/%s" % file_main

md5_val = md5.md5(state_name).hexdigest()
state_file = "%s/%s" % (results_dir, md5_val)
if not os.path.exists(state_file):
    print "1Error: state(%s) NOT exists" % state_name
    sys.exit(1)

os.unlink(state_file)
print "0Info: state(%s) deleted" % state_name
