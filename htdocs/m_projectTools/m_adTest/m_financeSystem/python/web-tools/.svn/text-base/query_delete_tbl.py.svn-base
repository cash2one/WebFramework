#!/usr/bin/python

import sys
sys.path.append('.')

from TableList import *

for tableObj in TableList.tables:
    if tableObj.deletable == True:
        print "<span class='delete_table'><input type='checkbox' name='%s' />%s <br></span>" % (tableObj.table_name, tableObj.table_name)
    print "<span class='query_table'><input type='checkbox' name='%s' />%s <br></span>" % (tableObj.table_name, tableObj.table_name)
