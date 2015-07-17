#!/usr/bin/python
from BaseTable import *
from SponsorAccountHistoryTable import *
from SponsorBalanceTable import *
from SponsorMonthlyContractTable import *
from SponsorMonthlyDiscountDetailTable import *
from SponsorMonthlyDiscountTable import *
from SponsorMonthlySettlementTable import *

def output_html_str(sponsor_id, selected_table_list, conf_str):
    BaseTable.setConf(conf_str)
    temp_list = []

    for table in SponsorBalanceTable(), SponsorMonthlyContractTable(), SponsorMonthlyDiscountTable(), SponsorMonthlyDiscountDetailTable(), SponsorMonthlySettlementTable(), SponsorAccountHistoryTable():
        if table.table_name not in selected_table_list:
            continue

        table.del_sponsor_rows(sponsor_id)


if __name__ == "__main__":
    sponsor_id     = sys.argv[1]
    table_list_str = sys.argv[2] 
    conf_str       = sys.argv[3]
    table_list = table_list_str.split(",")
    output_html_str(int(sponsor_id), table_list, conf_str)
