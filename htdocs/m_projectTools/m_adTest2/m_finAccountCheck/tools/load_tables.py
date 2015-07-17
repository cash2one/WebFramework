#!/usr/bin/python

from truncate_tables import *
import MySQLdb
import time
import struct

host, user, passwd, dbname, port = get_config_list("./jar/hibernate.properties")
conn = MySQLdb.connect(host=host, user=user, passwd=passwd, db=dbname, port=int(port), charset="utf8")
cursor = conn.cursor()

def get_line_fields(file_path):
    idx = 0
    for line in open(file_path):
        if idx == 0:
            idx = 1
            continue

        line = line.strip()
        fields = line.split(",")
        yield fields

def get_revenue_month(file_name_part):
    year, month = file_name_part.split("_")
    month = "%02d" % int(month)
    return year + month

def get_next_month(month_str):
    year, month = struct.unpack("4s2s", month_str)
    year = int(year)
    month = int(month)
    if month == 12:
        month == 1
        year += 1
    else:
        month += 1
    return "%d%02d" % (year, month)

def set_sponsor_format_fields(fields):
    for idx, field in enumerate(fields):
        if field == '-':
            if idx in (2, 7, 8, 9, 10, 11, 15):
                fields[idx] = "0"
            elif idx in (3, 4, 7, 8, 14, 19):
                fields[idx] = "''"
            elif idx in (5, 6):
                fields[idx] = "'%s'" % time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())
        elif idx in (3, 4, 5, 6, 16, 19):
            fields[idx] = "'%s'" % field
        else:
            fields[idx] = "%s" % field
        
    if fields[0] == "SUM":
        fields[0] = "1"
    elif fields[0] == "DETAIL":
        fields[0] = "2"
    else:
        raise Exception("Invalid log_type: %s:%s" % (csv_file, fields[0]))

def set_agent_format_fields(fields):
    if fields[0] == "SUM":
        fields[0] = "1"
        balance = fields[2]
        fields[2] = "0"
        fields.append("'" + time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + "'")
        fields.append("'" + time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + "'")
        fields.append("0")
        fields.append(balance)
        fields.append("0")
        return
        
    fields[2], fields[3], fields[4], fields[5], fields[6] = fields[6], fields[4], fields[5], fields[3], fields[2]
    for idx, field in enumerate(fields):
        if field == '-':
            if idx in (1, 6):
                fields[idx] = "0"
        elif idx in (3, 4):
            fields[idx] = "'%s'" % field
        else:
            fields[idx] = "%s" % field
        
    fields[0] = "2"

def insert_line(sql_cmd):
    global cursor
    cursor.execute(sql_cmd)

def load_sponsor_into_tables(file_name_part):
    global conn
    table_name = "SPONSOR_REVENUE_MONTHLY"
    csv_file = "./data/sponsor_month_%s.csv" % file_name_part
    revenue_month = get_revenue_month(file_name_part)
    
    for fields in get_line_fields(csv_file):
        set_sponsor_format_fields(fields)
        sql_cmd = "insert into %s values(%s)" % (table_name, '"",%s,' % revenue_month + ",".join(fields))
        insert_line(sql_cmd)

    current_month_str = get_next_month(revenue_month)
    time_str = "'%s'" % time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())
    sql_cmd = "insert into %s values(%s)" % (table_name, '"",%s,' % current_month_str + ",".join(("1", "0", "0", "''", "''", '%s' % time_str, '%s' % time_str, "0", "0", "0", "0", "0", "0", "0", "''", "0", "''", "0", "0", "''")))
    insert_line(sql_cmd)
    conn.commit()

def load_agent_into_tables(file_name_part):
    global conn
    table_name = "AGENT_REVENUE_MONTHLY"
    csv_file = "./data/agent_month_%s.csv" % file_name_part
    revenue_month = get_revenue_month(file_name_part)
    
    for fields in get_line_fields(csv_file):
        set_agent_format_fields(fields)
        sql_cmd = "insert into %s values(%s)" % (table_name, '"",%s,' % revenue_month + ",".join(fields))
        insert_line(sql_cmd)

    current_month_str = get_next_month(revenue_month)
    time_str = "'%s'" % time.strftime("%Y-%m-%d %H:%M:%S", time.localtime())
    sql_cmd = "insert into %s values(%s)" % (table_name, '"",%s,' % current_month_str + ",".join(("1", "0", "0", '%s' % time_str, '%s' % time_str, "0", "0", "0")))
    insert_line(sql_cmd)
    conn.commit()

def log_it(msg):
    print time.strftime("%Y-%m-%d %H:%M:%S"), msg

def load_data(month_str_list):
    global conn, cursor
    for month_str in month_str_list:
        log_it("begin to load data: %s" % month_str)
        load_sponsor_into_tables(month_str)
        log_it("finished for sponsor")
        load_agent_into_tables(month_str)
        log_it("finished for agent")

    cursor.close()
    conn.close() 
            
if __name__ == "__main__":
    # month_str_list = ("2012_5", "2012_6", "2012_7", "2012_8", "2012_9", "2012_10", "2012_11", "2012_12", "2013_1", "2013_2", "2013_3", "2013_4", "2013_5")
    month_str_list = ("2012_5", "2012_6")
    load_data(month_str_list)
