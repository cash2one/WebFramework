#!/usr/bin/python
#encoding:utf-8

from TableList import *

import sys
reload(sys)
sys.setdefaultencoding('utf-8')

import pickle
import time

# save object to a file or read object from a file
class PickleUtil:
    @staticmethod
    def read_file(input_file):
        reader = open(input_file, "rb")
        object = pickle.load(reader)
        reader.close()
        return object

    @staticmethod
    def write_file(output_file, object):
        writer = open(output_file, "wb")
        pickle.dump(object, writer)
        writer.close()


# functions about xxRow classes
class RowUtil:
    @staticmethod
    def get_insert_str(row):
        temp_fields = []
        for field in row:
            value = field.get_sql_cmd_value()
            ### str(value),否则会抛异常：UnicodeEncodeError: 'latin-1' codec can't encode characters in position 180-184: ordinal not in range(256)
            temp_fields.append(str(value))
        return ",".join(temp_fields)

    @staticmethod
    def clone(src_row, target_row):
        if type(src_row) != type(target_row):
            raise Exception, "Type NOT accordance for row clone, %s VS %s" % (type(src_row), type(target_row))

        for idx, field in enumerate(src_row.fields):
            target_row.fields[idx].value = field.value

    @staticmethod
    def get_field(row, key_name):
        for field in row.fields:
            if field.name == key_name:
                return field
        else:
            raise Exception, "get_field: No such field name(%s) in row" % key_name

    @staticmethod
    def set_field_value(row, key_name, value):
        if value == "*":
            raise Exception, "Invalid value(%s) for key %s" % (value, key_name)
        RowUtil.get_field(row, key_name).value = value

    @staticmethod
    def get_field_value(row, key_name):
        return RowUtil.get_field(row, key_name).value

    @staticmethod
    def update_time(row, key_names, delta_months):
        for key_name in key_names:
            field = RowUtil.get_field(row, key_name)
            if field.value == 0 or field.value == None:
                continue

            if field.type == Type.timestamp:
                struct_time = time.strptime(field.value, "%Y-%m-%d %H:%M:%S")
                time_stamp = time.mktime(struct_time)
                time_stamp += delta_months * 24 * 60 * 60 * 30
                field.value = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime(time_stamp))

            elif field.type == Type.bigint:
                field.value += delta_months * 24 * 60 * 60 * 1000 * 30

            elif field.type == Type.int:
                value_str = str(field.value)
                if len(value_str) == 8:
                    # year and mon
                    struct_time = time.strptime(str(field.value), "%Y%m%d")
                    time_stamp = time.mktime(struct_time)
                    time_stamp += delta_months * 24 * 60 * 60 * 30
                    field.value = time.strftime("%Y%m%d", time.localtime(time_stamp))
                elif len(value_str) == 6:
                    # year and mon
                    struct_time = time.strptime(str(field.value), "%Y%m")
                    time_stamp = time.mktime(struct_time)
                    time_stamp += delta_months * 24 * 60 * 60 * 30
                    field.value = time.strftime("%Y%m", time.localtime(time_stamp))
            else:
                raise Exception, "update_time: invalid field type:%s" % field.type


# functions about xxTableClass 
class TableUtil:
    @staticmethod
    def set_fields_value(tableObj, key_name, value):
        for row in tableObj.rows:
            RowUtil.set_field_value(row, key_name, value)           

    @staticmethod
    def read_table(tableObj):
        if tableObj.query_value == "*":
            sql_cmd = "select * from %s" % tableObj.table_name
        else:
            sql_cmd = "select * from %s where %s=%s" % (tableObj.table_name, tableObj.query_keyname, tableObj.query_value)

        conn = tableObj.get_conn()
        cursor = conn.cursor()
        cursor.execute(sql_cmd)
        results = cursor.fetchall()
        cursor.close()

        tableObj.rows = []
        for fields in results:
            row = tableObj.build_row()
            for idx, value in enumerate(fields):
                row.fields[idx].value = value
            tableObj.rows.append(row)

    @staticmethod
    def write_table(tableObj):
        if tableObj.writable == False:
            return

        conn = tableObj.get_conn()
        cursor = conn.cursor()
        for row in tableObj.rows:
            sql_cmd = "insert into %s values (%s)" % (tableObj.table_name, RowUtil.get_insert_str(row.fields))
            cursor.execute(sql_cmd)
        conn.commit()
        cursor.close()

    @staticmethod
    def update_table(tableObj, key, value):
        if tableObj.query_value == "*":
            sql_cmd = "update %s set %s=%s" % (tableObj.table_name, key, value)
        else:
            sql_cmd = "update %s set %s=%s where %s=%s" % (tableObj.table_name, key, value, tableObj.query_keyname, tableObj.query_value)

        conn = tableObj.get_conn()
        cursor = conn.cursor()
        cursor.execute(sql_cmd)
        conn.commit()
        cursor.close()

    @staticmethod
    def update_time(tableObj, key_names, delta_days):
        for row in tableObj.rows:
            RowUtil.update_time(row, key_names, delta_days)

    @staticmethod
    def delete_table(tableObj):
        if tableObj.deletable == False:
            return
        
        if tableObj.query_value == "*":
            sql_cmd = "delete from %s" % tableObj.table_name
        else:
            sql_cmd = "delete from %s where %s=%s" % (tableObj.table_name, tableObj.query_keyname, tableObj.query_value)

        conn = tableObj.get_conn()
        cursor = conn.cursor()
        cursor.execute(sql_cmd)
        conn.commit()
        cursor.close()


class TableListUtil:
    @staticmethod
    def get_table(table_name):
        for tableObj in TableList.tables:
            if tableObj.table_name == table_name:
                return tableObj
        else:
            raise Exception, "get_table: no table with name(%s)" % table_name

    @staticmethod
    def read_tables(output_file):
        for tableObj in TableList.tables:
            TableUtil.read_table(tableObj)
        PickleUtil.write_file(output_file, TableList)

    @staticmethod
    def write_tables(input_file):
        TableList = PickleUtil.read_file(input_file)
        for tableObj in TableList.tables:
            TableUtil.write_table(tableObj)

    @staticmethod
    def delete_tables():
        for tableObj in TableList.tables:
            TableUtil.delete_table(tableObj)

    @staticmethod
    def output():
        for tableObj in TableList.tables:
            print tableObj.table_name
            print tableObj.query_keyname    
            print tableObj.query_value
            print tableObj.writable
            print tableObj.deletable

            for row in tableObj.rows:
                print row

if __name__ == "__main__":
    TableListUtil.read_tables("abc.txt")
    TableListUtil.output()
