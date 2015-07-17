#!/usr/bin/python
#encoding: utf-8

from BaseTable import *

class SponsorMonthlyDiscountTable(BaseTable):
    def _my_init(self):
        self.db_conn = BaseTable.FinanceDB
        self.table_name  = "SPONSOR_MONTHLY_DISCOUNT"
        self.table_desc  = "月结广告商折扣信息"
        self.sponsor_id_key_name = "SPONSOR_ID"
        self.value_dict = {
            "STATUS": {
                0: "有效",
                1: "删除",
            },
            "AUDIT_STATUS": {
                1: "待审核",
                2: "审核不通过",
                3: "审核通过",
            },
            "CREATE_TIME": {
                "*": MyTime.get_time_str,
            },
            "AUDIT_TIME": {
                "*": MyTime.get_time_str,
            },
            "DISCOUNT_TYPE": {
                1: "固定折扣",
                2: "月度总消费条件折扣",
                3: "月度日均消费条件折扣",
            },
        }

if __name__ == "__main__":
    table = SponsorMonthlyDiscountTable()
    rows = table.get_sponsor_rows(64)
    for row in rows:
        print row
        row = table._get_readable_row(row)
        print row
