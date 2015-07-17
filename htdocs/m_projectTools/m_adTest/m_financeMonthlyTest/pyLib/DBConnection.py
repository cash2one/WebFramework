#!/usr/bin/python
#encoding:utf-8

"""
import MySQLdb
db=MySQLdb.connect(user="guest",passwd="guest",db="dbname",use_unicode=True)
db.set_character_set('utf8')
c=db.cursor()
c.execute('SET NAMES utf8;')
c.execute('SET CHARACTER SET utf8;')
c.execute('SET character_set_connection=utf8;') 
"""

import MySQLdb

from datetime import datetime
import time

class MyTime:
    @staticmethod
    def get_time_str(timestamp):
        if timestamp == None: return None

        timestamp = timestamp / 1000.0
        if timestamp > 2205567108:
            return "%s(TimeOutOfRange)" % timestamp

        time_tuple = time.gmtime(timestamp)  
        return time.strftime("%Y-%m-%d %H:%M:%S", time_tuple)

    @staticmethod
    def get_date_str(timestamp):
        if timestamp == None: return None

        timestamp = timestamp / 1000.0
        if timestamp > 2205567108:
            return "%s(TimeOutOfRange)" % timestamp

        time_tuple = time.gmtime(timestamp)  
        return time.strftime("%Y-%m-%d", time_tuple)

class DBConnection:
    Finance = MySQLdb.connect(host="tb081", user="test", passwd="test", db="financeDB", port=3306, charset='utf8')
    AdClick = MySQLdb.connect(host="tb081", user="test", passwd="test", db="adclickDB", port=3306, charset='utf8')
    AdUpdate = MySQLdb.connect(host="tb081", user="test", passwd="test", db="adupdateDB", port=3306, charset='utf8')
    AdPublish = MySQLdb.connect(host="tb081", user="test", passwd="test", db="adpublishDB", port=3306, charset='utf8')
    LogDB     = MySQLdb.connect(host="tb081", user="test", passwd="test", db="logdb", port=3306, charset='utf8')

    """
    Finance = MySQLdb.connect(host="tb007", user="ead", passwd="ea89,d24", db="eadb3", port=3306, charset='utf8')
    AdClick = MySQLdb.connect(host="tb015", user="eadonline4nb", passwd="new1ife4Th1sAugust", db="eadb7", port=3306, charset='utf8')
    AdUpdate  = MySQLdb.connect(host="tb015", user="eadonline4nb", passwd="new1ife4Th1sAugust", db="eadb8", port=3306, charset='utf8')
    AdPublish = MySQLdb.connect(host="tb015", user="eadonline4nb", passwd="new1ife4Th1sAugust", db="eadb1", port=3306, charset='utf8')
    """

    table_conn_dict = {
        "Sponsor": AdPublish,
        "SPONSOR_BALANCE": Finance,
        "SPONSOR_MONTHLY_DISCOUNT": Finance,
        "SPONSOR_MONTHLY_DISCOUNT_DETAIL": Finance,
        "SPONSOR_MONTHLY_CONTRACT": Finance,
        "SPONSOR_MONTHLY_SETTLEMENT": Finance,
        "SPONSOR_ACCOUNT_HISTORY": Finance,
        "Log_DA": LogDB,
        "Log_DA_SIM": LogDB,
        "CLICK": AdClick,
        "AD_CLICK_CHARGE_UP_PROGRESS": Finance,
        "SPONSOR_BALANCE_CHANGE" : AdUpdate,
    }

    table_desc_dict = {
        "Sponsor": "广告商信息表",
        "SPONSOR_BALANCE": "广告商余额表",
        "SPONSOR_MONTHLY_DISCOUNT": "月结广告商折扣信息",
        "SPONSOR_MONTHLY_DISCOUNT_DETAIL": "月结广告商折扣明细信息",
        "SPONSOR_MONTHLY_CONTRACT": "月结广告商合同信息",
        "SPONSOR_MONTHLY_SETTLEMENT": "广告商月结信息",
        "SPONSOR_ACCOUNT_HISTORY": "广告商账务明细表",
        "Log_DA": "logdb DA的日志信息",
        "Log_DA_SIM": "logdb DA_SIM的日志信息",
        "SPONSOR_BALANCE_CHANGE": "广告商余额变化表",
    }

    table_value_readable_dict = {
        "SPONSOR_ACCOUNT_HISTORY": {
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
        },

        "SPONSOR_BALANCE": {
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
        },

        "SPONSOR_MONTHLY_CONTRACT": {
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
                1 : "待审核",
                2 : "审核不通过",
                3 : "审核通过",
                5 : "无效",
            },
        },

        "SPONSOR_MONTHLY_DISCOUNT_DETAIL": {
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
        },

        "SPONSOR_MONTHLY_DISCOUNT": {
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
            }
        },

        "SPONSOR_MONTHLY_SETTLEMENT": {
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
        },

        "SPONSOR_BALANCE_CHANGE": {
            "CHANGE_TYPE": {
                1: "增加",
                2: "减少",
            },
        }
    }

    @staticmethod
    def get(table_name):
        return DBConnection.table_conn_dict.get(table_name, None)
