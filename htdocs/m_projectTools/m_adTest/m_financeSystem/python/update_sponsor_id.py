#!/usr/bin/python
#encoding:utf-8

from TableList import *

import sys
reload(sys)
sys.setdefaultencoding('utf-8')

def update_sponsor_id(old_sponsor_id, new_sponsor_id):
    for tableObj in TableList.tables:
        if tableObj.writable == False:
            print "Ignore table %s" % tableObj.table_name
            continue;

        print "Update table %s" % tableObj.table_name
        conn = tableObj.get_conn()
        cursor = conn.cursor()
        sql_cmd = "delete from %s where %s=%s" % (tableObj.table_name, tableObj.query_keyname, new_sponsor_id)
        cursor.execute(sql_cmd)
        sql_cmd = "update %s set %s=%s where %s=%s" % (tableObj.table_name, tableObj.query_keyname, new_sponsor_id, tableObj.query_keyname, old_sponsor_id)
        cursor.execute(sql_cmd)
        conn.commit()
        cursor.close()

if __name__ == "__main__":
    update_sponsor_id(64, 1)
