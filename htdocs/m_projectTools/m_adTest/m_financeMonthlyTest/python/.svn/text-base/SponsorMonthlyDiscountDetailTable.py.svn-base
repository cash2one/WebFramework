#!/usr/bin/python
#encoding:utf-8

from BaseTable import *
from SponsorMonthlyDiscountTable import *

class SponsorMonthlyDiscountDetailTable(BaseTable):
    def _my_init(self):
        self.db_conn = BaseTable.FinanceDB
        self.table_name  = "SPONSOR_MONTHLY_DISCOUNT_DETAIL"
        self.table_desc  = "月结广告商折扣明细信息"
        self.sponsor_id_key_name = "SMD_ID"
        self.value_dict = {
            "LOGIC_SYMBOL": {
                0: "=",
                1: ">",
                2: "<",
                3: ">=",
                4: "<=",
                5: "介于(前闭后开)",
            },
            "STATUS": {
                0: "有效",
                1: "删除",
            },
        }

if __name__ == "__main__":
    table = SponsorMonthlyDiscountDetailTable()
    print table.get_sponsor_rows(64, ["*"])
