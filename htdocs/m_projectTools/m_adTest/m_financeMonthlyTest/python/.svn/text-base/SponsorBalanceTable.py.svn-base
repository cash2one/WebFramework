#!/usr/bin/python
#encoding:utf-8

from BaseTable import *

class SponsorBalanceTable(BaseTable):
    def _my_init(self):
        self.db_conn = BaseTable.FinanceDB
        self.table_name  = "SPONSOR_BALANCE"
        self.table_desc  = "广告商余额表"
        self.sponsor_id_key_name = "SPONSOR_ID"

        self.value_dict = {
            "DEPOSIT_STATUS" : {
                0: "未入资",
                1: "已入资", 
            },
            "DISCOUNT_TYPE" : {
                0: "普通固定折扣",
                1: "月结固定折扣",
                2: "月度总消费条件折扣",
                3: "月度日均消费条件折扣",
            },
            "SETTLEMENT_TYPE": {
                0: "普通结算",
                1: "月结算",
            }
        }

if __name__ == "__main__":
    table = SponsorBalanceTable()
    print table.get_sponsor_rows(64, ["*"])
