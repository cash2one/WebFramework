#!/usr/bin/python
#encoding: utf-8

from util import *

class MonthSummary:

    def __init__(self, title):
        self.title = title
        self.lines = []

        self.confirm_income = ""                    # 确认收入
        self.confirm_income_after_discount = 0      # 折后客户确认消费收入（正）
        self.service_fee_balance = ""               # 服务费余额（正）
        self.monthly_payment = ""                   # 月结收款（正）
        self.monthly_payment_registered_adjust = "" # 月结收款已登记调整（负）
        self.agent_fine = ""                        # 经销商罚款（正）
        self.agent_cost_profits = 0                 # 经销商消费返点（负）
        self.agent_award_profits = 0                # 经销商奖励返点（负）
        self.agent_withholding_profits = ""          # 本月预提奖励（负）
        self.total1 = 0                             # 总计
        
        self.defer_income = ""                      # 递延收入
        self.balance_after_discount = 0             # 客户折后余额（正）
        self.agent_balance = 0                      # 经销商余额（正）
        self.agent_withholding_profits2 = ""        # 本月预提奖励（正）
        self.total2 = 0                             # 总计

        self.title_bold = False


    def get_td_str(self, title, value):
        temp_list = []
        value = str(value)

        if value == "":
            value = "&nbsp;"
        elif "." in value:
            int_part, dec_part = value.split(".")
            int_part = Util.getFormatNum(int_part)
            value = int_part + "." + dec_part
        else:
            value = Util.getFormatNum(value)

        if self.title_bold == True:
            return "<td><b>%s</b></td><td>%s</td>" % (title, value)
        else:
            return "<td>%s</td><td>%s</td>" % (title, value)


    def build_lines(self):
        self.lines.append(self.get_td_str(self.title, ""))
        self.lines.append(self.get_td_str("确认收入", self.confirm_income))
        self.lines.append(self.get_td_str("折后客户确认消费收入（正）", self.confirm_income_after_discount))
        self.lines.append(self.get_td_str("服务费余额（正）", self.service_fee_balance))
        self.lines.append(self.get_td_str("月结收款（正）", self.monthly_payment))
        self.lines.append(self.get_td_str("月结收款已登记调整（负）", self.monthly_payment_registered_adjust))
        self.lines.append(self.get_td_str("经销商罚款（正）", self.agent_fine))
        self.lines.append(self.get_td_str("经销商消费返点（负）", self.agent_cost_profits))
        self.lines.append(self.get_td_str("经销商奖励返点（负）", self.agent_award_profits))
        self.lines.append(self.get_td_str("本月预提奖励（负）", self.agent_withholding_profits))
        self.lines.append(self.get_td_str("总计", self.total1))
        self.lines.append(self.get_td_str("", ""))
        self.lines.append(self.get_td_str("递延收入", self.defer_income))
        self.lines.append(self.get_td_str("客户折后余额（正）", self.balance_after_discount))
        self.lines.append(self.get_td_str("经销商余额（正）", self.agent_balance))
        self.lines.append(self.get_td_str("本月预提奖励（正）", self.agent_withholding_profits2))
        self.lines.append(self.get_td_str("总计", self.total2))

        for i in range(4):
            self.lines.append(self.get_td_str("", ""))

        self.build_after()

    def build_after(self):
        for i in range(2):
            self.lines.append(self.get_td_str("", ""))
        

if __name__ == "__main__":
    monthSum = MonthSummary()
    print monthSum
