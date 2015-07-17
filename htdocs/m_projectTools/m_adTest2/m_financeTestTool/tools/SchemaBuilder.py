#!/usr/bin/python

import MySQLdb

class SchemaBuilder:
    def __init__(self, mysql_conn, name, output_dir):
        self.mysql_conn = mysql_conn
        self.name = name
        self.output_dir = output_dir

    def build(self, table_names):
        for table_name in table_names:
            self._build(table_name)

        self.mysql_conn.close()

    def _build(self, table_name):
        output_file = "%s/%s_%s.schema" % (self.output_dir, self.name, table_name)
        primary_key = None
        field_list = []

        sql_str = "desc %s" % table_name
        cursor = self.mysql_conn.cursor()
        cursor.execute(sql_str)

        for fields in cursor.fetchall():
            field_name = str(fields[0])
            if "PRI" == str(fields[3]):
                primary_key = field_name
            field_list.append(field_name)
        cursor.close()
        
        output_lines = []
        output_lines.append("[_table_]")
        output_lines.append("name = ")
        output_lines.append("primary_key = %s" % primary_key)
        output_lines.append("field_list = %s" % ",".join(field_list))
        output_lines.append("")

        for field_name in field_list:
            output_lines.append("[%s]" % field_name)
            output_lines.append("name = ")
            output_lines.append("readable_map = ")
            output_lines.append("")

        open(output_file, "w").writelines(map(lambda x: x + "\n", output_lines[:-1]))

if __name__ == "__main__":
    #conn = MySQLdb.connect(host="tb081", user="test", passwd="test", db="financeDB", charset="utf8")
    #schemaBuilder  = SchemaBuilder(conn, "fin", "../schema")
    #schemaBuilder.build(["AGENT_ACCOUNT_HISTORY", "AGENT_BALANCE", "EXTRA_REVENUE", "EXTRA_REVENUE_DETAIL", "SPONSOR_ACCOUNT_HISTORY"])

    conn = MySQLdb.connect(host="tb081", user="test", passwd="test", db="adupdateDB", charset="utf8")
    schemaBuilder  = SchemaBuilder(conn, "update", "../schema")
    schemaBuilder.build(["SPONSOR_BALANCE_CHANGE"])
