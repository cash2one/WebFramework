#!/usr/bin/python
#encoding:utf-8

import MySQLdb
import time
import os
import sys

def get_current_timestamp():
    return int(time.time())

def update_time(time_str):
    update_cmd = "sudo date -s '%s' > /dev/null" % time_str
    os.system(update_cmd)

def get_sql_insert_values_str(data_tuple):
    temp_list = []
    for one in data_tuple:
        if type(one) == str:
            temp_list.append("'%s'" % one)
        else:
            temp_list.append("%s" % one)
    return ",".join(temp_list)

def get_clone_list(data_list):
    temp_list = []
    for one in data_list:
        temp_list.append(one)
    return temp_list

def update_data(conn, sql_values_str, table_name, clean_table_firstly = False):
    cursor = conn.cursor()
    if clean_table_firstly:
        delete_cmd_sql = "delete from %s" % table_name
        cursor.execute(delete_cmd_sql)

    insert_cmd_sql = "insert into %s values (%s)" %  (table_name, sql_values_str)
    cursor.execute(insert_cmd_sql)

    conn.commit()
    cursor.close()

def get_click_max_id_info(conn):
    cursor = conn.cursor()
    query_sql = "select ID, COMMIT_TIME from CLICK order by ID desc limit 0,1"
    cursor.execute(query_sql)
    result = cursor.fetchone()
    cursor.close()
    if not result:
        return (0, "1970-01-01")
    return result

def get_click_month_cost(conn, date_str):
    cursor = conn.cursor()
    query_sql = "SELECT SUM(ACTU_COST) FROM CLICK WHERE COMMIT_TIME LIKE \"%s%%\"" % date_str
    cursor.execute(query_sql)
    result = cursor.fetchone()
    if result[0] == None:
        return 0
    return result[0]

def clean_table(conn, table_name):
    cursor = conn.cursor()
    clean_table_sql = "delete from %s" % (table_name)
    cursor.execute(clean_table_sql)
    conn.commit()
    cursor.close()
    print "table(%s) cleaned" % table_name

def reset_ad_click_charge_up_progress_table(conn):
    cursor = conn.cursor()
    reset_sql = "update AD_CLICK_CHARGE_UP_PROGRESS set PROGRESS_ACI_ID = 0, MAX_LOG_TIME = 0;"
    cursor.execute(reset_sql)
    conn.commit()
    cursor.close()

class TableUpdate:
    def __init__(self, sponsor_id, click_count, click_min_cnt_interval): 
        self.adclick_db = MySQLdb.connect(host='tb081', user='test', passwd='test', db='adclickDB', port=3306, charset='utf8', use_unicode=True)
        self.finance_db = MySQLdb.connect(host='tb081', user='test', passwd='test', db='eadb3', port=3306, charset='utf8', use_unicode=True)
        self.log_db     = MySQLdb.connect(host='tb081', user='test', passwd='test', db='logdb', port=3306, charset='utf8', use_unicode=True)

        self.sponsor_id = sponsor_id
        self.click_count = click_count
        self.click_min_cnt_interval = click_min_cnt_interval
        # timestamp_start_str = "2002-12-28 22:43:29" ### 时间日期起点
        # print "时间起点:%s" % timestamp_start_str
        # struct_time = time.strptime(time_start_str, "%Y-%m-%d %H:%M:%S")
        # self.timestamp_start  = int(time.mktime(struct_time))
        
        self.max_click_id, self.pre_date = get_click_max_id_info(self.adclick_db)
        self.pre_date = str(self.pre_date)[:10]

    def reset_tables(self):
        # print "###### clicks with reset tables #######"
        clean_table(self.adclick_db, "CLICK")
        clean_table(self.log_db, "Log_DA")
        clean_table(self.log_db, "Log_DA_SIM")
        clean_table(self.finance_db, "AD_CAMPAIGN_TODAY_COST")
        clean_table(self.finance_db, "SPONSOR_TODAY_COST")
        clean_table(self.finance_db, "SPONSOR_DAILY_COST")
        clean_table(self.finance_db, "AD_CAMPAIGN_TODAY_COST")
        clean_table(self.finance_db, "AGENT_DAILY_COST")
        clean_table(self.finance_db, "ACCOUNT_DAILY_REPORT")
        clean_table(self.finance_db, "AGENT_ACCOUNT_HISTORY")
        clean_table(self.finance_db, "AGENT_BALANCE")
        clean_table(self.finance_db, "AGENT_MONTHLY_REPORT")
        clean_table(self.finance_db, "SPONSOR_MONTHLY_REPORT")
        reset_ad_click_charge_up_progress_table(self.finance_db)

        # reset time
        # current_time_str = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime(self.timestamp_start))
        # print "udpate system time: %s" % current_time_str
        # update_time(current_time_str)
    
    def _update_table(self, conn, table_name, list):
        sql_insert_values_str = get_sql_insert_values_str(list)
        update_data(conn, sql_insert_values_str, table_name)

    def do_work(self):
        # 要修改commit_time, 用于判断是否有日期变更
        actu_cost = 1000 #单位：分
        click_data = (1, self.sponsor_id, 169, 277, 349, 730895, 14, 14039, 1, 0.61688, 24.6755, 40, actu_cost, "119.141.88.224", "119.141.88.224", 898334343343, "2009-04-06 11:57:11", "2009-04-06 11:58:29", "2009-04-06 11:59:30", "http://www.youdao.com", "http://www.baidu.com", "@@", 34343433, 0)
        for i in range(self.click_count):
            temp_list = get_clone_list(click_data)
            temp_list[0]  = self.max_click_id + 1 + i # ID
            temp_list[12] = actu_cost # actu_cost
            current_ts = get_current_timestamp() + 60 * self.click_min_cnt_interval
            commit_time = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime(current_ts))
            update_time(commit_time)
            temp_list[18] = commit_time
            
            # 有日期变更
            if commit_time[:10] != self.pre_date:
                if self.pre_date != "1970-01-01":
                    self._update_logdb_tables()

            self._update_table(self.adclick_db, "CLICK", temp_list)
            print "[%s] ads clicked, ID(%s), actu_cost(%s)" % (commit_time, temp_list[0], temp_list[12])
            self.pre_date = commit_time[:10]

    def do_work2(self, time_cost_tuple):
        # 要修改commit_time, 用于判断是否有日期变更
        actu_cost = 1000 #单位：分
        click_data = (1, self.sponsor_id, 169, 277, 349, 730895, 14, 14039, 1, 0.61688, 24.6755, 40, actu_cost, "119.141.88.224", "119.141.88.224", 898334343343, "2009-04-06 11:57:11", "2009-04-06 11:58:29", "2009-04-06 11:59:30", "http://www.youdao.com", "http://www.baidu.com", "@@", 34343433, 0)
        i = 0
        for commit_time, actu_cost in time_cost_tuple: # commit_time like "2009-12-11 11:11:00"
            temp_list = get_clone_list(click_data)
            temp_list[0]  = self.max_click_id + 1 + i # ID
            i += 1
            temp_list[12] = actu_cost # actu_cost
            update_time(commit_time)
            temp_list[18] = commit_time
            
            # 有日期变更
            if commit_time[:10] != self.pre_date:
                if self.pre_date != "1970-01-01":
                    self._update_logdb_tables()

            self._update_table(self.adclick_db, "CLICK", temp_list)
            print "[%s] ads clicked, ID(%s), actu_cost(%s)" % (commit_time, temp_list[0], temp_list[12])
            self.pre_date = commit_time[:10]

    def _update_logdb_tables(self):
        log_da_data = (2012, 201212, 20121203, 4703, 27, self.sponsor_id, 61170, 301501, 1015427, 1, 0, 0, 1, 1000, 0, 0, "20121221-334343-1")
        log_da_sim_data = (2012, 2012, 201203, 4091, 94, self.sponsor_id, 1, 0, 2, 55, 0, 0, 0, "20121203-10132-1")

        date_str = self.pre_date.replace("-", "")
        click_month_sum_cost = get_click_month_cost(self.adclick_db, self.pre_date)

        temp_list = get_clone_list(log_da_data)
        temp_list[0]  = date_str[:4] # statYear
        temp_list[1]  = date_str[:6] # statMonth
        temp_list[2]  = date_str[:8] # statDay
        temp_list[13] = click_month_sum_cost
        temp_list[-1] = self.pre_date
        self._update_table(self.log_db, "Log_DA", temp_list)
        print "### save clicks(%s) into Log_DA, sum_cost(%s)" % (self.pre_date, temp_list[13])

        temp_list = get_clone_list(log_da_sim_data)
        temp_list[0]  = date_str[:4] # statYear
        temp_list[1]  = date_str[:6] # statMonth
        temp_list[2]  = date_str[:8] # statDay
        temp_list[9]  = click_month_sum_cost
        temp_list[-1] = self.pre_date
        self._update_table(self.log_db, "Log_DA_SIM", temp_list)
        print "### save clicks(%s) into Log_DA_SIM, sum_cost(%s)" % (self.pre_date, temp_list[9])

if __name__ == "__main__":
    finance_service_host = "nc111"

    if finance_service_host not in os.environ["HOSTNAME"]:
        print "请在%s上运行" % finance_service_host 
        sys.exit(1)

    param1 = (len(sys.argv) >= 2) and sys.argv[1]

    table_update = TableUpdate(215623, 20, 1) # sponsor_id, click_counts, click_minutes_interval
    if param1 == "reset":
        # 清除模拟点击的存储表
        table_update.reset_tables()

    elif param1 == "update" and len(sys.argv) == 3:
        # 修改系统时间，传入参数单位是天
        seconds_delta = int(sys.argv[2]) * 24 * 60 * 60
        current_ts = get_current_timestamp() + seconds_delta
        current_time_str = time.strftime("%Y-%m-%d %H:%M:%S", time.localtime(current_ts))
        print "update system time: %s" % current_time_str
        update_time(current_time_str)

    elif param1 == "set" and len(sys.argv) == 3:
        # 修改系统时间
        current_time_str = sys.argv[2]
        print "set system time as %s" % current_time_str
        update_time(current_time_str)
        
    else:
        # 模拟点击
        table_update.do_work()

        case_temp  = (("2012-07-22 10:00:01", 6220), ("2012-07-22 10:00:02", 1000), ("2012-07-22 10:00:03", 1000), ("2012-07-22 10:00:04", 1000), ("2012-07-22 10:00:05", 1000), ("2012-07-23 10:00:06", 0), ("2012-07-31 10:00:01", 6220), ("2012-07-31 10:00:02", 1000), ("2012-07-31 10:00:03", 1000), ("2012-07-31 10:00:04", 1000), ("2012-07-31 10:00:05", 1000), ("2012-08-01 10:00:06", 0))

        case_1_1 = (("2012-01-02 10:00:01", 1000), ("2012-01-02 10:00:02", 1000), ("2012-01-02 10:00:03", 1000), ("2012-01-02 10:00:04", 1000), ("2012-01-02 10:00:05", 1000), ("2012-01-03 10:00:05", 0))
        case_1_2 = (("2012-01-02 10:00:01", 6000), ("2012-01-02 10:00:02", 1000), ("2012-01-02 10:00:03", 1000), ("2012-01-02 10:00:04", 1000), ("2012-01-02 10:00:05", 1000), ("2012-01-03 10:00:05", 0))
        case_1_3 = (("2012-01-02 10:00:01", 16000), ("2012-01-02 10:00:02", 1000), ("2012-01-02 10:00:03", 1000), ("2012-01-02 10:00:04", 1000), ("2012-01-02 10:00:05", 1000), ("2012-01-03 10:00:05", 0))

        case_2_1 = (("2012-01-02 10:00:01", 1000), ("2012-01-02 10:00:02", 1000), ("2012-01-02 10:00:03", 1000), ("2012-01-02 10:00:04", 1000), ("2012-01-02 10:00:05", 1000), ("2012-01-03 10:00:06", 0))
        case_2_1_2 = (("2012-02-04 10:00:01", 16000), ("2012-02-04 10:00:02", 1000), ("2012-02-04 10:00:03", 1000), ("2012-02-04 10:00:04", 1000), ("2012-02-04 10:00:05", 1000), ("2012-02-05 10:00:06", 0))

        case_3_1 = (("2012-01-02 10:00:01", 16000), ("2012-01-02 10:00:02", 1000), ("2012-01-02 10:00:03", 1000), ("2012-01-02 10:00:04", 1000), ("2012-01-02 10:00:05", 1000), ("2012-01-03 10:00:06", 0))
        case_3_1_2 = (("2012-02-04 10:00:01", 16000), ("2012-02-04 10:00:02", 1000), ("2012-02-04 10:00:03", 1000), ("2012-02-04 10:00:04", 1000), ("2012-02-04 10:00:05", 1000), ("2012-02-05 10:00:06", 0))

        case_4_2 = (("2012-02-01 10:00:01", 326000), ("2012-02-01 10:00:02", 1000), ("2012-02-01 10:00:03", 1000), ("2012-02-01 10:00:04", 1000), ("2012-02-01 10:00:05", 1000), ("2012-02-02 10:00:06", 0))
        case_4_2_2 = (("2012-03-04 10:00:01", 326000), ("2012-03-04 10:00:02", 1000), ("2012-03-04 10:00:03", 1000), ("2012-03-04 10:00:04", 1000), ("2012-03-04 10:00:05", 1000), ("2012-03-05 10:00:06", 0))
        case_4_2_3 = (("2012-04-04 10:00:01", 326000), ("2012-04-04 10:00:02", 1000), ("2012-04-04 10:00:03", 1000), ("2012-04-04 10:00:04", 1000), ("2012-04-04 10:00:05", 1000), ("2012-04-05 10:00:06", 0))

        #table_update.reset_tables()
        #table_update.do_work2(case_2_1)
        #table_update.do_work2(case_2_1_2)
        #table_update.do_work2(case_3_1)
        #table_update.do_work2(case_3_1_2)
        #table_update.do_work2(case_4_2)
        #table_update.do_work2(case_4_2_2)
        #table_update.do_work2(case_4_2_3)
