#!/usr/bin/python

import ConfigParser
import time
import os

class SchemaReader:
    def __init__(self, schema_file, conn_obj = None):
        self.schema_file = schema_file
        self.conn_obj = conn_obj
        self.table_info_dict = {}
        self.field_dict = {}
        self.field_type_dict = {}

        self._get_field_type_dict()

    def _get_field_type_dict(self):
        if self.conn_obj == None:
            return

        file_name = os.path.split(self.schema_file)[1]
        file_main = file_name.split(".")[0]
        table_name = file_main.split("_", 1)[1]

        cursor = self.conn_obj.cursor()
        sql_cmd = "desc %s" % table_name
        cursor.execute(sql_cmd)
        for fields in cursor.fetchall():
            field_name = str(fields[0])
            field_type = str(fields[1])
            self.field_type_dict[field_name] = field_type

    def read(self):
        cf = ConfigParser.ConfigParser()
        cf.read(self.schema_file)

        for section in cf.sections():
            if section == "_table_":
                for key, value in cf.items(section):
                    if key == "field_list":
                        self.table_info_dict[key] = map(lambda x: x.strip(), value.split(","))
                    else:
                        self.table_info_dict[key] = value.strip()
            else:
                self.field_dict[section] = {}
                for key, value in cf.items(section):
                    if key == "readable_map":
                        temp_dict = {}
                        if "|" not in value:
                            continue
                        fields = value.split("|")
                        for field in fields:
                            key2, val2 = field.split(":", 1)
                            temp_dict[key2.strip()] = val2.strip()
                        self.field_dict[section][key] = temp_dict

                    else:
                        self.field_dict[section][key.strip()] = value.strip()

    def get_field_dict(self):
        return self.field_dict

    def get_table_name(self):
        return self.table_info_dict.get("name")

    def get_key_index(self):
        key_name = self.table_info_dict.get("primary_key")
        return self.table_info_dict.get("field_list").index(key_name)

    def get_field_name_list(self):
        ret_list = []
        for field_name in self.table_info_dict.get("field_list"):
            field_name_bak = field_name
            if self.field_dict.has_key(field_name):
                field_name = self.field_dict.get(field_name).get("name", field_name)
                if field_name.strip() == "":
                    field_name = field_name_bak
            ret_list.append(field_name)
        return ret_list

    def get_field_list_str(self):
        return ",".join(self.table_info_dict.get("field_list"))

    def get_row2(self, data_list):
        ret_list = []
        for index, field_value in enumerate(data_list):
            field_name = self.table_info_dict.get("field_list")[index]
            if self.field_dict.has_key(field_name):
                if self.field_dict.get(field_name).has_key("readable_map"):
                    field_value = self.field_dict[field_name]["readable_map"].get(field_value, field_value)
                elif self.field_dict.get(field_name).has_key("readable_func"):
                    func_name = self.field_dict[field_name]["readable_func"]
                    if func_name == "readable_time":
                        field_value = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime(float(field_value) / 1000.0))
                    
            ret_list.append(field_value)

        return ret_list

    def get_primary_key_value(self, data_list):
        primary_key_index = self.get_key_index()
        return data_list[primary_key_index]

    def get_insert_row(self, row_data):
        if not self.field_type_dict:
            raise "Error: You must specify the connection when using the constructor function"

        field_list = self.table_info_dict.get("field_list")
        for idx, field_name in enumerate(field_list):
            field_type_str = self.field_type_dict[field_name]
            if row_data[idx] == "None":
                row_data[idx] = "null"
            elif "varchar" in field_type_str:
                row_data[idx] = "'" + row_data[idx] + "'"
        return row_data

    def get_default_value_set(self):
        return self.table_info_dict.get("default_insert_value_set", None)

    def get_fields_count(self):
        return len(self.table_info_dict.get("field_list"))
        

if __name__ == "__main__":
    import sys
    sReader = SchemaReader(sys.argv[1])
    sReader.read()
