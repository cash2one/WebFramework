#!/usr/bin/python
#encoding: utf-8

import time
import re
import struct

class Util:
    _conf_dict = {}

    @staticmethod
    def get_last_month_str(frmt_str = "%d%02d"):
        local_tm = time.localtime()
        year     = local_tm.tm_year
        month    = local_tm.tm_mon

        if month == 1:
            year -= 1
            month = 12
        else:
            month -= 1

        return frmt_str % (year, month)

    @staticmethod
    def getConf(key):
        if not Util._conf_dict:
            for line in open("../conf/test.conf"):
                line = line.strip()
                if not line or line[0] == "#" or "=" not in line:
                    continue

                key, val = re.split("\s*=\s*", line, 1)
                Util._conf_dict[key] = val

        return Util._conf_dict.get(key, None)

    @staticmethod
    def getFormatNum(int_num_str):
        int_num_str = int_num_str[::-1]
        temp_list = []
        for idx, ch in enumerate(int_num_str):
            if idx != 0 and idx % 3 == 0 and ch != "-" and ch != "+":
                temp_list.append(",")
            temp_list.append(ch)

        int_num_str = "".join(temp_list)
        return int_num_str[::-1]

    @staticmethod
    def date_adjust(month_str):
        """因为对账工具的bug，比如201212会写成201300, 所以给定201212来作为数据库查询条件时，需要调整为201300"""
        """已经fix了"""
        year, month = struct.unpack("4s2s", str(month_str))
        if month == "12":
            year = "%s" % (int(year) + 1)
            month = "00"

        return "%s%s" % (year, month)

    @staticmethod
    def get_pre_month(input_month):
        year = input_month / 100
        month = input_month % 100
        if month == 1:
            month = 12
            year -= 1
        else:
            month -= 1
        
        return year * 100 + month

if __name__ == "__main__":
    print Util.get_last_month_str()
    print Util.getConf("test_fin_db")
    for i in range(12):
        print Util.get_pre_month(201301 + i)
