#!/usr/bin/python
#encoding:utf-8

import re
import os
import sys
import time

class LogType:
    ExceptionLog = 2
    InvalidLog = 3

class LineParser:
    @staticmethod
    def parse(line):
        if "Exception:" in line:
            if " - " not in line:
                return None
            field1, exceptionTitle = line.split(" - ", 1)
            return LogType.ExceptionLog, exceptionTitle[:-1]
        elif "[INVALID]" in line:
            if "]from" not in line:
                return None
            invalidTitle = line.split("]")[2]
            return LogType.InvalidLog, invalidTitle
        else:
            return None

class ClickLogParser:
    def __init__(self, log_files): # date_str format like yyyymmdd
        self.log_file_list = log_files
        self.exceptionDailyDict = {}
        self.invalidDailyDict = {}

    def parse(self, date_str):
        self.date_str = date_str

        for log_file in self.log_file_list:
            if not os.path.exists(log_file):
                continue

            for line in open(log_file):
                retObj = LineParser.parse(line)
                if retObj == None: continue

                if retObj[0] == LogType.ExceptionLog:
                    exceptionTitle = retObj[1] 
                    self.exceptionDailyDict[exceptionTitle] = self.exceptionDailyDict.get(exceptionTitle, 0) + 1
                elif retObj[0] == LogType.InvalidLog:
                    invalidTitle = retObj[1][1:]
                    self.invalidDailyDict[invalidTitle] = self.invalidDailyDict.get(invalidTitle, 0) + 1
        return self

    def output(self, dataDir = "../data"):
        if not os.path.exists(dataDir):
            os.mkdir(dataDir)

        lines = []
        lines.append("<table border='1' id='clickexception'>")
        lines.append("<tr><th colspan='2'>点击服务中的异常</th></tr>")
        lines.append("<tr><th>标题</th><th>异常数量</th></tr>")
        for expTitle, expCnt in sorted(self.exceptionDailyDict.items(), key=lambda x: x[1], reverse=True):
            lines.append("<tr><td>%s</td><td>%d</td></tr>" % (expTitle, expCnt))
        lines.append("</table>")

        logFile = "%s/%s.click-log.html" % (dataDir, self.date_str)
        open(logFile, "w").write("\n".join(lines))

        #generate invalid
        lines = []
        lines.append("<table border='1' id='ClickInvalid'>")
        lines.append("<tr><th colspan='2'>点击服务中的非法请求</th></tr>")
        lines.append("<tr><th>类型</th><th>非法数量</th><tr>")
        [ v for v in sorted(self.invalidDailyDict.values())]
        for k in self.invalidDailyDict:
            print "self.invalidDailyDict[%s]=%s"%(k,self.invalidDailyDict[k])
        for invTitle, invCnt in self.invalidDailyDict.items():
            lines.append("<tr><td>%s</td><td>%d</td></tr>" % (invTitle, invCnt))
        lines.append("</table>")

        logFile = "%s/%s.click-invalid-log.html" % (dataDir, self.date_str)
        open(logFile, "w").write("\n".join(lines))

if __name__ == "__main__":
    """
    for line in open("log"):
        line = LineParser.parse(line)
        if line != None:
            print line
    """

    ClickLogParser(sys.argv[2:]).parse(sys.argv[1]).output()
