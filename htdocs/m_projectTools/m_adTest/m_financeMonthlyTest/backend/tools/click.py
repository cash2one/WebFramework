#!/usr/bin/python
#encoding:utf-8

import os
import sys
sys.path.append(".")

from MySqlAdapter import *
from Table import *

def read_table_data(output_file):
    baseTable = ClickTable()
    TableAdapter.read(ClickTable, baseTable, True)
    baseTable.rows = baseTable.rows[:1]
    TableAdapter.set_fields_value(baseTable, baseTable.query_keyname, baseTable.query_value)
    TableAdapter.set_fields_value(baseTable, "ACTU_COST", 100)
    FileAdapter.write(baseTable, output_file)

def write_table(input_file):
    baseTable = FileAdapter.read(input_file)
    base_row = baseTable.rows[0]

    set_tuple = (
        (10, "63"), (11, "63"), (12, "63"),
        (103, "64"), (104, "64"), (105, "64"), (106, "64"),
        (57, "65"), (58, "65"), (59, "65"),
    )

    table = ClickTable()
    for s_tuple in set_tuple:
        row = ClickRow()
        RowAdapter.clone(base_row, row)
        RowAdapter.set_field_value(row, "ID", s_tuple[0])
        RowAdapter.set_field_value(row, "SPONSOR_ID", s_tuple[1])
        table.rows.append(row)
    TableAdapter.delete(ClickTable, table, True)
    TableAdapter.write(ClickTable, table) 
    

if __name__ == "__main__":
    base_file = "./base_data/CLICK.txt"
    #read_table_data(base_file)
    write_table(base_file)
