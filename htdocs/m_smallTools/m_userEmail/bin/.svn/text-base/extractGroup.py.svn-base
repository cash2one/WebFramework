#!/usr/bin/python
#encoding:utf-8

import re

src_data_file  = "data.txt"
src_data_file2 = "from_bugzilla.txt"
output_file    = "group.txt"

lines = open(src_data_file).readlines()
output_lines = []

# 解析data.txt，提取出UserName, LDAP, 形成一行tr，放到source.html中
for line in lines:
    if "UserName=" in line:
        reObj = re.search("information\.php\?UserName=(.+)\">(.*)</a></td>", line)
        if reObj:
            ldap = reObj.group(1).strip()
            name = reObj.group(2).strip()
            output_lines.append((ldap, name))

# read lines from src_data_file2
for line in open(src_data_file2).read().splitlines():
    for ldap, name in output_lines:
        email_addr = ldap + "@rd.netease.com"
        if email_addr in line:
            break
    else:
        print line
