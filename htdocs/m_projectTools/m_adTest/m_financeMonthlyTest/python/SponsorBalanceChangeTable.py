#!/usr/bin/python
#encoding:utf-8

from BaseTable import *

class SponsorBalanceChangeTable(BaseTable):
    def _my_init(self):
        self.db_conn = BaseTable.AdUpdateDB
        self.table_name  = "SPONSOR_BALANCE_CHANGE"
        self.table_desc  = "广告商余额变动表"
        self.sponsor_id_key_name = "SPONSOR_ID"

        self.value_dict = {
            "CHANGE_TYPE" : {
                1: "增加",
                2: "减少", 
            },
        }
