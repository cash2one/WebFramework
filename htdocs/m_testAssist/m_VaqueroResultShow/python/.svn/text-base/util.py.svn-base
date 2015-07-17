#!/usr/bin/python
#encoding: utf-8

import os
import re
import time
import commands
import md5
from xml.dom import minidom

def parse_vaquero_xml_file(xml_file):
    ret_list = []

    doc = minidom.parse(xml_file)
    root = doc.documentElement

    keyset_node_list = root.getElementsByTagName("keyset")
    for keyset in keyset_node_list:
        keyset_attr_dict = {}
        property_attr_dict = {}
        key_attr_dict = {}

        # deal with keyset node
        for attrName, attrValue in keyset.attributes.items():
            keyset_attr_dict[attrName] = attrValue

        nodes = keyset.childNodes
        for node in nodes:
            if node.nodeType != 1:
                continue

            # deal with property node
            if node.tagName == "property":
                for attrName, attrValue in node.attributes.items():
                    property_attr_dict[attrName] = attrValue

            # deal with key node
            elif node.tagName == "key":
                for attrName, attrValue in node.attributes.items():
                    key_attr_dict[attrName] = attrValue

                # deal with value node
                value_nodes = node.childNodes
                key_attr_dict["values"] = []
                for value_node in value_nodes:
                    if value_node.nodeType != 1:
                        continue
                
                    key_attr_dict["values"].append(value_node.firstChild.nodeValue)

        key_name = key_attr_dict.get("name", None)
        if key_name == None:
            continue

        if property_attr_dict.get("performance", None) == "true":
            ret_list.append(key_name + ".response")

        if property_attr_dict.get("throughput", None) == "true":
            ret_list.append(key_name + ".throughput")

        if property_attr_dict.get("allowall", None) == "false":
            ret_list.append(key_name + ".proportion")

        if keyset_attr_dict.get("type", None) == "string":
            count_only = keyset_attr_dict.get("countonly", None)
            if count_only == "false":
                ret_list.append(key_name + ".throughput")
                ret_list.append(key_name + ".proportion")
            elif count_only == "true":
                ret_list.append(key_name)

    return ret_list

def get_md5(file):
    key = md5.new()
    key.update(open(file).read())
    return key.hexdigest()

def get_hosts():
    return "hs014,nb092,nb093,nb292,nb293,nc024,nc044,nc069,nc070,nc107,nc108,nc109,nc111,qt101,qt102,qt103,qt104,qt105,qt106".split(",")

def get_process_keywords():
    return ("toolbox.manager.tools.AnalyzerService",)

def req_interval():
    return 10

def scp_conf_file(hostname, cwd, conf_file, target_file):
    ssh_cmd = "scp %s:%s/%s %s 2>/dev/null" % (hostname, cwd, conf_file, target_file)
    os.system(ssh_cmd)

def get_vaquero_params(vaquero_cmd):
    conf_file = ""
    log_file = ""
    cub = ""
    type = ""
    prod = "" 

    old_field = ""
    fields = re.split("\s+", vaquero_cmd)
    for field in fields:
        if old_field == "-f":
            conf_file = field
        elif old_field == "-i":
            log_file = field
        elif old_field == "-cub":
            cub = field
        elif old_field == "-t":
            type_str = field
        elif old_field == "-p":
            prod = field

        old_field = field

    return conf_file, log_file, cub, type_str, prod

def get_pid_cmd_dict(hostname):
    pid_dict = {}
    for keyword in get_process_keywords():
        ssh_cmd = "ssh %s 'pgrep -l -f %s'" % (hostname, keyword)
        ret, pids_str = commands.getstatusoutput(ssh_cmd)
        for line in pids_str.split("\n"):
            if line == "": continue
            pid, process_cmd = line.split(" ", 1)
            if "vim" in process_cmd:
                continue
            pid_dict[pid] = process_cmd

    return pid_dict

def get_pid_user_dict(hostname, pid_list):
    ret_dict = {}
    if not pid_list:
        return ret_dict

    pid_list_str = ",".join(pid_list) 
    ssh_cmd = "ssh %s 'ps -p %s -o \"user pid\"'" % (hostname, pid_list_str)
    ret, pid_user_str = commands.getstatusoutput(ssh_cmd)
    for line in pid_user_str.split("\n")[1:]:
        user, pid = re.split("\s+", line.strip(), 2)
        ret_dict[pid] = user

    return ret_dict

def get_cwd_dict(hostname, pid_list):
    ret_dict = {}
    if not pid_list:
        return ret_dict

    file_list = []
    for pid in pid_list:
        file_list.append('/proc/%s/cwd' % pid)
    file_str = " ".join(file_list)
    ssh_cmd = "ssh %s 'sudo ls -l %s 2>/dev/null'" % (hostname, file_str)

    ret, cwd_str = commands.getstatusoutput(ssh_cmd)
    for line in cwd_str.split("\n"):
        other, cwd = re.split("\s+->\s+", line.strip())
        fields = other.split("/")
        pid = fields[2]
        ret_dict[pid] = cwd

    return ret_dict

def read_result(hostname, ret_dir = "../vaquero_data"):
    interval_sec = req_interval()

    pid_dict  = get_pid_cmd_dict(hostname)
    user_dict = get_pid_user_dict(hostname, pid_dict.keys())
    cwd_dict  = get_cwd_dict(hostname, pid_dict.keys())
    for pid, cmd in pid_dict.items():
        user = user_dict.get(pid, None)
        cwd  = cwd_dict.get(pid, None)
        if None in (user, cwd): continue

        conf_file, log_file, cub, type_str, prod = get_vaquero_params(cmd)
        local_conf_file = "conf_dir/%s.%s-%s-%s-%s-%s" % (os.path.basename(conf_file), cub, type_str, prod, pid, user)
        scp_conf_file(hostname, cwd, conf_file, local_conf_file)
        graph_list = parse_vaquero_xml_file(local_conf_file)

        user_dir = "%s/%s" % (ret_dir, user)
        if not os.path.exists(user_dir):
            os.mkdir(user_dir)

        user_file = "%s/%s:%s:%s" % (user_dir, cub, prod, type_str)
        handle = open(user_file, "w")
        for graph in graph_list:
            line = "%s:%s:%s:http://vaquero.corp.youdao.com//image?type=img&product=%s&name=%s&drawname=%s&cubtype=%s" % (prod, type_str, cub, prod, cub, graph, type_str)
            handle.write(line + "\n")
        handle.close()
        
    # sleep for 5 minutes
    for i in range(30):
        time.sleep(interval_sec)

if __name__ == "__main__":
    for hostname in ("nb292", "nc070", "nc107", "nc109"):
        print hostname
        read_result(hostname)
