#!/usr/bin/python

import sys
sys.path.append('.')
import os

from Util import *

if len(sys.argv) != 2 or not os.path.exists(sys.argv[1]):
    raise Exception, "%s data_file" % sys.argv[0]

TableListUtil.write_tables(sys.argv[1])
