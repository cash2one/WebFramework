#!/usr/bin/python
#encoding: utf-8

import re
from datetime import datetime
from DBFactory import *
import time
reload(sys)
import sys
sys.setdefaultencoding("utf-8")

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

class BaseTable:

    FinanceDB   = None
    AdClickDB   = None
    AdUpdateDB  = None
    AdPublishDB = None

    @staticmethod
    def setConf(conf_str):
        DBFactory.setConf(conf_str)
        BaseTable.FinanceDB   = DBFactory.getConn("finance")
        BaseTable.AdClickDB   = DBFactory.getConn("adclick")
        BaseTable.AdUpdateDB  = DBFactory.getConn("adupdate")
        BaseTable.AdPublishDB = DBFactory.getConn("adpublish")
        BaseTable.LogDB       = DBFactory.getConn("logdb")
        
    def __init__(self, useReadableValue = True):
        # should be override in derived classes
        self.db_conn = None
        self.table_name  = None
        self.table_desc  = None
        self.sponsor_id_key_name = None
        self.value_dict = None # save readable value for some columns
        self.useReadableValue = useReadableValue

        self._my_init()
        self.key_name_list = []
        self._read_schema()

    def _read_schema(self):
        schema_file = "conf/" + self.table_name  + ".schema"
        for line in open(schema_file).read().splitlines():
            line = line.strip()
            if not line: continue
            if line[0] == "#": continue

            key, value = re.split("\s*:\s*", line, 1) 
            self.key_name_list.append((key, value))

    def _execute_sql(self, sql_cmd, isCommit = False):
        cursor = self.db_conn.cursor()
        cursor.execute(sql_cmd)
        if isCommit:
            self.db_conn.commit()
        results = cursor.fetchall()
        cursor.close()
        return results

    def _get_readable_row(self, row):
        if self.useReadableValue == False:
            return row

        new_row = []
        if (self.value_dict == None):
            return row

        for idx, td in enumerate(row):
            key = self.key_name_list[idx][0]
            if not self.value_dict.has_key(key): 
                new_row.append(td)
                continue

            subDict = self.value_dict.get(key)
            if subDict.has_key("*"):
                td = subDict["*"](td)
                new_row.append(td)
            else:
                td = subDict.get(td, td)
                new_row.append(td)

        return new_row

    def get_sponsor_rows(self, sponsor_id):
        key_list = (map(lambda x: x[0], self.key_name_list))
        key_str  = ",".join(key_list)
        if self.table_name not in ("SPONSOR_MONTHLY_DISCOUNT_DETAIL", "AD_CLICK_CHARGE_UP_PROGRESS"):
            sql_cmd = "select %s from %s where %s=%s;" % (key_str, self.table_name, self.sponsor_id_key_name, sponsor_id)
        else: 
            sql_cmd = "select %s from %s;" % (key_str, self.table_name)
        return self._execute_sql(sql_cmd)

    def get_readable_sponsor_rows(self, sponsor_id):
        rows = list(self.get_sponsor_rows(sponsor_id))
        for idx, row in enumerate(rows):
            rows[idx] = self._get_readable_row(row)
        return rows

    def del_sponsor_rows(self, sponsor_id):
        if self.table_name not in ("SPONSOR_MONTHLY_DISCOUNT_DETAIL", "AD_CLICK_CHARGE_UP_PROGRESS"):
            sql_cmd = "delete from %s where %s=%s;" % (self.table_name, self.sponsor_id_key_name, sponsor_id)
        else:
            sql_cmd = "delete from %s;" % (self.table_name)
        self._execute_sql(sql_cmd, True)
