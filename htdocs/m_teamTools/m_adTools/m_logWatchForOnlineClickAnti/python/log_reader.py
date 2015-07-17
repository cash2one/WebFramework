#!/usr/bin/python
#encoding: utf-8

import os
import sys
import threading
import commands
import re
import time
import json

mutex = threading.Lock()

class LogReader:

    def __init__(self, inputObj):
        self.filter_str = str(inputObj["filter_str"])
        self.output_dict = {
            "status": 0,
            "message": "",
            "anti": {
                "lines": [],
                "machine_info": map(lambda x: str(x), inputObj["anti"]),
            },
            "click": {
                "lines": [],
                "machine_info": map(lambda x: str(x), inputObj["click"]),
            },
        }

        self.max_line_count = 1000

        self.lock = threading.Lock()

    def get_cmd_output(self, cmd):
        #self.lock.acquire()
        #print cmd
        status, output = commands.getstatusoutput(cmd)
        #self.lock.release()

        return status, output

    def _read(self, key, host, file_path, offset, idx):
        if (offset == 0):
            if os.environ["USER"] in ("luqy", "#lihy"): 
                lc_cmd = "source /home/%s/.keychain/tb086.corp.yodao.com-sh; ssh %s 'wc -l %s'" % (os.environ["USER"], host, file_path)
            else:
                lc_cmd = "ssh %s 'wc -l %s'" % (host, file_path)
            status, output = self.get_cmd_output(lc_cmd)
            if status != 0:
                self.output_dict["status"] = status
                self.output_dict["message"] = output
                return

            offset = int(output.split(" ")[0])

        line_count = offset + 1
        # just for security reason WITHOUT use $ in sed command
        line_count2 = line_count + self.max_line_count - 1

        if os.environ["USER"] == "luqy": 
            read_cmd = "source /home/luqy/.keychain/tb086.corp.yodao.com-sh; ssh %s \"sed -n '%d, %d'p %s\"" % (host, line_count, line_count2, file_path)
        else:
            read_cmd = "ssh %s \"sed -n '%d, %d'p %s\"" % (host, line_count, line_count2, file_path)
        status, output = self.get_cmd_output(read_cmd)
        if status != 0:
            self.output_dict["status"] = status
            self.output_dict["message"] = output
            return

        lines = output.split("\n")
        self.output_dict[key]["machine_info"][idx] = "%s:%s:%s" % (host, file_path, str(offset + len(lines)))

        mutex.acquire()
        for line in lines:
            if self.filter_str in line:
                line = line.replace(self.filter_str, "<font color='red'>%s</font>" % self.filter_str)
                self.output_dict[key]["lines"].append("<font color='blue'>[%s-%s]</font> %s" % (key, host, line))
        mutex.release()

    def read(self):
        tList = []

        for key, m_dict in self.output_dict.items():
            if key in ("status", "message"): 
                continue

            for idx, line in enumerate(m_dict["machine_info"]):
                host, log_path, offset = line.split(":")
                t = threading.Thread(target = self._read, args = (key, host, log_path, int(offset), idx))
                tList.append(t)
                t.start()
        
        for t in tList:
            t.join()

    def output(self):
        #json: http://hi.baidu.com/mlxiangbala/item/1bd31a1178a2e2debe904248
        print json.dumps(self.output_dict)
            

if __name__ == "__main__":
    # input machine-log list sep by ","
    inputObj = json.loads(sys.argv[1], encoding="utf-8");
    reader   = LogReader(inputObj)
    reader.read()
    reader.output()
