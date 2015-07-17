#!/usr/bin/python
#encoding:utf-8

from TableList import *
from FileAdapter import *

import sys
reload(sys)
sys.setdefaultencoding('utf-8')

def get_insert_str(row):
    ret_fields = []

    for field in row:
        key  = field.name
        type = field.type
        value = field.get_sql_cmd_value()
        ret_fields.append(str(value)) ### str(value),否则会抛异常：UnicodeEncodeError: 'latin-1' codec can't encode characters in position 180-184: ordinal not in range(256)

    return ",".join(ret_fields)

class RowAdapter:
    @staticmethod
    def clone(base_row, new_row):
        for idx, field in enumerate(base_row.fields):
            new_row.fields[idx].value = field.value

    @staticmethod
    def set_field_value(row, key_name, value):
        if value == "*":
            raise Exception, "Invalid value(%s) for key %s" % (value, key_name)

        for field in row.fields:
            if field.name == key_name:
                field.value = value
                break
        else:
            raise Exception, "No such field name(%s) in row" % key_name

class TableAdapter:
    @staticmethod
    def set_fields_value(tableObj, key_name, value):
        for row in tableObj.rows:
            RowAdapter.set_field_value(row, key_name, value)           

    @staticmethod
    def read(tableClass, tableObj, global_table = False):
        if tableObj.query_value == "*" or global_table == True:
            sql_cmd = "select * from %s" % tableObj.table_name
        else:
            sql_cmd = "select * from %s where %s=%s" % (tableObj.table_name, tableObj.query_keyname, tableObj.query_value)

        conn = tableClass.conn
        cursor = conn.cursor()
        cursor.execute(sql_cmd)
        results = cursor.fetchall()
        cursor.close()

        tableObj.rows = []
        for fields in results:
            row = tableClass.table_schema()
            for idx, field in enumerate(fields):
                row.fields[idx].value = field
            tableObj.rows.append(row)

    @staticmethod
    def write(tableClass, tableObj):
        if tableObj.writable == False:
            return

        conn = tableClass.conn
        cursor = conn.cursor()
        for row in tableObj.rows:
            sql_cmd = "insert into %s values (%s)" % (tableObj.table_name, get_insert_str(row.fields))
            cursor.execute(sql_cmd)
        conn.commit()
        cursor.close()

    @staticmethod
    def update(tableClass, tableObj, key, value, qry_key = None, qry_value = None):
        if qry_key == None:
            qry_key = tableObj.query_keyname
        if qry_value == None:
            qry_value = tableObj.query_value

        conn = tableClass.conn
        cursor = conn.cursor()
        sql_cmd = "update %s set %s=%s where %s=%s" % (tableObj.table_name, key, value, qry_key, qry_value)
        cursor.execute(sql_cmd)
        conn.commit()
        cursor.close()

    @staticmethod
    def delete(tableClass, tableObj, global_table = False):
        if tableObj.deletable == False:
            return

        if tableObj.query_value == "*" or global_table == True:
            sql_cmd = "delete from %s" % tableObj.table_name
        else:
            sql_cmd = "delete from %s where %s=%s" % (tableObj.table_name, tableObj.query_keyname, tableObj.query_value)

        conn = tableClass.conn
        cursor = conn.cursor()
        cursor.execute(sql_cmd)
        conn.commit()
        cursor.close()


class MySqlAdapter:
    @staticmethod
    def read_data_from_mysql(output_file):
        tableListObj = TableList()
        for tableClass, tableObj in tableListObj.tables:
            TableAdaper.read(tableClass, tableObj)
        FileAdapter.write(tableListObj, output_file)
         
    @staticmethod
    def write_mysql(input_file):
        tableListObj = FileAdapter.read(input_file)
        for tableClass, tableObj in tableListObj.tables:
            TableAdaper.write(tableClass, tableObj)

    @staticmethod
    def delete_mysql():
        tableListObj = TableList()
        for tableClass, tableObj in tableListObj.tables:
            TableAdaper.delete(tableClass, tableObj)


if __name__ == "__main__":
    MySqlAdapter.read_data_from_mysql("abc.txt")
    MySqlAdapter.write_mysql("abc.txt")
    MySqlAdapter.delete_mysql()
