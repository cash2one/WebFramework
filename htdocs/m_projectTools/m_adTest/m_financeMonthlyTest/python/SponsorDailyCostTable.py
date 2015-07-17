#!/usr/bin/python
#encoding:utf-8

from BaseTable import *

class SponsorDailyCostTable(BaseTable):
    def _my_init(self):
        self.db_conn = BaseTable.FinanceDB
        self.table_name  = "SPONSOR_DAILY_COST"
        self.table_desc  = "广告商每日消费"
        self.sponsor_id_key_name = "SPONSOR_ID"

        self.value_dict = {}
