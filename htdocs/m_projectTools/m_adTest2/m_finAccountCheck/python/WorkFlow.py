#!/usr/bin/python
#encoding:utf-8

import MySQLdb
import struct
import sys
import time

from util import *
from MonthSummary import *
from MonthSummaryAdd import *

class LogType:
    SUM = 1
    DETAIL = 2

class WorkFlow:

    def __init__(self):
        host, dbname, port, user, passwd = Util.getConf("test_fin_db").split(":")
        self.connObj = MySQLdb.connect(host=host, user=user, passwd=passwd, db=dbname, port=int(port), charset="utf8")

    def __select_table(self, sql_str):
        result_list = []
        cursor = self.connObj.cursor()
        cursor.execute(sql_str)
        for fields in cursor.fetchall():
            result_list.append(list(fields))
        cursor.close()
        return result_list

    def __get_sponsor_detail(self, month_str):
        """获取客户入撤资明细"""
        pre_sponsor_id = -1
        temp_list      = []

        table_name = "SPONSOR_REVENUE_MONTHLY"
        sql_str = "select sponsor_id, agent_id, audit_time, op_type, seq, input, cost, ratio, available_input, cost_return from %s where revenue_month = %s and log_type != %d and agent_id not in (3, 12);" % (table_name, month_str, LogType.SUM)
        result_list = self.__select_table(sql_str)

        for fields in result_list:
            sponsor_id = fields[0]
            op_type    = fields[3]
            input      = fields[5]
            cost       = fields[6]
            ratio      = float(fields[7]) # ratio is varchar type in table
            available_input  = fields[8]
            confirm_income = 0
            defer_income   = 0

            if pre_sponsor_id != sponsor_id:
                pre_sponsor_id = sponsor_id
                temp_list = [] 

            balance = input - cost
            if balance > 0:
                # 临时保存余额大于0的
                temp_list.append([balance, ratio])
            elif op_type != 16:
                # 处理扣除服务费逻辑, 对应的ratio是0
                for idx, pairs in enumerate(temp_list):
                    if pairs[0] + balance >= 0:
                        temp_list[idx][0] += balance
                        balance = 0
                    else:
                        balance += pairs[0]
                        temp_list[idx][0] = 0
            
            if op_type != 16:
                # 操作类型不是"额外扣款"
                confirm_income = cost * (1.0 - ratio)
                # ROUNDUP
                if confirm_income > int(confirm_income):
                    confirm_income = int(confirm_income) + 1
                else:
                    confirm_income = int(confirm_income)

                # ROUNDDOWN
                defer_income = int(available_input * (1.0 - ratio))

            else:
                # 处理额外扣款逻辑, balance < 0
                for idx, pairs in enumerate(temp_list):
                    if pairs[0] + balance >= 0:
                        temp_list[idx][0] += balance
                        confirm_income += abs(balance) * (1.0 - pairs[1])
                        balance = 0
                    else:
                        balance += pairs[0]
                        confirm_income += pairs[0] * (1.0 - pairs[1])
                        temp_list[idx][0] = 0

            fields.append(confirm_income)
            fields.append(defer_income)

        return result_list

    def __get_agent_balance(self, month_str):
        """经销商余额"""
        table_name = "AGENT_REVENUE_MONTHLY"
        sql_str = "select agent_id, balance from %s where revenue_month = %s and log_type = %d and agent_id not in (3, 12);" % (table_name, month_str, LogType.SUM)
        return self.__select_table(sql_str)

    def __get_agent_detail(self, month_str):
        """经销商入撤资明细"""
        result_list = []
        table_name = "AGENT_REVENUE_MONTHLY"
        sql_str = "select log_type, agent_id, audit_time, op_type, actual_balance from %s where revenue_month = %s and agent_id not in (3, 12);" % (table_name, month_str)
        ret_list = self.__select_table(sql_str)

        cur_agent_id = -1
        for fields in ret_list:
            log_type = fields[0]
            agent_id = fields[1]
            audit_time = fields[2]
            op_type = fields[3]
            actual_balance = fields[4]
            if log_type == LogType.SUM:
                cur_agent_id = agent_id
            else:
                result_list.append([cur_agent_id, audit_time, op_type, actual_balance])
        return result_list

    def __set_result(self, monthSummaryObj, month_str):
        # month_str = Util.date_adjust(month_str) 已经fix了
        # fill 折后客户确认消费收入, 客户折后余额
        total_confirm_income = 0
        total_defer_income   = 0
        sponsor_detail_list = self.__get_sponsor_detail(month_str)
        for fields in sponsor_detail_list:
            total_confirm_income += fields[-2]
            total_defer_income   += fields[-1]
        total_confirm_income /= 100.0
        total_defer_income   /= 100.0
        monthSummaryObj.confirm_income_after_discount = total_confirm_income
        monthSummaryObj.balance_after_discount = total_defer_income

        # fill 经销商余额
        total_agent_balance = 0
        agent_balance_list = self.__get_agent_balance(month_str)
        for agent_id, balance in agent_balance_list:
            total_agent_balance += balance
        monthSummaryObj.agent_balance = total_agent_balance / 100.0

        # fill 经销商消费返点, 经销商奖励返点
        total_agent_cost_profits = 0
        total_agent_award_profits = 0
        agent_detail_list = self.__get_agent_detail(month_str)
        for fields in agent_detail_list:
            op_type = fields[2]
            actual_balance = fields[3]
            if op_type == 12:
                total_agent_cost_profits += actual_balance
            elif op_type == 7:
                total_agent_award_profits += actual_balance
        total_agent_cost_profits /= 100.0
        total_agent_award_profits /= 100.0
        monthSummaryObj.agent_cost_profits = total_agent_cost_profits * -1.0
        monthSummaryObj.agent_award_profits = total_agent_award_profits * -1.0

        # fill 总计
        monthSummaryObj.total1 = monthSummaryObj.confirm_income_after_discount + monthSummaryObj.agent_cost_profits + monthSummaryObj.agent_award_profits
        monthSummaryObj.total2 = monthSummaryObj.balance_after_discount + monthSummaryObj.agent_balance

        return sponsor_detail_list, agent_detail_list, agent_balance_list

    def run(self, month_str):
        '''计算该月的收入'''
        pre_month_str = Util.get_pre_month(month_str)

        self.month_str = month_str
        year, month = struct.unpack("4s2s", str(month_str))
        pre_year, pre_month = struct.unpack("4s2s", str(pre_month_str))

        self.preMonthSummary = MonthSummary("截至%s年%s月1日历史汇总" % (pre_year, pre_month))
        self.monthSummary    = MonthSummary("截至%s年%s月1日历史汇总" % (year, month))
        self.addSummary      = MonthSummaryAdd("%s年%s月新增收入" % (year, month))

        self.__set_result(self.preMonthSummary, pre_month_str)
        sponsor_detail_list, agent_detail_list, agent_balance_list = self.__set_result(self.monthSummary, month_str)

        # 计算新增收入
        sponsor_deposite_total = 0
        sponsor_withdraw_total = 0
        sponsor_deposite_and_service_fee = 0
        sponsor_service_fee = 0
        agent_deposite = 0
        agent_withdraw = 0

        for fields in sponsor_detail_list:
            audit_time = fields[2]
            this_month = audit_time.year * 100 + audit_time.month
            if this_month != month_str:
                continue

            op_type = fields[3]
            input   = fields[5]
            if op_type == 1:
                sponsor_deposite_total += input
            elif op_type == 3:
                sponsor_withdraw_total += input
            elif op_type == 11:
                sponsor_deposite_and_service_fee += input
            elif op_type == 41:
                sponsor_service_fee += input

        for fields in agent_detail_list:
            audit_time = fields[1]
            this_month = audit_time.year * 100 + audit_time.month
            if this_month != month_str:
                continue

            op_type = fields[2]
            actual_balance = fields[3]
            if op_type == 1:
                agent_deposite += actual_balance
            elif op_type == 3:
                agent_withdraw += actual_balance

        self.addSummary.confirm_income_after_discount = self.monthSummary.confirm_income_after_discount - self.preMonthSummary.confirm_income_after_discount
        self.addSummary.agent_cost_profits = self.monthSummary.agent_cost_profits - self.preMonthSummary.agent_cost_profits
        self.addSummary.agent_award_profits = self.monthSummary.agent_award_profits - self.preMonthSummary.agent_award_profits
        self.addSummary.total1 = self.monthSummary.total1 - self.preMonthSummary.total1

        self.addSummary.balance_after_discount = self.monthSummary.balance_after_discount - self.preMonthSummary.balance_after_discount
        self.addSummary.agent_balance = self.monthSummary.agent_balance - self.preMonthSummary.agent_balance
        self.addSummary.total2 = self.monthSummary.total2 - self.preMonthSummary.total2

        self.addSummary.confirm_defer_income = self.addSummary.total1 + self.addSummary.total2
        self.addSummary.actual_deposite_withdraw_total = (sponsor_deposite_total + sponsor_withdraw_total + sponsor_deposite_and_service_fee + sponsor_service_fee + agent_deposite + agent_withdraw) / 100.0 

        print "广告商入资:", sponsor_deposite_total
        print "广告商撤资:", sponsor_withdraw_total
        print "广告商入资并扣除服务费:", sponsor_deposite_and_service_fee
        print "广告商扣除服务费:", sponsor_service_fee
        print "代理商入资:", agent_deposite
        print "代理商撤资:", agent_withdraw

    def output(self):
        self.preMonthSummary.build_lines()
        self.monthSummary.build_lines()
        self.addSummary.build_lines()

        lines = []
        for idx, line in enumerate(self.preMonthSummary.lines):
            line2 = self.monthSummary.lines[idx]
            line3 = self.addSummary.lines[idx]
            lines.append("<tr>" + line + line2 + line3 + "</tr>")

        open("./output/%s.html" % self.month_str, "w").write("\n".join(lines))

if __name__ == "__main__":
    year, month = struct.unpack("4s2s", sys.argv[1])
    year = int(year)
    month = int(month)

    if year < 2008 or year > int(time.strftime("%Y", time.localtime())):
        raise Exception("Invalid year: %s" % year)
    
    if month < 1 or month > 12:
        raise Exception("Invalid month: %s" % month)
    
    
    wf = WorkFlow()
    wf.run(year * 100 + month)
    wf.output()
