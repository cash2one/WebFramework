#!/usr/bin/python

import MySQLdb
from Config import *

def get_click_db_conn():
    return MySQLdb.connect(host = CLICK_DB_HOST, user = CLICK_DB_USERNAME, passwd = CLICK_DB_PASSWORD, db = CLICK_DB_NAME, port = CLICK_DB_PORT, charset = 'utf8', use_unicode = True)


def update_table(sql_cmd):
    conn = get_click_db_conn()
    cursor = conn.cursor()
    cursor.exexute(sql_cmd)
    conn.commit()
    cursor.close()
    conn.close()
    

def clear_uncharged_clicks_table()
    sql_cmd = "delete from uncharged_clicks"
    update_table(sql_cmd)


def clear_fraud_click()
    sql_cmd = "delete from FRAUD_CLICK"
    update_table(sql_cmd)


def clear_filterarg_table(self):
    sql_cmd = "delete from FILTERARG"
    update_table(sql_cmd)


class FilterArgRow:
    def __init__(self, filter_name, server_id, value, cycle, fkey):
        self.filter_id   = None
        self.user_id     = 2 
        self.filter_name = filter_name
        self.server_id   = server_id
        self.value       = value
        self.set_time    = "2010-09-13 17:00:00"
        self.cycle       = cycle
        self.fkey        = fkey
        self.comment     = "comment"


class FilterArgTableClass:
    def __init__(self):
        self.filter_arg_row_list = []

    def add_row(self, row):
        # append FilterArgRow into list
        self.filter_arg_row_list.append(row)

    def update_table(self):
        conn = get_click_db_conn()
        cursor = conn.cursor()

        for row in self.filter_arg_row_list:
            sql_cmd = "insert into FILTERARG(USER_ID, FILTER_NAME, SERVER_ID, VALUE, SET_TIME, CYCLE, FKEY, COMMENT) values (%s, %s, %s, %s, '%s', %s, %s, %s)" % (row.user_id, row.filter_name, row.server_id, row.value, row.set_time, row.cycle, row.fkey, row.comment)
            cursor.exexute(sql_cmd)

        conn.commit()
        cursor.close()
        conn.close()
