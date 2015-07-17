#!/usr/bin/python
#encoding: utf-8

import os
import pickle
from Schema import *
from Table import *
from DBConnection import *
import sys
reload(sys)
sys.setdefaultencoding('utf-8')

# ========================= Global Variables Area ==================================
# sponsor id used to be operated on
#_sponsor_id = 215623
_sponsor_id = 64

# table data operations: write, delete
_table_list = [
    Table(SponsorSchema, "SPONSOR_ID", TableStyle.horizontal),
    Table(SponsorBalanceSchema, "SPONSOR_ID", TableStyle.horizontal),
    Table(SponsorMonthlyDiscountSchema, "SPONSOR_ID"),
    Table(SponsorMonthlyDiscountDetailSchema, "SMD_ID"),
    Table(SponsorMonthlyContractSchema, "SPONSOR_ID"),
    Table(SponsorAccountHistorySchema, "TO_USER_ID", TableStyle.horizontal),
    Table(SponsorMonthlySettlementSchema, "SPONSOR_ID", TableStyle.horizontal),
    Table(LogDaSchema, "adSponsorId"),
    Table(LogDaSimSchema, "adSponsorId"),
    Table(ClickSchema, "SPONSOR_ID"),
]

_table_names_for_read_only = ["Sponsor"]

# ==================== Functions Area for DB operations ======================
# read data from finance-related tables
def read_data_from_tables():
    global _table_list, _sponsor_id
    for tableObj in _table_list:
        table_name    = tableObj.schemaClass.table_name
        fields        = map(lambda field: field.name, tableObj.schemaClass.schema_fields)
        if table_name == "SPONSOR_MONTHLY_DISCOUNT_DETAIL":
            sql_query_cmd = "select %s from %s" % (",".join(fields), table_name)
        else:
            sql_query_cmd = "select %s from %s where %s=%s" % (",".join(fields), table_name, tableObj.main_key, _sponsor_id)
        cursor  = DBConnection.get(table_name).cursor()
        cursor.execute(sql_query_cmd)
        results = cursor.fetchall()
        for fields in results:
            tableObj.add_row(fields)
        cursor.close()


# write data to finance-related tables
def write_data_to_tables():
    global _table_list, _table_names_for_read_only
    for tableObj in _table_list:
        if tableObj.schemaClass.table_name in _table_names_for_read_only:
            continue

        table_name = tableObj.schemaClass.table_name
        fields     = map(lambda field: field.name, tableObj.schemaClass.schema_fields)
        values_str_rows = tableObj.get_insert_value_rows()
        conn    = DBConnection.get(table_name)
        cursor  = conn.cursor()
        for values_str_row in values_str_rows:
            sql_query_cmd = "insert into %s (%s) values (%s)" % (table_name, ",".join(fields), values_str_row)
            cursor.execute(str(sql_query_cmd))
        conn.commit() 
        cursor.close()


# delete data in finance-related tables
def delete_data_in_tables():
    global _table_list, _sponsor_id, _table_names_for_read_only
    for tableObj in _table_list:
        if tableObj.schemaClass.table_name in _table_names_for_read_only:
            continue

        table_name    = tableObj.schemaClass.table_name
        if table_name == "SPONSOR_MONTHLY_DISCOUNT_DETAIL":
            sql_query_cmd = "delete from %s" % table_name
        else:
            sql_query_cmd = "delete from %s where %s=%s" % (table_name, tableObj.main_key, _sponsor_id)
        conn    = DBConnection.get(table_name)
        cursor  = conn.cursor()
        cursor.execute(sql_query_cmd)
        conn.commit()
        cursor.close()

# output as html format
def get_html_str(class_name = ""):
    global _table_list
    return "\n".join(map(lambda tableObj: tableObj.get_html_str(class_name), _table_list))

# ===================== Functions Area for seriablizations ==============
# write table-list obj to file
def save_tables_data_to_file(file_path):
    global _table_list 
    writer = open(file_path, "wb")
    pickle.dump(_table_list, writer)
    writer.close()


# read table-list obj from file
def load_tables_data_from_file(file_path):
    global _table_list
    reader = open(file_path, "rb")
    _table_list = pickle.load(reader)
    reader.close()

# output table-list as html format
def output_tables_as_html_file(file_path):
    filename = os.path.basename(file_path)
    class_name = filename.split(".")[0]
    open(file_path, "w").write("<div>\n" + get_html_str(class_name) + "</div>\n")

# ========================= Entry Functions ==================================
def main():
    usage = "Usage: %s [delete|read|write|output_html|output_html2] [file] [html_file]" % sys.argv[0]
    param_list = sys.argv[1:]
    if len(param_list) == 1 and param_list[0] == "delete":
        delete_data_in_tables()
    
    elif len(param_list) == 2:
        op_type = param_list[0]
        file = param_list[1]
        if op_type == "read":
            read_data_from_tables()
            save_tables_data_to_file(file)
        elif op_type == "write":
            load_tables_data_from_file(file)
            write_data_to_tables()
        elif op_type == "output_html2":
            read_data_from_tables()
            output_tables_as_html_file(file)
        else:
            raise Exception, usage

    elif len(param_list) == 3:
        op_type = param_list[0]
        file = param_list[1]
        output_file = param_list[2]
        if op_type == "output_html":
            load_tables_data_from_file(file)
            output_tables_as_html_file(output_file)
    else:
        raise Exception, usage


# ========================= Main Logic ==================================
if __name__ == "__main__":
    main()
