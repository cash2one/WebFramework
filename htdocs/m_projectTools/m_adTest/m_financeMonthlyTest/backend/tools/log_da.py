#!/usr/bin/python
#encoding:utf-8

import os
import sys
sys.path.append(".")

from MySqlAdapter import *
from Table import *

def read_table_data(output_file):
    baseTable = LogDaTable()
    TableAdapter.read(LogDaTable, baseTable, True)
    baseTable.rows = baseTable.rows[:1]
    TableAdapter.set_fields_value(baseTable, "consumption", 10000)
    TableAdapter.set_fields_value(baseTable, baseTable.query_keyname, baseTable.query_value)
    FileAdapter.write(baseTable, output_file)

def write_table(input_file):
    baseTable = FileAdapter.read(input_file)
    base_row = baseTable.rows[0]

    set_tuple = (
        (201210, 20121013, "20121013-1013762-1"),
        (201210, 20121031, "20121031-1013762-1"),
        (201211, 20121101, "20121101-1013762-1"),
        (201211, 20121113, "20121113-1013762-1"),
        (201211, 20121130, "20121130-1013762-1"),
        (201212, 20121201, "20121201-1013762-1"),
        (201212, 20121213, "20121213-1013762-1"),
    )

    table = LogDaTable()
    for s_tuple in set_tuple:
        row = LogDaRow()
        RowAdapter.clone(base_row, row)
        RowAdapter.set_field_value(row, "statMonth", s_tuple[0])
        RowAdapter.set_field_value(row, "statDay", s_tuple[1])
        RowAdapter.set_field_value(row, "id", s_tuple[2])
        table.rows.append(row)
    TableAdapter.delete(LogDaTable, table, True)
    TableAdapter.write(LogDaTable, table) 
    

if __name__ == "__main__":
    base_file = "./base_data/Log_DA.txt"
    #read_table_data(base_file)
    write_table(base_file)
