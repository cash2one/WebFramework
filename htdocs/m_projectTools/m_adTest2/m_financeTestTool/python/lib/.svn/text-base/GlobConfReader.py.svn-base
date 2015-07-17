#!/usr/bin/python

import ConfigParser

class GlobConfReader:
    def __init__(self, conf_file):
        self.conf_file = conf_file
        self.db_conf_dict = {}
        self.schema_list = []

    def read(self):
        cf = ConfigParser.ConfigParser()
        cf.read(self.conf_file)

        for db_name, value in cf.items("db_conf"):
            self.db_conf_dict[db_name] = value.strip()

        schema_list_str = cf.get("table_info", "schema_list")
        self.schema_list = map(lambda x: x.strip(), schema_list_str.split(","))

    def get_db_conf_dict(self):
        return self.db_conf_dict

    def get_schema_list(self):
        return self.schema_list


if __name__ == "__main__":
    import sys
    gConfReader = GlobConfReader(sys.argv[1])
    gConfReader.read()
