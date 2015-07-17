#!/usr/bin/python

from TableHtmlWriter import *

sponsor_id     = 64
conf_str       = "nc111:/disk3/zhangpei/financial-system/financial-war/war/WEB-INF/hibernate.properties"
useReadable    = True
table_list     = ["SPONSOR_TODAY_COST"]
output_html_str(sponsor_id, table_list, conf_str, useReadable)
