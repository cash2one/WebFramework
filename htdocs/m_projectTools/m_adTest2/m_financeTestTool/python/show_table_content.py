#!/usr/bin/python

from lib import *
import sys

conf_file = sys.argv[1]
schema_dir = sys.argv[2]
schema_list_str = sys.argv[3]

confReader = GlobConfReader(conf_file)
confReader.read()

db_conn_dict = {}
for key, db_conn_str in confReader.get_db_conf_dict().items():
    host, dbname, port, user, passwd = db_conn_str.split(":")
    db_conn_dict[key] = MySQLdb.connect(host=host, user=user, passwd=passwd, db=dbname, charset="utf8")

schema_list = schema_list_str.split(",")

output_list = []

for schema_name in schema_list:
    output_list.append("<table border=1'>")

    key_name, table_name = schema_name.split("_", 1)
    schema_file = "%s/%s.schema" % (schema_dir, schema_name)
    schemaReader = SchemaReader(schema_file)
    schemaReader.read()

    field_name_list = schemaReader.get_field_name_list() 
    output_list.append("<thead><tr><th colspan=" + str(schemaReader.get_fields_count()) + ">" + table_name + " (" + schemaReader.get_table_name() + ")" + "</th></tr></thead>")
    output_list.append("<thead><tr><th>" + "</th><th>".join(field_name_list) + "</th></tr></thread>")

    sql_cmd = "select %s from %s" % (schemaReader.get_field_list_str(), table_name)
    conn = db_conn_dict[key_name]
    cursor = conn.cursor()
    cursor.execute(sql_cmd)

    for fields in cursor.fetchall():
        temp_list = map(lambda x: str(x), fields)
        temp_list = schemaReader.get_row2(temp_list)
        temp_list = map(lambda x: x.replace("&", "&amp;"), temp_list)
        temp_list = map(lambda x: x.replace(">", "&gt;"), temp_list)
        temp_list = map(lambda x: x.replace("<", "&lt;"), temp_list)
        temp_list = map(lambda x: x.replace("None", ""), temp_list)
        output_list.append("<tr><td>" + "</td><td>".join(temp_list) + "</td></tr>")

    output_list.append("</table>")
    output_list.append("<br>")

print "\n".join(output_list)
