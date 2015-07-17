#!/usr/bin/python

import struct
import re
import MySQLdb

def get_config_list(conf_file):
    host = ""
    user = ""
    passwd = ""
    dbname = ""
    port = 3306
    for line in open(conf_file):
        line = line.strip()
        if not line or line[0] == "#":
            continue

        key, val = line.split("=", 1)
        if key == "hibernate.connection.write_finance.url":
            fields = re.split("[/\?]", val)
            host = fields[2]
            dbname = fields[3]

        elif key == "hibernate.connection.write_finance.username":
            user = val
        
        elif key == "hibernate.connection.write_finance.password":
            passwd = val 
            
    return "tb081", "test", "test", "finance_db", 3306
    # return host, user, passwd, dbname, port
    

if __name__ == "__main__":
    host, user, passwd, dbname, port = get_config_list("./jar/hibernate.properties")
    conn = MySQLdb.connect(host=host, user=user, passwd=passwd, db=dbname, port=int(port), charset="utf8")
    cursor = conn.cursor()

    for tablename in ("AGENT_REVENUE_MONTHLY", "SPONSOR_REVENUE_MONTHLY", "FILE_RECORD", "REVENUE_SUMMARY"):
    # for tablename in ("FILE_RECORD", "REVENUE_SUMMARY"):
        cursor.execute("truncate table %s" % tablename)
        conn.commit()
        print "table %s truncated" % tablename

    cursor.close()
    conn.close()
