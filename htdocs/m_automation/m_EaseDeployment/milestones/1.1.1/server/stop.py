#!/usr/bin/python2.4 -u
# coding=utf-8
#

import os
import sys
import optparse
import time
import getpass
import commands

script_path = "/global/share/test/deploy_web"

def option_parser():
    parser = optparse.OptionParser()
    parser.add_option("-c","--collect",
                      dest="collect",
                      default=False)
    parser.add_option("-s","--step",
                      dest="step",
                      default=False)
    parser.add_option("-m","--machine",
                      dest="machine",
                      default=False)
    parser.add_option("-u","--user",
                      dest="user",
                      default=False)
    return parser

def stop(argv):
    parser = option_parser()
    (options, args) = parser.parse_args()
    pid_file_name = ""
    if options.user:
        pid_file_name += options.user
    if options.collect:
        pid_file_name += "."+options.collect
    if options.step:
        pid_file_name += "."+options.step
    if options.machine:
        pid_file_name += "."+options.machine

    pid_file = script_path+"/ldap/"+options.user+"/"+pid_file_name+".pid"

    # if pid_file is not file
    if not os.path.isfile(pid_file):
        print "STOPPED"
        print "The service is already stopped. Cannot find the file %s." % pid_file
        os.system("rm -rf %s" % pid_file)
        return 0 

    # get pid
    f = open(pid_file,"r")
    pid = f.read()

    # check if pid is exists
    if not os.path.exists("/proc/%s" % pid):
        print "STOPPED"
        print "The service is already stopped. Cannot find its process."
        os.system("rm -rf %s" % pid_file)
        return 0

    # kill 
    (status,output)=commands.getstatusoutput("pstree %s -p | awk -F'[()]' '{for(i=1;i<=NF;i++)if($i~/[0-9]+/)print $i}' | xargs kill -SIGQUIT" % pid)
    if status == 0:
        os.system("rm %s" % pid_file)
        print "STOPPED"
        return  0
    # kill command is fail, so kill -9
    os.system("sleep 2")
    (status,output)=commands.getstatusoutput("pstree %s -p | awk -F'[()]' '{for(i=1;i<=NF;i++)if($i~/[0-9]+/)print $i}' | xargs kill -SIGKILL" % pid)
    os.system("rm %s" % pid_file)
    print "STOPPED"
    return status 

if __name__ == "__main__":
    status = stop(sys.argv)
    print status
