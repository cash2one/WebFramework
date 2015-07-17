#!/usr/bin/python
#encoding:utf-8

from BaseTable import *

class SponsorMonthlySettlementTable(BaseTable):
    def _my_init(self):
        self.db_conn = BaseTable.FinanceDB
        self.table_name  = "SPONSOR_MONTHLY_SETTLEMENT"
        self.table_desc  = "广告商月结信息"
        self.sponsor_id_key_name = "SPONSOR_ID"
        self.value_dict = {
            "PAYMENT_STATUS": {
                1: "未结算",
                0: "已结算",
            },
            "AUDIT_STATUS": {
                0: "已提交",
                1: "已确认待审核",
                2: "审核不通过",
                3: "审核通过",
            },
            "CREATE_TIME": {
                "*": MyTime.get_time_str,
            },
            "AUDIT_TIME": {
                "*": MyTime.get_time_str,
            },
            "SETTLEMENT_TIME": {
                "*": MyTime.get_time_str,
            },
        }

if __name__ == "__main__":
    table = SponsorMonthlySettlementTable()
    print table.get_sponsor_rows(64, ["*"])
