#!/usr/bin/python

import sys
sys.path.append('.')

from Util import *

if len(sys.argv) != 2:
    raise Exception, "%s days_count_delta" % sys.argv[0]

time_field_dict = {
    "SPONSOR_ACCOUNT_HISTORY": ("OPERATE_TIME", "AUDIT_TIME"),
    "SPONSOR_BALANCE": ("LAST_MOD_TIME",),
    "SPONSOR_MONTHLY_CONTRACT": ("CONTRACT_START_DATE", "CONTRACT_END_DATE", "CREATE_TIME", "LAST_UPDATE_TIME"),
    "SPONSOR_MONTHLY_DISCOUNT": ("CREATE_TIME", "AUDIT_TIME"),
    "SPONSOR_MONTHLY_SETTLEMENT": ("SETTLEMNET_MONTH", "CREATE_TIME", "SETTLEMENT_TIME", "AUDIT_TIME"),
    "Log_DA": ("statDay", ),
    "Log_DA_SIM": ("statDay", ),
}

for table_name, time_field_name in time_field_dict.items():
    tableObj = TableListUtil.get_table(table_name)
    TableUtil.read_table(tableObj)
    TableUtil.update_time(tableObj, time_field_name, int(sys.argv[1]))

    if table_name == "Log_DA":
        for row in tableObj.rows:
            stat_day = RowUtil.get_field_value(row, "statDay")
            RowUtil.set_field_value(row, "statYear", stat_day[:4])
            RowUtil.set_field_value(row, "statMonth", stat_day[:6])
    
    TableUtil.delete_table(tableObj)
    TableUtil.write_table(tableObj)
