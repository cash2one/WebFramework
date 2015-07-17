#!/usr/bin/python

from lib import *
import sys
import os
import md5

conf_file, state_name = sys.argv[1:3]
schema_dir = "../schema"

dir_name, file_name = os.path.split(conf_file)
file_main, file_suffix_name = os.path.splitext(file_name)
results_dir = "../results/%s" % file_main
results_index_file = "%s.index" % results_dir

md5_val = md5.md5(state_name).hexdigest()
lines = open(results_index_file).read().splitlines()
for line in lines:
    if md5_val in line:
        break
else:
    open(results_index_file, "a").write("%s%s\n" % (md5_val, state_name))

state_file = "%s/%s" % (results_dir, md5_val)
if os.path.exists(state_file):
    print "1Error: state(%s) alreay exists" % state_name
    sys.exit(1)

dbReader = DBReader(conf_file, schema_dir, state_file)
dbReader.read()
