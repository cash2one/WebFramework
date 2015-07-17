#!/usr/bin/python2.4 -u
# coding=utf-8
#
# Authour: kuyan@rd.netease.com
# Created: 2011 Dec 13 Tue 18:26:28 PM CST

import os
import sys
import optparse
import time
import getpass

script_path = "/global/share/test/deploy_web" # path of the "BackEnd" script will be copied to
logpath = ""
confpath = ""

help_file ="""
[Usage]:                                             
    deployment file                                           
    REQUIERED parameter.                                      
[Demo]:                                                 
    ./run.py -f config_file -c index_list                 
"""

help_machine ="""
[Usage]:                                                                                                                  
    remote hostname for remotely deployment                            
    OPTIONAL parameter.                                                         
[Demo]:                                                                                 
    ./run.py -f config_file -c index_list -m nb404                     
"""

help_collect ="""
[Usage]:                                                               
    one of the collection names in deployment file                        
    OPTIONAL parameter                                                            
[Demo]:                                                                          
    ./run.py -f config_file -c index_list                         
"""    

help_step ="""
[Usage]:                                                               
    one or several step names in deployment file                        
    OPTIONAL parameter                                                                                                          
[Demo]:                                                                          
    ./run.py -f config_file -s start_impr,stop_impr                                  
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

def prepare_env(conffile,log,user,collect,step):
    global logpath,confpath
    try:
        if os.path.exists(script_path):
            '''create log'''
            if user:
                username = user
            else:
                username = getpass.getuser()
            if collect:
                operation = collect
            elif step:
                operation = step
            else:
                operation = "none"
            currenttime = time.strftime('%y%m%d%H%M%S',  time.localtime(time.time())) 
            logpath = "%s/log/log.%s.%s.%s" % (script_path,username,operation,currenttime)
            if not os.path.exists("%s/log" % script_path):
                os.system("mkdir %s/log;chmod 777 log" % script_path)
            
            linklog = "log"
            if log:
                linklog = log
            if os.path.islink(linklog):
                os.system("rm %s" % linklog)
            linkdir = os.path.dirname(linklog)
            if linkdir != '':
                os.system("mkdir -p %s" % linkdir)
            os.system("touch %(logpath)s;chmod 777 %(logpath)s;ln -s %(logpath)s %(linklog)s" % {"logpath":logpath,"linklog":linklog})
            '''copy deployment file to global'''
            if os.path.exists(conffile):
                confpath = "%s/conf/%s.%s.%s.%s" % (script_path,os.path.basename(conffile),username,operation,currenttime)
                if not os.path.exists("%s/conf" % script_path):
                    os.system("mkdir %s/conf;chmod 777 conf" % script_path)
                os.system("cp %s %s;chmod 777 %s" % (conffile,confpath,confpath))
            else:
                print "No such file: %s" % conffile
                sys.exit(-1)
        else:
            print "No such file: %s" % scrip_path
            sys.exit(-1)
    except Exception,e:
        print e
     

def get_cmd(file,collect,step,machine,user,password,key):
    ''' get the cmd string '''
    global confpath
    cmd = "./deploy.py"
    if file:
        cmd += " -f %s" % confpath
    if collect:
        cmd += " -c %s" % collect
    if step:
        cmd += " -s %s" % step
    if machine:
        cmd += " -m %s" % machine
    if user:
        cmd += " -u %s" % user
    if password:
        cmd += " -p \'%s\'" % password
    if key:
        cmd += " -k \'%s\'" % key
    cmd += " -l %s" % logpath
    print cmd
    return cmd 

def main(argv):
    parser = option_parser()
    (options, args) = parser.parse_args()
    prepare_env(options.file,options.log,options.user,options.collect,options.step)


    pid_file_name = ""
    if options.user:
        pid_file_name += options.user
    if options.collect:
        pid_file_name += "."+options.collect
    if options.step:
        pid_file_name += "."+options.step
    if options.machine:
        pid_file_name += "."+options.machine

    if options.user:
        if not os.path.exists(script_path+"/ldap/"+options.user):
            os.system("cd %s/ldap/; mkdir %s; chmod 777 %s" % (script_path, options.user, options.user))
        pid_file_name = script_path+"/ldap/"+options.user+"/"+pid_file_name+".pid"
       # if os.path.exists(pid_file_name):
        #    print "Error: Service may be already started. Check the status first."
         #   sys.exit(-1)
    else:
        pid_file_name += ".pid"
        if os.path.exists(pid_file_name):
            print "Error: Service may be already started. Check the status first."
            sys.exit(-1)

    pid_file = open(pid_file_name,'w')
    pid = os.getpid()
    pid_file.write("%s" % pid)
    pid_file.close()
    os.system("chmod 777 %s" % pid_file_name)

    cmd = get_cmd(options.file,options.collect,options.step,options.machine,options.user,options.password,options.key)
    try:
        os.system("cd %s;%s" % (script_path,cmd))
    except Exception,e:
        print e

if __name__ == "__main__":
    main(sys.argv)
