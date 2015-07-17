#!/usr/bin/python
#encoding:utf-8

from BaseTable import *

class ClickTable(BaseTable):
    def _my_init(self):
        self.db_conn = BaseTable.AdClickDB
        self.table_name  = "CLICK"
        self.table_desc  = "CLICK的日志"
        self.sponsor_id_key_name = "SPONSOR_ID"

        self.value_dict = {}
