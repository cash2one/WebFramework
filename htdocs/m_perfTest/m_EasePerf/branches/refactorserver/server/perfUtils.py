# encoding=utf-8
'''
Created on 2013-4-12

@author: 张波
'''
import subprocess


def getMachineMemory():
    '''
    获取内存大小:返回单位为M
    '''
    # 计算total_mem: 目前每个Process都会算一次, 不过占用资源较少, 暂不设为global或由Collect来计算并赋值
    cmd = "cat /proc/meminfo  |grep 'MemTotal'|awk '{print $2,$3}'"
    result = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE ).communicate()[0]
    result = result.strip( "\r" ).strip( "\n" ).strip().split()
    meminfo = float( result[0] )
    unit = result[1].lower().strip()
    if unit == 'kb':
        meminfo = meminfo / 1024
    # 后面两种应该没啥必要, 不过有备无患:)
    elif unit == 'mb':
        pass
    elif unit == 'gb':
        meminfo = meminfo * 1024
    else:
        return None
    return meminfo


def getCurUserName():
    '''
    获取当前用户
    '''
    cmd = "whoami"
    result = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE ).communicate()[0]
    result = result.strip( "\r" ).strip( "\n" ).strip()
    machinename = result.split( '.' )[0]
    return machinename

def getMachineName():
    '''
    获取机器名称
    '''
    cmd = "uname -n"
    result = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE ).communicate()[0]
    result = result.strip( "\r" ).strip( "\n" ).strip()
    machinename = result.split( '.' )[0]
    return machinename

def getPidCmdPathInfos():
    '''
    返回进程信息： [ (pid,cmd,proccesspath),]
    '''
    processinfos = []
    # 获取当前user的进程pid, cmd, path的shell命令 (返回格式执行可见):
    # ps x --columns=100000 | sed -r 's/^\s*(.*)/\1/' | grep -P "^\s*\d" | grep -v -P "\d+:\d+\s+(ps|grep|sed|ssh|-bash|ls|sh)" | sed -r 's/^([0-9]*).*[0-9]+:[0-9]+\s+(.*)/echo -ne "\1\\\\t\2\\\\t";ls -l \/proc\/\1\/cwd/' | grep -v columns=100000 | sh | grep -P "^\d" | sed -r "s/([0-9]*)\t(.*)\t.*-> (.*)/\1\t\2\t\3/"
    # 1. ps -x (可改为用ps -xo) 注:ps 命令会有截断, 设为10w长应该可以对付一般情况, 默认为page length, 一般为4096... 可能取不到完整的commands
    # 2. 去掉非进程开头的行
    # 3. 去掉shell本身的进程 注: 过滤了sh, top等进程, 而一般的监控场景也不是对此类进程做监控
    # 4. 获取pid及path
    # 5. readlink /proc/pid/cwd获取进程cwd 注:readlink /proc/pid/cwd时可能会有Permission denied的情况, 因此需要对返回值做处理
    # 6. escape真是很xx...!!!: reg+sed(还好用了-r)+echo -ne+python+...
    cmd = "ps xo pid,cmd --columns=100000 | sed -r \"s/^\s*(.*)/\\1/\" |grep -P \"^\s*\d\" | grep -v -P \"\d+:\d+\s+(ps|grep|sed|ssh|top|tail|less|tail|more|-bash|ls|sh|vim|awk|wc?\s)\" | sed -r 's/^([0-9]*)\s+(.*)/echo -ne \"\\1\\\\t\\2\\\\t\";readlink \/proc\/\\1\/cwd/' | grep -v columns=100000 | sh  | grep -P \"^\d\" "
    lines = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE, stderr = subprocess.PIPE ).communicate()[0].splitlines()
    for line in lines:
        if 'Permission denied' in line:
            continue
        datas = line.split()
        if len( datas ) < 3:
            continue
        if len( datas ) == 3:
            processinfos.append( ( datas[0], datas[1], datas[2] ) )
        else:
            processinfos.append( ( datas[0], ' '.join( datas[1:-1] ), datas[-1] ) )
    return processinfos


def getPidCpuMemInfos():
    '''
    获取进程信息{ pid:(pid,user,%cpu,%mem),}
    '''
    processinfos = {}
    cmd = "top -n1 -b | awk '{printf(\"%s %s %s %s\\n\",$1,$2,$9,$10);}' | grep  -e \"^[0-9]\\+\""
    results = subprocess.Popen( args = cmd, shell = True, stdout = subprocess.PIPE ).communicate()[0].strip().splitlines()
    for result in results:
        # PID USER      PR  NI  VIRT  RES  SHR S %CPU %MEM
        # 当前结果为： PID USER %CPU %MEM
        lineinfos = result.split()
        if 4 != len( lineinfos ):
            break
        pid = str( lineinfos[0] ).strip()
        processinfos[pid] = ( pid, lineinfos[1], float( lineinfos[2] ), float( lineinfos[3] ) )
    return processinfos
