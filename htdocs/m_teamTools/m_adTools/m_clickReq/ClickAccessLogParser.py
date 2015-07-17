#!/usr/bin/python
#encoding:utf-8

import re

"""
!!! 点击access日志中的syndid不是SiteType中的syndid !!!
"""

def parse_access_log_line(line, ignore_log_head_str = True):
    ret_list = []

    double_quotes_found = False # 是否已经发现双引号
    in_quotes = False           # 当前字符是否在双引号内

    temp_list = []
    temp_sub_list = []

    for ch in line:
        if ch == '"':
            if double_quotes_found == False:
                double_quotes_found = True

            # 发现右双引号，则将temp_sub_list中的字符拼成字符串，放到temp_list中
            if in_quotes == True:
                temp_list.append("".join(temp_sub_list))
                temp_sub_list = []

            # 只要发现双引号，则其状态就要变化
            in_quotes = not in_quotes

        else:
            # 还没有发现双引号，且忽略日志行的头字符串
            if double_quotes_found == False:
                if ignore_log_head_str == True:
                    continue
    
            if in_quotes == True:
                # 当前字符在双引号内
                temp_sub_list.append(ch)
            else:
                if ch == ' ':
                    if len(temp_sub_list) != 0:
                        # 当前字符不在双引号内,且是空格, 则把已经存储的字符拼成字符串，放到temp_list中
                        temp_list.append("".join(temp_sub_list))
                        temp_sub_list = []
                else:
                    # 当前字符不在双引号内，且不是空格，则保留下来
                    temp_sub_list.append(ch) 

    return temp_list


def get_sitetype_from_clk_req_str(clk_req_str):
    # 如果在点击串中发现形如s=xx
    reObj = re.search("s=(\d+)", clk_req_str)
    if reObj != None:
        return reObj.group(1)
    return None

def get_syndid_from_impr_req_str(impr_req_str):
    # 此syndid非彼syndid
    reObj = re.search("syndid=(\d+)", impr_req_str)
    if reObj != None:
        return reObj.group(1)
    return None

def get_platform(clk_req_str, impr_req_str):
    site_type = get_sitetype_from_clk_req_str(clk_req_str)
    if site_type == None:
        return "unknown"

    site_type = int(site_type)
    if site_type == 1:
        return "eads"
    elif site_type == 2:
        syndid = get_syndid_from_impr_req_str(impr_req_str)
        if syndid == "1005":
            return "offline"
        else:
            return "eadc"
    elif site_type == 4:
        return "eadu"
    elif site_type == 8:
        return "eadm"
    elif site_type == 16:
        return "eadd"
    elif site_type == 32:
        return "ad_exchange"
    elif site_type != None:
        return "eads"

    return "unknown"

def parse_access_log_file(log_file, output_php_file, max_req_count = 40):
    lines_to_read = 200000
    handle = open(output_php_file, "w")
    access_log_fields_cnt = 5

    clk_req_dict = {
        "eads": [],
        "eadm": [],
        "offline": [],
        "eadc": [],
        "eadd": [],
        "eadu": [],
        "ad_exchange": [],
        "unknown": [],
    }

    for line in open(log_file):
        ret_list = parse_access_log_line(line)
        if len(ret_list) != access_log_fields_cnt:
            continue

        lines_to_read -= 1
        if lines_to_read <= 0:
            break

        if lines_to_read % 10000 == 0:
            print "%s lines to be parse" % lines_to_read
        
        clk_req_str = ret_list[0].split(' ')[1]
        impr_req_str = ret_list[3]
        plat_name = get_platform(clk_req_str, impr_req_str)
        clk_req_dict[plat_name].append(clk_req_str)

    handle.write("<?php\n\n")
    handle.write("$clk_req_array = Array(\n")
    for key, req_list in clk_req_dict.items():
        if key == "unknown":
            continue

        handle.write("\t\"" + key + "\" => Array(\n")

        for index, req_str in enumerate(set(req_list)):
            if index == max_req_count:
                break
            handle.write("\t\t\"" + req_str + "\",\n")

        handle.write("\t),\n") 

    handle.write(");")

    handle.close()


if __name__ == "__main__":
    parse_access_log_file("access.log.2013-03-07", "php/click.php")
