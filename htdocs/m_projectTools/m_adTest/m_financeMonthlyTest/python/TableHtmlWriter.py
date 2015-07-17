#!/usr/bin/python
from BaseTable import *
from SponsorTable import *
from LogDATable import *
from LogDASimTable import *
from SponsorAccountHistoryTable import *
from SponsorBalanceTable import *
from SponsorMonthlyContractTable import *
from SponsorMonthlyDiscountDetailTable import *
from SponsorMonthlyDiscountTable import *
from SponsorMonthlySettlementTable import *
from ClickTable import *
from AdClickChargeUpProgressTable import *
from SponsorTodayCostTable import *
from SponsorDailyCostTable import *
from SponsorBalanceChangeTable import *

def output_html_str(sponsor_id, selected_table_list, conf_str, useReadable):
    BaseTable.setConf(conf_str)
    temp_list = []

    for table in SponsorTable(useReadable), LogDATable(useReadable), LogDASimTable(useReadable), SponsorBalanceTable(useReadable), SponsorMonthlyContractTable(useReadable), SponsorMonthlyDiscountTable(useReadable), SponsorMonthlyDiscountDetailTable(useReadable), SponsorMonthlySettlementTable(useReadable), SponsorAccountHistoryTable(useReadable), ClickTable(useReadable), AdClickChargeUpProgressTable(useReadable), SponsorTodayCostTable(useReadable), SponsorDailyCostTable(useReadable), SponsorBalanceChangeTable(useReadable):
        if table.table_name not in selected_table_list:
            continue

        rows = table.get_readable_sponsor_rows(sponsor_id)

        temp_list.append("<table border='1'>")
        # table head
        temp_list.append("<thead>")
        temp_list.append("<tr><th colspan='%s'>%s(%s)</th></tr>" % (len(table.key_name_list), table.table_desc, table.table_name))
        temp_list.append("<tr>")
        temp_list.append("".join(map(lambda x: "<th>%s</th>" % x[1], table.key_name_list)))
        temp_list.append("</tr>")
        temp_list.append("</thead>")
        # table body
        for cells in rows:
            temp_list.append("<tr>")
            cells = list(cells)
            for idx, td in enumerate(cells):
                if td == None or str(id).strip() == "":
                    cells[idx] = "&nbsp;"

            td_list = map(lambda x: "<td>%s&nbsp;</td>" % x, cells)
            temp_list.append("".join(td_list))
            temp_list.append("</tr>")
        temp_list.append("</table>")
        temp_list.append("<br>")

    print "\n".join(temp_list) 

if __name__ == "__main__":
    sponsor_id     = sys.argv[1]
    table_list_str = sys.argv[2] 
    conf_str       = sys.argv[3]
    if sys.argv[4] == "1": 
        useReadable = True
    else:
        useReadable = False
    table_list = table_list_str.split(",")
    output_html_str(int(sponsor_id), table_list, conf_str, useReadable)
