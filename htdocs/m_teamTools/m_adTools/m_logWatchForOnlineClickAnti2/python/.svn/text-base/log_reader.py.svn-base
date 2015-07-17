#!/usr/bin/python
#encoding: utf-8
# 00imprIp=61.135.255.831000

import os
import sys
import threading
import commands
import re
import time
import json

mutex = threading.Lock()
line_cnt_read_each = 2000

class QueryStatus:
    Pass = 0
    GetWLFail = 1
    ReadLogLineFail = 2

class LogReader:
    def __init__(self, set_file):
        self.set_file2 = set_file + ".more"
        self.log_type_dict = {
            "ws129:/disk5/eadop/click-resin/logs/log": "click",
            "ws130:/disk5/eadop/click-resin/logs/log": "click",
            "nc092:/disk4/eadop/click-resin/logs/log": "click",
            "nc072:/disk4/eadop/click-resin/logs/log": "click",

            "hs026:/disk2/eadop/antifrauder/logs/log": "anti",
            "hs027:/disk2/eadop/antifrauder/logs/log": "anti",
            "nc096:/disk2/eadop/antifrauder/logs/log": "anti"
        }

        self.click_lines = []
        self.anti_lines = []

        set_file_content = open(set_file).read().strip()
        self.click_checked, self.anti_checked, self.filter_str = set_file_content.split("")
        self.lines_cnt_read = 0
        self.status = QueryStatus.Pass
    
        ### 初始化log_line_offset_dict
        self.log_line_offset_dict = {}
        try:
            for line in open(self.set_file2).readlines():
                log_path, offset = line.strip().split("")
                self.lines_cnt_read += int(offset)

                self.log_line_offset_dict[log_path] = int(offset)
        except:
            for log_path, type in self.log_type_dict.items():
                if self.click_checked == "1" and type == "click":
                    self.log_line_offset_dict[log_path] = 0
                elif self.anti_checked == "1" and type == "anti":
                    self.log_line_offset_dict[log_path] = 0


    def do_read(self, log_path):
        offset = self.log_line_offset_dict[log_path]
        type = self.log_type_dict[log_path]
        host, log_file = log_path.split(":")
        user = os.environ["USER"]
        ### 如果第一次，需要知道当前日志文件有多少行
        if offset == 0:
            if user in ("luqy",):
                wl_cmd = "source /home/%s/.keychain/tb086.corp.yodao.com-sh; ssh %s 'wc -l %s'" % (user, host, log_file)
            else:
                wl_cmd = "ssh %s 'wc -l %s'" % (host, log_file)
            status, output = commands.getstatusoutput(wl_cmd) 
            if status != 0:
                self.status = QueryStatus.GetWLFail
                return
    
            self.log_line_offset_dict[log_path] = int(output.split(" ")[0])

        ### 读取指定行的日志
        start_idx = self.log_line_offset_dict[log_path] + 1
        end_idx = self.log_line_offset_dict[log_path] + line_cnt_read_each
        if user in ("luqy", ):
            read_cmd = "source /home/luqy/.keychain/tb086.corp.yodao.com-sh; ssh %s \"sed -n '%d, %d'p %s\"" % (host, start_idx, end_idx, log_file)
        else:
            read_cmd = "ssh %s \"sed -n '%d, %d'p %s\"" % (host, start_idx, end_idx, log_file)
        status, output = commands.getstatusoutput(read_cmd)
        if status != 0:
            self.status = QueryStatus.ReadLogLineFail
            return

        ### 保存日志到list中
        lines = output.split("\n")
        self.log_line_offset_dict[log_path] += len(lines)

        mutex.acquire()
        for line in lines:
            if self.filter_str in line:
                line = line.replace(self.filter_str, "<font color='red'>%s</font>" % self.filter_str)
                plat_name = ""
                reObj = re.search("syndId=(\d+)", line)
                if reObj:
                    syndIdVal = int(reObj.group(1))
                    if syndIdVal in range(5, 11):
                        plat_name = "邮箱"
                    elif syndIdVal == 16:
                        plat_name = "线下直销"
                    elif syndIdVal in range(10, 21):
                        # 不包括16
                        plat_name = "频道"
                    elif syndIdVal in range(50, 101):
                        plat_name = "词典"
                    elif syndIdVal in range(101, 151):
                        plat_name = "智选"
                    elif syndIdVal >= 1000:
                        plat_name = "联盟"
                    else:
                        plat_name = "搜索"
                    
                if plat_name == "":
                    line = "<font color='yellow'>[%s-%s]</font> %s" % (type, host, line)
                else:
                    line = "<font color='yellow'>[%s-%s][%s]</font> %s" % (type, host, plat_name, line)
                if type == "click":
                    self.click_lines.append(line)
                else:
                    self.anti_lines.append(line)
        mutex.release()

    def read(self):
        tList = []
        for log_path in self.log_line_offset_dict.keys():
            t = threading.Thread(target = self.do_read, args = (log_path,))
            tList.append(t)
            t.start()
        
        for t in tList:
            t.join()

    def output(self):
        temp_list = []
        for log_path, offset in self.log_line_offset_dict.items():
            temp_list.append("%s%s" % (log_path, offset))
        open(self.set_file2, "w").write("\n".join(temp_list))

        print "%s%s%s" % (self.status, "".join(self.click_lines), "".join(self.anti_lines))
            

if __name__ == "__main__":
    # input machine-log list sep by ","
    reader = LogReader(sys.argv[1])
    reader.read()
    reader.output()
