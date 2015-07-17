#!/usr/bin/python
#encoding=utf-8

import logging
import deploy

LEVEL = logging.DEBUG
logpath = "log/log.0"

def setpath(log):
    global logpath
    logpath = log

def initlog():
    logger = None
    logger = logging.getLogger()
    hdlr = logging.FileHandler(logpath)
    formatter = logging.Formatter('%(asctime)s %(levelname)s %(message)s')
    hdlr.setFormatter(formatter)
    logger.addHandler(hdlr)
    logger.setLevel(logging.INFO)
    return [logger,hdlr]


def logMsg(class_name, level, err_msg):
    message = "%s : %s " % (class_name, err_msg)
    logger,hdlr = initlog()
    logger.log(level, message)
    hdlr.flush()
    logger.removeHandler( hdlr )

def info(class_name, err_msg):
	if logging.INFO >= LEVEL:
		logMsg(class_name, logging.INFO, err_msg)

def debug(class_name, err_msg):
	if logging.DEBUG >= LEVEL:
		logMsg(class_name, logging.DEBUG, err_msg)

def error(class_name, err_msg):
	if logging.ERROR >= LEVEL:
		logMsg(class_name, logging.ERROR, err_msg)

def warn(class_name, err_msg):
	if logging.WARN >= LEVEL:
		logMsg(class_name, logging.WARN, err_msg)

if ( __name__ == "__main__"):
	for k in ["fun1","fun2","fun3"]:
		logMsg("tt",logging.DEBUG, k )
		print "1111"
