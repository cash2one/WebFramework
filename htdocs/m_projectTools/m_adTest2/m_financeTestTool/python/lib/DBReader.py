#!/usr/bin/python

import sys
import MySQLdb
import cPickle as pickle

reload(sys)
sys.setdefaultencoding('utf-8')

from GlobConfReader import *
from SchemaReader import *

class DBReader:

    def __init__(self, conf_file, schema_dir, output_file):
        self.conf_file = conf_file
        self.schema_dir = schema_dir
        self.output_file = output_file

    def read(self):
        confReader = GlobConfReader(self.conf_file)
        confReader.read()
        
        db_conn_dict = {}
        for key, db_conn_str in confReader.get_db_conf_dict().items():
            host, dbname, port, user, passwd = map(lambda x: x.strip(), db_conn_str.split(":"))
            db_conn_dict[key] = MySQLdb.connect(host=host, user=user, passwd=passwd, db=dbname, charset="utf8")

        data_results = []
        for schema_name in confReader.get_schema_list():
            key_name, table_name = schema_name.strip().split("_", 1)

            schema_file = "%s/%s.schema" % (self.schema_dir, schema_name)
            schemaReader = SchemaReader(schema_file)
            schemaReader.read()

            data_results.append((table_name, {}))
            sql_cmd = "select %s from %s" % (schemaReader.get_field_list_str(), table_name)
            conn = db_conn_dict[key_name]
            cursor = conn.cursor()
            cursor.execute(sql_cmd)
            for fields in cursor.fetchall():
                temp_list = map(lambda x: str(x), fields)
                primary_key_value = schemaReader.get_primary_key_value(temp_list)
                data_results[-1][1][primary_key_value] = temp_list

        file = open(self.output_file, "w")
        pickle.dump(data_results, file)
        file.close()


if __name__ == "__main__":
    dbReader = DBReader(sys.argv[1], sys.argv[2], sys.argv[3])
    dbReader.read()
