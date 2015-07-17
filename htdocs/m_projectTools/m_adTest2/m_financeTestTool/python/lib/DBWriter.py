#!/usr/bin/python

import sys
import MySQLdb
import cPickle as pickle

from GlobConfReader import *
from SchemaReader import *

class DBWriter:

    def __init__(self, conf_file, schema_dir, data_file):
        self.conf_file = conf_file
        self.schema_dir = schema_dir
        self.data_file = data_file

    def write(self):
        confReader = GlobConfReader(self.conf_file)
        confReader.read()
        
        db_conn_dict = {}
        for key, db_conn_str in confReader.get_db_conf_dict().items():
            host, dbname, port, user, passwd = map(lambda x: x.strip(), db_conn_str.split(":"))
            db_conn_dict[key] = MySQLdb.connect(host=host, user=user, passwd=passwd, db=dbname, charset="utf8")

        schema_list = confReader.get_schema_list()

        table_data_list = pickle.load(open(self.data_file))
        for idx, table_info in enumerate(table_data_list):
            schema_name = schema_list[idx]
            prod_type_str, table_name = schema_name.split("_", 1)
            schema_file = "%s/%s.schema" % (self.schema_dir, schema_name)

            conn = db_conn_dict[prod_type_str]

            schemaReader = SchemaReader(schema_file, conn)
            schemaReader.read()
            default_value_set = schemaReader.get_default_value_set()
            if default_value_set != None:
                def_key_str, def_value_str = map(lambda x: x.strip(), default_value_set.split(":", 1))
            else:
                def_key_str, def_value_str = None, None

            cursor = conn.cursor()
            table_name, data_dict = table_info

            sql_del_cmd = "delete from %s" % (table_name)
            cursor.execute(sql_del_cmd)

            for key, row_data_list in data_dict.items():
                row_data_list = schemaReader.get_insert_row(row_data_list)
                if def_value_str == None:
                    sql_cmd = "insert into %s (%s) values (%s)" % (table_name, schemaReader.get_field_list_str(), ",".join(row_data_list))
                else:
                    sql_cmd = "insert into %s (%s) values (%s)" % (table_name, schemaReader.get_field_list_str() + "," + def_key_str, ",".join(row_data_list) + "," + def_value_str)
                cursor.execute(sql_cmd)

            conn.commit()
            cursor.close()

if __name__ == "__main__":
    dbWriter = DBWriter(sys.argv[1], sys.argv[2], sys.argv[3])
    dbWriter.write()
