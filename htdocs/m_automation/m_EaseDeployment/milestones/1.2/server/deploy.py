#!/usr/bin/python -u
# coding=utf-8
#
# Authour: kuyan@rd.netease.com
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

remote_exec = "ssh -t"

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

def get_cmd(file,collect,step,log):
    ''' get the cmd string '''
    cmd = "./deploy.py -f %s" % file
    if collect:
        cmd += " -c %s" % collect
    if step:
        cmd += " -s %s" % step
    cmd += " -l %s" % log
    return cmd 

def exec_local(file,collect,step):
    ''' execute the deployment locally '''
    t = execute.EXECUTE(file)
    if collect:
        t.exec_collect(t.parser.collect_list[collect])
    else:
        steps = step.split(',')
        for step in steps:
            t.exec_step(t.parser.step_list[step])
 
def user_exec(user,key,password,cmd_exec):
    ''' su user '''
    try:
        cmd = "./su_user.expect %s \'%s\' \'%s\' \"%s\"" % (user,key,password,cmd_exec)
        returncode = os.system(cmd)
        if (returncode == 256):
            log.error("deploy.py","Incorrect ssh password")
    except Exception, e:
        log.error("deploy.py", e)

def main(argv):
    global remote_exec
    parser = option_parser()
    (options, args) = parser.parse_args()
    if options.log:
        log.setpath(options.log)
    
    if not options.type:
        if not precheck(options.file,options.collect,options.step):
            sys.exit(-1)

        cmd_array = []
        cmd_exec = "%s -t local" % get_cmd(options.file,options.collect,options.step,options.log)
        t = execute.EXECUTE(options.file)

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
                        user_exec(options.user,options.key,options.password,cmd)
                    else:
                        os.system(cmd)
            else:
                log.error("deploy.py","No such file: %s" % path)
        except Exception,e:
            log.error("deploy.py",e)

    elif options.type == "local":
        exec_local(options.file,options.collect,options.step)
    else:
        log.error("deploy.py","error value of \"type\" parameter")

if __name__ == "__main__":
    main(sys.argv)
