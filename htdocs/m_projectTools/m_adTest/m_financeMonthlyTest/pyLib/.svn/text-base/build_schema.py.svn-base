#!/usr/bin/python
#encoding: utf-8

import sys
reload(sys)
sys.setdefaultencoding('utf-8')

from DBConnection import *
from Type import *

def get_name(table_name):
    tList = list(table_name.lower())
    for idx, ch in enumerate(tList):
        if idx == 0:
            tList[idx] = ch.upper()
        elif tList[idx - 1] == "_":
            tList[idx] = ch.upper()
            
    return "".join(tList).replace("_", "")

head_str = "    "
output_str = ""
for table_name, conn in DBConnection.table_conn_dict.items():
    table_desc = DBConnection.table_desc_dict.get(table_name, "")
    output_str += "class %sSchema:\n" % get_name(table_name)
    output_str += "%stable_name = \"%s\"\n" % (head_str, table_name)
    output_str += "%stable_desc = \"%s\"\n" % (head_str, table_desc)
    output_str += "%sschema_fields= [\n" % head_str

    cursor = conn.cursor()
    cmd = "desc %s" % table_name

    cursor.execute(cmd)
    results = cursor.fetchall()
    cursor.close()

    for slist in results:
        name  = slist[0]
        type  = slist[1].split("(")[0]
        value = slist[4]
        if value == "":
            value = None
        if type == Type.timestamp:
            output_str += "%s%sField(\"%s\", Type.%s, '%s'),\n" % (head_str, head_str, name, type, value)
        else:
            output_str += "%s%sField(\"%s\", Type.%s, %s),\n" % (head_str, head_str, name, type, value)
    
    output_str += "%s]\n" % head_str
    output_str += "\n"

print output_str
