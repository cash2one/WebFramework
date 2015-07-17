#!/usr/bin/python

import os

file = "s4.url"
req_pre_str = "http://nb092x.corp.youdao.com:18382"
max_count = 10

for line in open(file):
    line = line.strip()

    url = "%s%s" % (req_pre_str, line)

    cmd = "wget '%s' -O temp.txt" % url
    os.system(cmd)

    ret = raw_input("save it(y/n)? ")
    if ret == "y":
        open("man_xing.txt", "a").write(line + "\n")
    
        max_count -= 1
        if max_count  == 0:
            break
