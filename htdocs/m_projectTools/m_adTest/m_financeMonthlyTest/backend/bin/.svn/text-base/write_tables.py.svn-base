#!/usr/bin/python

import os
import sys
sys.path.append("./")

from MySqlAdapter import *

if len(sys.argv) != 2 or not os.path.exists(sys.argv[1]):
    raise Exception, "Need an existing valid file as param"

MySqlAdapter.write_mysql(sys.argv[1])
