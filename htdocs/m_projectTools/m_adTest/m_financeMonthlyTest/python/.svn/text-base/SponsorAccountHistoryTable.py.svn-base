#!/usr/bin/python
#encoding:utf-8

from BaseTable import *

class SponsorAccountHistoryTable(BaseTable):
    def _my_init(self):
        self.db_conn = BaseTable.FinanceDB
        self.table_name  = "SPONSOR_ACCOUNT_HISTORY"
        self.table_desc  = "广告商账务明细表"
        self.sponsor_id_key_name = "TO_USER_ID"
        self.value_dict = {
            "OPERATE_TIME": {
                "*": MyTime.get_time_str,
            },
            "AUDIT_TIME": {
                "*": MyTime.get_time_str,
            },
            "OPERATE_TYPE": {
                1: "入资",
                2: "返点、体验金入资",
                3: "撤资",
                4: "被代理商撤资",
                5: "被代理商入资",
                7: "虚拟资金入资",
                8: "体验金撤资",
                9: "合同编辑",
                21: "设置信用额度",
                31: "录入折扣率",
                41: "扣除服务费",
                32: "录入月结广告商折扣率",
                51: "标记月结广告商",
                52: "月结广告商结算",
            },
            "AUDIT_STATUS": {
                0: "已提交",
                1: "已确认待审核",
                2: "审核未通过",
                3: "审核通过",
                4: "不用审核",
                5: "无效（记录已删除）",
                7: "暂缓处理",
                8: "延迟处理并已处理",
            },
            "IS_CHARGED": {
                0: "未入账",
                1: "已入账",
            },
            "PAYMENT_TYPE": {
                1: "现金",
                2: "支票",
                3: "电汇",
                4: "其他",
                5: "在线支付",
            }
        }

if __name__ == "__main__":
    table = SponsorAccountHistoryTable()
    print table.get_sponsor_rows(64)
