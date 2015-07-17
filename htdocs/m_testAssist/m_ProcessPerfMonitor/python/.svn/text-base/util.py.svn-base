#!/usr/bin/python
#encoding: utf-8

import os
import re
import time
import commands

def get_hosts():
    # return "hs014,nb092,nb093,nb292,nb293,nc024,nc044,nc069,nc070,nc107,nc108,nc109,nc111,qt101,qt102,qt105,qt106,qt107,qt109".split(",")
    return "hs014,nb092,nb093,nb292,nb293,nc024,nc044,nc069,nc070,nc107,nc108,nc109,nc111,qt101,qt102,qt103,qt104,qt105,qt106".split(",")

def get_process_keywords():
    return ("java",)

def req_interval():
    return 10

def get_pids(hostname):
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

def get_open_fd(hostname, pid_list):
    ret_dict = {}

    if not pid_list:
        return ret_dict

    cmd_list = []
    for pid in pid_list:
        # 运维已经给了test账号对ls的sudo权限
        cmd_list.append("sudo ls /proc/%s/fd 2>/dev/null|wc -l" % pid)

    cmd = ";".join(cmd_list)
    ssh_cmd = "ssh %s '%s'" % (hostname, cmd)
    ret, result_str = commands.getstatusoutput(ssh_cmd)
    fd_cnt_list = result_str.split("\n")

    for idx, pid in enumerate(pid_list):
        ret_dict[pid] = fd_cnt_list[idx]

    return ret_dict

def get_socket_dict(hostname, pid_list):
    ret_dict = {}

    if not pid_list:
        return ret_dict

    cmd_list = []
    for pid in pid_list:
        # 运维已经给了test账号对ls的sudo权限
        cmd_list.append("sudo ls -l /proc/%s/fd 2>/dev/null|grep socket:|wc -l" % pid)

    cmd = ";".join(cmd_list)
    ssh_cmd = "ssh %s '%s'" % (hostname, cmd)
    ret, result_str = commands.getstatusoutput(ssh_cmd)
    socket_list = result_str.split("\n")

    for idx, pid in enumerate(pid_list):
        ret_dict[pid] = socket_list[idx]

    return ret_dict

def get_top_info(hostname, pid_list):
    pid_list_str = ",".join(pid_list)
    if pid_list:
        top_cmd = "ssh %s 'top -b -p %s -n1'" % (hostname, pid_list_str)
    else:
        top_cmd = "ssh %s 'top -b -n1'" % hostname
    ret, result_str = commands.getstatusoutput(top_cmd)

    if pid_list:
        return result_str
    else:
        return "\n".join(result_str.split("\n")[:5])

def get_pthread_count(hostname, pid_list):
    ret_dict = {}

    if not pid_list:
        return ret_dict

    cmd_list = []
    for pid in pid_list:
        cmd_list.append("pstree -p %s|wc -l" % pid)
    cmd = ";".join(cmd_list)
    ssh_cmd = "ssh %s '%s'" % (hostname, cmd)
    ret, result_str = commands.getstatusoutput(ssh_cmd)
    pthread_cnt_list = result_str.split("\n")

    for idx, pid in enumerate(pid_list):
        ret_dict[pid] = pthread_cnt_list[idx]

    return ret_dict

def parse_top_result_str(top_str):
    ret_fields = []
    max_fields_cnt = -1
    pid_idx  = -1
    user_idx = -1
    res_idx  = -1
    cpu_idx  = -1
    mem_idx  = -1

    lines = top_str.split("\n")
    for line in lines:
        line = line.strip()
        if not line: continue

        if line.startswith("top"):
            loads = line.split(":")[-1].strip().split(", ")
            ret_fields.append(loads[0])
        elif line.startswith("PID"):
            fields = re.split("\s+", line)
            max_fields_cnt = len(fields)
            pid_idx = fields.index("PID")
            user_idx = fields.index("USER")
            res_idx  = fields.index("RES")
            cpu_idx  = fields.index("%CPU")
            mem_idx  = fields.index("%MEM")
        elif line[0] in ("1", "2", "3", "4", "5", "6", "7", "8", "9"):
            fields = re.split("\s+", line, max_fields_cnt - 1)
            pid  = fields[pid_idx]
            user = fields[user_idx]
            res  = fields[res_idx]
            cpu  = fields[cpu_idx]
            mem  = fields[mem_idx]
            ret_fields.append([pid, user, res, cpu, mem])

    return ret_fields

def get_disk_map(hostname):
    disk_dict = {}

    df_cmd = "ssh %s 'df -h'" % hostname
    ret, result_str = commands.getstatusoutput(df_cmd)
    for line in result_str.split("\n")[1:]:
        fields = re.split("\s+", line)
        device = fields[0]
        disk   = fields[-1]
        
        if disk.startswith("/disk"):
            device_name = device.split("/")[-1]
            disk_dict[device_name] = disk

    return disk_dict

def get_iostat_info(hostname, disk_dict):
    iostat_dict = {}
    
    iostat_cmd = "ssh %s 'iostat -p ALL -k'" % hostname
    ret, result_str = commands.getstatusoutput(iostat_cmd)
    for line in result_str.split("\n")[1:]:
        fields = re.split("\s+", line)
        device = fields[0]
        if not disk_dict.has_key(device):
            continue

        disk = disk_dict[device]
        tps = fields[1]
        kB_read_rate = fields[2]
        kB_wrtn_rate = fields[3]
        iostat_dict[disk] = "%s,%s,%s" % (tps, kB_read_rate, kB_wrtn_rate)

    return iostat_dict

def get_vmstat_info(hostname):
    vmstat_cmd = "ssh %s 'vmstat 1 2'" % hostname
    ret, result_str = commands.getstatusoutput(vmstat_cmd)
    line = result_str.split("\n")[3]
    # r, b, swpd, free, buff, cache, si, so, bi, bo, In, cs, us, sy, id, wa, st
    return ",".join(re.split("\s+", line.strip()))

def get_network_info(hostname):
    sar_cmd = "ssh %s 'sar -n DEV 1 1'" % hostname
    ret, result_str = commands.getstatusoutput(sar_cmd)
    lines = result_str.split("\n")
    for line in lines:
        if "eth0" in line:
            fields = re.split("\s+", line)
            return ",".join(fields[3:])

def read_result(hostname):
    interval_sec = req_interval()

    ret_dir = "../perf_results"
    host_dir = "%s/%s" % (ret_dir, hostname)
    if not os.path.exists(host_dir):
        os.mkdir(host_dir)
    date_dir = "%s/%s" % (host_dir, time.strftime("%Y%m%d", time.localtime()))
    if not os.path.exists(date_dir):
        os.mkdir(date_dir)

    pid_dict = get_pids(hostname)
    device_dict = get_disk_map(hostname)

    for i in range(30):
        timestamp = int(time.time()) * 1000
        ret_str = get_top_info(hostname, pid_dict.keys())
        machine_list = parse_top_result_str(ret_str)
        vmstat_info = get_vmstat_info(hostname)

        pthread_cnt_dict = get_pthread_count(hostname, pid_dict.keys())
        fd_cnt_dict = get_open_fd(hostname, pid_dict.keys())
        socket_dict = get_socket_dict(hostname, pid_dict.keys())

        if len(machine_list) == 0:
            time.sleep(interval_sec)
            continue
        
        machine_file = "%s/machine.log" % date_dir
        open(machine_file, 'a').write("%s:%s\n" % (timestamp, machine_list[0]))

        vmstat_file = "%s/vmstat.log" % date_dir
        open(vmstat_file, 'a').write("%s:%s\n" % (timestamp, vmstat_info))

        disk_map = get_iostat_info(hostname, device_dict)
        for disk, val_str in disk_map.items():
            iostat_file = "%s/%s.log" % (date_dir, disk)
            open(iostat_file, 'a').write("%s:%s\n" % (timestamp, val_str))

        network_info = get_network_info(hostname)
        network_file = "%s/network.log" % date_dir
        open(network_file, 'a').write("%s:%s\n" % (timestamp, network_info))

        if len(machine_list) == 1:
            time.sleep(interval_sec)
            continue

        for subList in machine_list[1:]:
            # ['0.11', ['16797', 'ndfs', '4.5g', '0', '14.2']]
            pid, user, res, cpu, mem = subList
            if user in ("maintain", ):
                continue

            pthread_cnt = pthread_cnt_dict.get(pid, 0)
            fd_cnt      = fd_cnt_dict.get(pid, 0)
            socket_cnt  = socket_dict.get(pid, 0)

            cmd = pid_dict[pid]
            user_cmd_file = "%s/%s-%s.cmd" % (date_dir, pid, user)
            if not os.path.exists(user_cmd_file):
                open(user_cmd_file, 'w').write(cmd)

            if res[-1] == "m":
                res = float(res[:-1]) / 1024.0
            elif res[-1] == "g":
                res = res[:-1]
            else:
                try:
                    res = float(res[:-1]) / (1024 * 1024.0)
                except:
                    print "res: [%s]" % res
                    res = 0
            user_file = "%s/%s-%s.log" % (date_dir, pid, user)
            open(user_file, 'a').write("%s:%s,%s,%s,%s,%s,%s\n" % (timestamp, res, cpu, mem, pthread_cnt, fd_cnt, socket_cnt))
        
        time.sleep(interval_sec)

if __name__ == "__main__":
    # read_result("nc107")
    # print get_pids("qt109")
    # print get_pthread_count("nb292", [18, 19, 20,23,33,33,44])
    # print get_pthread_count("nb292", [18, 19, 20,23,33,33,44])
    # print get_open_fd("nc111", [212155, 255834, 375859, 448583, 500846, 586975])
    # device_dict = get_disk_map("nc109")
    # print get_iostat_info("nc109", device_dict)
    hostname = "qt109"
    pid_dict = get_pids(hostname)
    device_dict = get_disk_map(hostname)
    ret_str = get_top_info(hostname, ["16797"])
    print parse_top_result_str(ret_str)
    print get_vmstat_info(hostname)
    pthread_cnt_dict = get_pthread_count(hostname, pid_dict.keys())
    print pthread_cnt_dict
    fd_cnt_dict = get_open_fd(hostname, pid_dict.keys())
    print fd_cnt_dict
    socket_dict = get_socket_dict(hostname, pid_dict.keys())
    print socket_dict
