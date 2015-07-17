#!/usr/bin/python
#encoding:utf-8

import os
import sys
sys.path.append(".")

from MySqlAdapter import *
from Table import *

def time_update(seconds):
    myList = (
        (ClickTable, ClickTable(), ("IMPR_TIME", "CLICK_TIME", "COMMIT_TIME")),
        (SponsorAccountHistoryTable, SponsorAccountHistoryTable(), ("OPERATE_TIME", "AUDIT_TIME")),
        (SponsorBalanceTable, SponsorBalanceTable(), ("LAST_MOD_TIME",)),
        (SponsorMonthlyContractTable, SponsorMonthlyContractTable(), ("CONTRACT_START_DATE", "CONTRACT_END_DATE", "CREATE_TIME", "LAST_UPDATE_TIME")),
        (SponsorMonthlyDiscountTable, SponsorMonthlyDiscountTable(), ("CREATE_TIME", "AUDIT_TIME")),
        (SponsorMonthlyDiscountDetailTable, SponsorMonthlyDiscountDetailTable(), ("CREATE_TIME", "AUDIT_TIME")),
        (SponsorMonthlySettlementTable, SponsorMonthlySettlementTable(), ("SETTLEMENT_MONTH", "CREATE_TIME", "SETTLEMENT_TIME", "AUDIT_TIME")),
    )

    for slist in myList:
        for field_name in myList[2]:
            TableAdapter.update(myList[0], myList[1], 
            TableAdapter.update(AdClickChargeUpProgressTable, table, "PROGRESS_ACI_ID", 0, "REMARK", "'fin2'")

if __name__ == "__main__":
    objList = (
