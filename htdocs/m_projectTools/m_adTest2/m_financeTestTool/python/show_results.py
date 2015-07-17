#!/usr/bin/python

from lib import *
import sys
import os
import md5

conf_file, state_name1, state_name2 = sys.argv[1:4]
schema_dir = "../schema"

dir_name, file_name = os.path.split(conf_file)
file_main, file_suffix_name = os.path.splitext(file_name)
results_dir = "../results/%s" % file_main
results_index_file = "%s.index" % results_dir

md5_val1 = md5.md5(state_name1).hexdigest()
state_file1 = "%s/%s" % (results_dir, md5_val1)
md5_val2 = md5.md5(state_name2).hexdigest()
state_file2 = "%s/%s" % (results_dir, md5_val2)
resultCheck = WebPageBuilder(conf_file, schema_dir, state_file1, state_file2)
resultCheck.check()
