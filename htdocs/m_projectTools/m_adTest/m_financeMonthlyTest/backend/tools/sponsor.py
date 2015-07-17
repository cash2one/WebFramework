#!/usr/bin/python
#encoding:utf-8

import os
import sys
sys.path.append(".")

from MySqlAdapter import *
from Table import *

def update_table():
    table = SponsorTable()
    # password: hello123
    TableAdapter.update(SponsorTable, table, "USER_NAME", "'youdao_adtest02@163.com'")
    TableAdapter.update(SponsorTable, table, "EMAIL", "'youdao_adtest02@163.com'")

if __name__ == "__main__":
    update_table()
