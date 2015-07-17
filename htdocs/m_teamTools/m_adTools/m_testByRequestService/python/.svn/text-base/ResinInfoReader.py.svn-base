#!/usr/bin/python
#encoding: utf-8

import sys
import threading
import commands
import re
import time

def get_host(file):
    hosts = []

    for line in open(file).read().splitlines():
        line = line.strip()
        if not line: continue
        if line[0] == "#": continue
        hosts.append(line)
    
    return hosts

def read_resin_path(hosts):
    host_conf_list = []

    for host in hosts:
        cmd = "ps aux|grep resin|grep -v perl|grep -v grep"
        cmd = "ssh %s \"%s\"" % (host, cmd)
        output = commands.getoutput(cmd)

        lines = re.findall("-stderr (.*?)/log/stderr.log", output)
        for line in lines:
            # host_conf_list.append(host + ":" + line + "/webapps/imp/WEB-INF/ead-servlet.xml")
            host_conf_list.append(host + ":" + line)

    return host_conf_list

def get_resin_type(host_conf_list, output_dir):
    log_dict = {}

    for host_conf in host_conf_list:
        host, root_dir = host_conf.split(":")
    
        # cmd = "grep platform %s/webapps/imp/WEB-INF/ead-servlet.xml" % root_dir
        cmd = "grep platform %s/webapps/*/WEB-INF/*-servlet.xml" % root_dir
        cmd = "ssh %s \"%s\"" % (host, cmd)
        output = commands.getoutput(cmd)

        reObj = re.search("value=\"(\w+)\"", output)
        if reObj:
            service_type = reObj.group(1)
            if service_type == "all":
                service_type = "click_resin"

            line = "%s:%s/logs/access.log" % (host, root_dir)
            if not log_dict.has_key(service_type):
                log_dict[service_type] = []

            log_dict[service_type].append(line + "\n")

    for type, lines in log_dict.items():
        file = "%s/%s.conf" % (output_dir, type)
        open(file, "w").writelines(lines)

if __name__ == "__main__":
    file = "../conf/resin.hosts"
    hosts = get_host(file)

    host_conf_list = read_resin_path(hosts)
    get_resin_type(host_conf_list, "/home/zhangpei/resin")
