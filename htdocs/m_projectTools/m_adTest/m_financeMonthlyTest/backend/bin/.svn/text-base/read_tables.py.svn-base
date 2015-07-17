#!/usr/bin/python

import os
import sys
sys.path.append("./")

from MySqlAdapter import *

if len(sys.argv) != 2:
    raise Exception, "Need a file as param"

MySqlAdapter.read_data_from_mysql(sys.argv[1])
