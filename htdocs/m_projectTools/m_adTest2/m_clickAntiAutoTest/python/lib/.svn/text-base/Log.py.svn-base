#!/usr/bin/python
#encoding:utf-8

import os
import time

class Log:

    logRootDir = "./output"
    timestampStr = time.strftime("%Y%m%d-%H%M%S", time.localtime())
    testSuiteName = None
    caseName = None
    logList = []

    @staticmethod
    def setTestSuiteName(name):
        Log.testSuiteName = name

    @staticmethod
    def setCaseName(name):
        Log.caseName = name

    @staticmethod
    def write(msg):
        msg = "[%s] %s" % (time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()), msg)
        print msg
        Log.logList.append(msg)

    @staticmethod
    def saveEntryHtml(testDescription):
        logIndexFile = "%s/%s.html" % (Log.logRootDir, Log.timestampStr)
        htmlStr = "<tr><td>%s</td><td>%s</td><td>%s</td><td><a href='./php/showLog.php?ts=%s'>查看日志</a></td></tr>"
        open(logIndexFile, "w").write(htmlStr % (time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()), testDescription, os.environ["USER"], Log.timestampStr))

    @staticmethod
    def saveCaseDetail(tcObj):
        logIndexDir = "%s/%s" % (Log.logRootDir, Log.timestampStr)
        if not os.path.exists(logIndexDir):
            os.mkdir(logIndexDir)

        logDir = "%s/%s" % (logIndexDir, Log.testSuiteName)
        if not os.path.exists(logDir):
            os.mkdir(logDir)

        indexFile = "%s/%s.index" % (logDir, Log.caseName)
        if tcObj.testResult.retVal == "PASS":
            htmlStr = "<tr style='background-color:green'><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td><a href='../%s/%s.detail.html'>执行细节</a></td></tr>"
        else:
            htmlStr = "<tr style='background-color:red'><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td>%s</td><td><a href='../%s/%s.detail.html'>执行细节</a></td></tr>"
        htmlStr = htmlStr % (Log.testSuiteName, tcObj.title, tcObj.description, tcObj.testResult.retVal, tcObj.testResult.retMsg, logDir, Log.caseName)
        open(indexFile, "a").write(htmlStr)
        htmlBeginStr = "<html><head><meta charset='utf8'></head><body><pre>"
        htmlEndStr = "</pre></body></html>"
        open("%s/%s.detail.html" % (logDir, Log.caseName), "w").write(htmlBeginStr + "\n".join(Log.logList) + htmlEndStr)

        Log.logList = []
