#!/usr/bin/python
#encoding: utf-8

from MonthSummary import *

class MonthSummaryAdd(MonthSummary):

    def __init__(self, title):
        MonthSummary.__init__(self, title)

        self.confirm_defer_income = 0            # 确认收入+递延收入
        self.actual_deposite_withdraw_total = 0  # 实际入撤资汇总
        self.title_bold = True

    def build_after(self):
        self.lines.append(self.get_td_str("确认收入+递延收入", self.confirm_defer_income))
        self.lines.append(self.get_td_str("实际入撤资汇总", self.actual_deposite_withdraw_total))


if __name__ == "__main__":
    monthSum = MonthSummaryAdd("title")
    monthSum.build_lines()
    print "\n".join(monthSum.lines)
