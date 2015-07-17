#!/usr/bin/python
#encoding:utf-8

from TableList import *

import sys
reload(sys)
sys.setdefaultencoding('utf-8')

def get_sponsor_daily_cost_data():
    ret_list = []
    conn = SponsorDailyCostCopyTable.get_conn()
    cursor = conn.cursor()
    sql_cmd = "select SPONSOR_ID, SDC_DATE, COST_BEFORE_DISCOUNT from %s where SPONSOR_ID < 10000 ORDER BY SDC_DATE" % SponsorDailyCostCopyTable.table_name 
    cursor.execute(sql_cmd)
    for sponsor_id, date, cost in cursor.fetchall():
        ret_list.append((str(sponsor_id), str(date), str(cost)))
    cursor.close()
    conn.close()
    return ret_list

def get_sponsor_today_cost_data():
    ret_list = []
    conn = SponsorTodayCostCopyTable.get_conn()
    cursor = conn.cursor()
    sql_cmd = "select SPONSOR_ID, STC_DATE, TOTAL_COST from %s where SPONSOR_ID < 10000" % SponsorTodayCostCopyTable.table_name
    cursor.execute(sql_cmd)
    for sponsor_id, date, cost in cursor.fetchall():
        ret_list.append((str(sponsor_id), str(date), str(cost)))
    cursor.close()
    conn.close()
    return ret_list

def clean_tables():
    conn = SponsorTodayCostTable.get_conn()
    cursor = conn.cursor()
    del_spon_today_cost = "delete from %s" % SponsorTodayCostTable.table_name
    cursor.execute(del_spon_today_cost)
    conn.commit()

    conn = SponsorDailyCostTable.get_conn()
    del_spon_daily_cost = "delete from %s" % SponsorDailyCostTable.table_name
    cursor.execute(del_spon_daily_cost)
    conn.commit()
    cursor.close()
    conn.close()

def update_log_da(data_list, is_delete = True):
    conn = LogDaTable.get_conn() 
    cursor = conn.cursor();

    if is_delete:
        sql_cmd_del = "delete from %s" % LogDaTable.table_name
        cursor.execute(sql_cmd_del)

    for sponsor_id, date_str, cost in data_list:
        year, mon, day = date_str.split("-")  
        sql_cmd = "insert into %s values (%s, %s, %s, 4703, 27, %s, 61170, 301501, 1015427, 1, 0, 0, 1, %s, 0, 0, '%s')" % (LogDaTable.table_name, year, year + mon, year + mon + day, sponsor_id, cost, year + "-" + mon + "-" + day + "-" + sponsor_id)
        cursor.execute(sql_cmd)
    
    conn.commit()
    cursor.close()
    conn.close()
  
def update_log_da_sim(data_list, is_delete = True):
    conn = LogDaSimTable.get_conn() 
    cursor = conn.cursor();

    if is_delete:
        sql_cmd_del = "delete from %s" % LogDaSimTable.table_name
        cursor.execute(sql_cmd_del)

    for sponsor_id, date_str, cost in data_list:
        year, mon, day = date_str.split("-")  
        sql_cmd = "insert into %s values (%s, %s, %s, 4703, 94, %s, 1, 0, 2, %s, 0, 0, 0, '%s')" % (LogDaSimTable.table_name, year, year + mon, year + mon + day, sponsor_id, cost, year + "-" + mon + "-" + day + "-" + sponsor_id)
        cursor.execute(sql_cmd)
    
    conn.commit()
    cursor.close()
    conn.close()

def update_click(data_list, is_delete = True):
    conn = ClickTable.get_conn() 
    cursor = conn.cursor();

    if is_delete:
        sql_cmd_del = "delete from %s" % ClickTable.table_name
        cursor.execute(sql_cmd_del)

    index = 1
    for sponsor_id, date_str, cost in data_list:
        sql_cmd = "insert into %s values (%s, %s, 169, 277, 349, 730895, 14, 14039, 1, 0.61688, 24.6755, 40, %s, '119.141.88.224', '119.141.88.224', 898334343343, '%s 11:57:11', '%s 11:58:29', '%s 15:37:55', 'http://www.youdao.com', 'http://www.baidu.com', '@@', 34343433, 0)" % (ClickTable.table_name, index, sponsor_id, cost, date_str, date_str, date_str)
        cursor.execute(sql_cmd)
        index += 1
    
    conn.commit()
    cursor.close()
    conn.close()

def reset_ad_click_charge_up_progress():
    conn = AdClickChargeUpProgressTable.get_conn()
    cursor = conn.cursor()
    
    sql_cmd = "update %s set PROGRESS_ACI_ID = 31 where FIN_SYS_ID = 1" % AdClickChargeUpProgressTable.table_name
    cursor.execute(sql_cmd)

    sql_cmd = "update %s set PROGRESS_ACI_ID = 26 where FIN_SYS_ID = 2" % AdClickChargeUpProgressTable.table_name
    cursor.execute(sql_cmd)
    
    
    conn.commit()
    cursor.close()
    conn.close()

def run():
    # get data where sponsor_id < 1000
    print "read data from SPONSOR_TODAY_COST_copy"
    today_list = get_sponsor_today_cost_data()

    print "read data from SPONSOR_DAILY_COST_copy"
    daily_list = get_sponsor_daily_cost_data()
    
    print "merge data in today and daily table"
    daily_list.extend(today_list)

    # clear data in SPONSOR_TODAY_COST & SPONSOR_DAILY_COST
    print "clear data in SPONSOR_TODAY_COST, SPONSOR_DAILY_COST"
    clean_tables()

    delete_before_insert = True
    print "insert data into Log_DA"
    update_log_da(daily_list, delete_before_insert)
    print "insert data into Log_DA_SIM"
    update_log_da_sim(daily_list, delete_before_insert)
    print "insert data into CLICK"
    update_click(daily_list, delete_before_insert)
    print "reset AD_CLICK_CHARGE_UP_PROGRESS"
    reset_ad_click_charge_up_progress()
    

if __name__ == "__main__":
    run()
