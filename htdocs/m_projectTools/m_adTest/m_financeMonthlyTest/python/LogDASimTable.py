#!/usr/bin/python
#encoding:utf-8

from BaseTable import *

class LogDASimTable(BaseTable):
    def _my_init(self):
        self.db_conn = BaseTable.LogDB
        self.table_name  = "Log_DA_SIM"
        self.table_desc  = "DA_SIM的日志"
        self.sponsor_id_key_name = "adSponsorId"

        self.value_dict = {}
