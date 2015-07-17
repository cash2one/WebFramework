#!/usr/bin/python
#encoding:utf-8

import os
import sys
sys.path.append(".")

from MySqlAdapter import *
from Table import *

def update_table():
    table = AdClickChargeUpProgressTable()
    TableAdapter.update(AdClickChargeUpProgressTable, table, "PROGRESS_ACI_ID", 0, "REMARK", "'fin'")
    TableAdapter.update(AdClickChargeUpProgressTable, table, "PROGRESS_ACI_ID", 0, "REMARK", "'fin2'")

if __name__ == "__main__":
    update_table()
