#!/usr/bin/python
# coding=utf-8
# filename: fakeSearch.py
##################################
# @author yinxj
# @date 2013-01-05
##################################

import string, os, sys, re, time, logging, commands, random

# logger
logFile='search.log'
logger=logging.getLogger()
hdlr=logging.FileHandler(logFile)
formatter=logging.Formatter('%(asctime)s %(levelname)s %(message)s')
hdlr.setFormatter(formatter)
logger.addHandler(hdlr)
logger.setLevel(logging.DEBUG)

def main(argv):
	state=['success','failed','timeout']
	status=['success','failed']
	while True:
		logger.info("@@ANALYSIS@@ search.process.time=%s" % random.randrange(300,501))
		logger.info("@@ANALYSIS@@ cluster.time=%s" % random.randrange(100,151))
		logger.info("@@ANALYSIS@@ search.process.state=%s" % state[random.randrange(0,3)])
		logger.info("@@ANALYSIS@@ cluster.status=%s" % status[random.randrange(0,2)])
		time.sleep(0.2)

if __name__ == "__main__":
	main(sys.argv)
