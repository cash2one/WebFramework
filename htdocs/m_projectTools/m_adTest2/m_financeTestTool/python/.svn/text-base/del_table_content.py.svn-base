#!/usr/bin/python

from lib import *
import sys

conf_file = sys.argv[1]
schema_list_str = sys.argv[2]

confReader = GlobConfReader(conf_file)
confReader.read()

db_conn_dict = {}
for key, db_conn_str in confReader.get_db_conf_dict().items():
    host, dbname, port, user, passwd = db_conn_str.split(":")
    db_conn_dict[key] = MySQLdb.connect(host=host, user=user, passwd=passwd, db=dbname, charset="utf8")

schema_list = schema_list_str.split(",")

for schema_name in schema_list:
    key_name, table_name = schema_name.split("_", 1)
    sql_cmd = "delete from %s" % table_name
    conn = db_conn_dict[key_name]
    cursor = conn.cursor()
    cursor.execute(sql_cmd)
    conn.commit()

print "data in table(s) are deleted."
