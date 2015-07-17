#!/usr/bin/python

from DBConnection import *

def clear_table(table_name):
    conn = DBConnection.get(table_name)
    cursor = conn.cursor()
    sql_cmd = "delete from %s" % table_name
    cursor.execute(sql_cmd)
    conn.commit()

for table_name in ("SPONSOR_BALANCE", "SPONSOR_MONTHLY_CONTRACT", "SPONSOR_MONTHLY_DISCOUNT", "SPONSOR_MONTHLY_DISCOUNT_DETAIL", "SPONSOR_MONTHLY_SETTLEMENT", "SPONSOR_ACCOUNT_HISTORY", "Log_DA", "Log_DA_SIM", "CLICK", "SPONSOR_BALANCE_CHANGE"):
    clear_table(table_name)
