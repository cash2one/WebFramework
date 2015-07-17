#!/usr/bin/python
#encoding:utf-8

from BaseTable import *

class SponsorMonthlyContractTable(BaseTable):
    def _my_init(self):
        self.db_conn = BaseTable.FinanceDB
        self.table_name  = "SPONSOR_MONTHLY_CONTRACT"
        self.table_desc  = "月结广告商合同信息"
        self.sponsor_id_key_name = "SPONSOR_ID"
        self.value_dict = {
            "CONTRACT_START_DATE": {
                "*": MyTime.get_date_str,
            },
            "CONTRACT_END_DATE": {
                "*": MyTime.get_date_str,
            },
            "CREATE_TIME": {
                "*": MyTime.get_time_str,
            },
            "LAST_UPDATE_TIME": {
                "*": MyTime.get_time_str,
            },
            "STATUS": {
                1: "待审核",
                2: "审核不通过",
                3: "审核通过",
                5: "无效",
            },
            "CONFIRM_TIME": {
                "*": MyTime.get_date_str,
            },
        }

if __name__ == "__main__":
    table = SponsorMonthlyContractTable()
    print table.get_sponsor_rows(64, ["*"])
