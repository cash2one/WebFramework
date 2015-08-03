#!/usr/bin/python -u
# coding=utf-8
#
# Authour: kuyan@rd.xxx.com
# Created: 2011 Dec 13 Tue 18:26:28 PM CST

import os
import sys
import optparse
import commands
import time
import getpass
import execute
import log
import checkconf
import sysio
import commands
import subprocess

remote_exec = "ssh -t"
exec_file = "exec.sh"

help_file ="""
[Usage]:                                             
    deployment file                                           
    REQUIERED parameter.                                      
[Demo]:                                                 
    ./deploy.py -f config_file -c index_list                 
"""

help_machine ="""
[Usage]:                                                                                                                  
    remote hostname for remotely deployment                            
    OPTIONAL parameter.                                                         
[Demo]:                                                                                 
    ./deploy.py -f config_file -c index_list -m nb404                     
"""

help_collect ="""
[Usage]:                                                               
    one of the collection names in deployment file                        
    OPTIONAL parameter                                                            
[Demo]:                                                                          
    ./deploy.py -f config_file -c index_list                         
"""    

help_step ="""
[Usage]:                                                               
    one or several step names in deployment file                        
    OPTIONAL parameter                                                                                                          
[Demo]:                                                                          
    ./deploy.py -f config_file -s start_impr,stop_impr                                  
"""

help_type ="""
[Usage]:                                                               
    local or remote execution                      
    SYSTEM parameter. NOT for users                                                                                         
[Demo]:                                                                          
    ./deploy.py -f config_file -s start_impr -t local                                 
"""

def option_parser():
    parser = optparse.OptionParser() 
    parser.add_option("-f","--file",
                      dest="file",
                      default=False,
                      help=help_file)
    parser.add_option("-c","--collect",
                      dest="collect",
                      default=False,
                      help=help_collect)
    parser.add_option("-s","--step",
                      dest="step",
                      default=False,
                      help=help_step)
    parser.add_option("-m","--machine",
                      dest="machine",
                      default=False,
                      help=help_machine)
    parser.add_option("-t","--type",
                      dest="type",
                      default=False)
    parser.add_option("-u","--user",
                      dest="user",
                      default=False)
    parser.add_option("-p","--password",
                      dest="password",
                      default=False)
    parser.add_option("-k","--key",
                      dest="key",
                      default=False)
    parser.add_option("-l","--log",
                      dest="log",
                      default=False)
    parser.add_option("-e","--deployUser",
                      dest="deployUser",
                      default=False)
    return parser


def precheck(file,collect,step):
    '''check the validity of inputs and deployment file '''
    #checkcmd
    if not file:
        log.error("deploy.py","deloyment file is required")    
        return False
    if collect and step:
        log.error("deploy.py","collect and steps should not coexist") 
        return False
    if not collect and not step:
        log.error("deploy.py","collect or steps are required")    
        return False

    #checkconf
    mycheck = checkconf.checkconf(file) 
    if not mycheck.check():
        return False
    return True

def get_cmd(file,collect,step,log,user):
    ''' get the cmd string '''
    cmd = "./deploy.py -f %s" % file
    if collect:
        cmd += " -c %s" % collect
    if step:
        cmd += " -s %s" % step
    cmd += " -l %s" % log
    cmd += " -u %s" % user
    return cmd 

def exec_local(file,collect,step,username):
    ''' execute the deployment locally '''
    global exec_file
    os.system("touch %(execfile)s;chmod 777 %(execfile)s" % {"execfile":exec_file})
    execute.set_path(exec_file)
    t = execute.EXECUTE(file, username)
    if collect:
        t.exec_collect(t.parser.collect_list[collect])
    else:
        steps = step.split(',')
        for step in steps:
            t.exec_step(t.parser.step_list[step])
 
def user_exec(user,key,password,cmd_exec,deployUser):
    ''' su user '''
    try:
        cmd = "./su_user.expect %s %s \'%s\' \'%s\' \"%s\"" % (user,deployUser,key,password,cmd_exec)
        (output,err) = subprocess.Popen(cmd, shell=True, stdout=subprocess.PIPE).communicate()
        if "incorrect password" in output:
            log.error("deploy.py", "Incorrect ssh password")
            exit()
        if "ssh:" in output and "Name or service not known" in output:
            log.error("deploy.py", "ssh: Name or service not known")
            exit()
        if "Permission denied" in output:
            log.error("deploy.py", "ssh: Permission denid")
            exit()
    except Exception, e:
        log.error("deploy.py", "%s" % e)

def main(argv):
    global remote_exec,exec_file
    parser = option_parser()
    (options, args) = parser.parse_args()
    if options.log:
        log.setpath(options.log)

    ''' create executing shell script ''' 
    if options.user:
        username = options.user
    else:   
        username = getpass.getuser()
    if options.collect:
        operation = options.collect 
    elif options.step:
        operation = options.step
    else:   
        operation = "none"
    currenttime = time.strftime('%y%m%d%H%M%S',  time.localtime(time.time())) 
    exec_file = "%s/ldap/%s/%s_%s_%s.sh" % (sysio.SCRIPT_PATH,username,username,operation,currenttime)
    if not os.path.exists("%s/ldap/%s" % (sysio.SCRIPT_PATH,username)):
        os.system("mkdir %s/ldap/%s;chmod 777 %s/ldap/%s" % (sysio.SCRIPT_PATH,username,sysio.SCRIPT_PATH,username))

    if not options.type:
        if not precheck(options.file,options.collect,options.step):
            sys.exit(-1)

        cmd_array = []
        cmd_exec = "%s -t local" % get_cmd(options.file,options.collect,options.step,options.log,options.user)

        os.system("touch %(execfile)s;chmod 777 %(execfile)s" % {"execfile":exec_file})
        execute.set_path(exec_file)
        t = execute.EXECUTE(options.file, options.user)

        machine = False
        if options.machine:
            machine = options.machine
        elif options.collect:
            if t.parser.collect_list[options.collect].has_key("collect.hostname"):
                machine = t.parser.collect_list[options.collect]["collect.hostname"]

        if machine and machine != "":
            machine_array = machine.split(',')
            for item in machine_array:
                cmd = "%s %s \'. ~/.bash_profile;cd %s;%s\'" % (remote_exec,item,sysio.SCRIPT_PATH,cmd_exec)
                cmd_array.append(cmd)
        else:
            cmd_array.append(cmd_exec)

        try:
            path = "%s/deploy.py" % sysio.SCRIPT_PATH
            if os.path.exists(path):
                for cmd in cmd_array:
                    if options.user and options.password and options.key:
                        if options.deployUser:
                            user_exec(options.user,options.key,options.password,cmd,options.deployUser)
                        else:
                            user_exec(options.user,options.key,options.password,cmd,options.user)
                    else:
                        if sysio.DEPLOY_MODE == "shell":
                            os.system(cmd)
                        else:
                            (status, output) = commands.getstatusoutput(cmd)
                            if status != 0:
                                log.error("deploy.py", "执行命令：%s 失败，输出为：%s" % (cmd,output))
            else:
                log.error("deploy.py","No such file: %s" % path)
        except Exception,e:
            log.error("deploy.py","%s" % e)

    elif options.type == "local":
        exec_local(options.file,options.collect,options.step,options.user)
    else:
        log.error("deploy.py","error value of \"type\" parameter")

if __name__ == "__main__":
    main(sys.argv)
