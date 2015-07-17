#!/usr/bin/python
#encoding:utf-8

import re
import os
import sys
import time

class LineParser:
    @staticmethod
    def parse(line):
        #print line
        fzf = line.split(" ")
        if cmp(fzf[9],"404")==0:
           return fzf[7]
        else:
           return None

class ClickAccessLogParser:
    def __init__(self, log_files): # date_str format like yyyymmdd
        self.log_file_list = log_files
        self.fzfDailyDict = {}
        self.sum = 0

    def parse(self, date_str):
        self.date_str = date_str
        print date_str
        for log_file in self.log_file_list:
            if not os.path.exists(log_file):
                continue

            for line in open(log_file):
                retObj = LineParser.parse(line)
                
                if retObj == None: continue
                else:
                    urlTitle = retObj
                    self.fzfDailyDict[urlTitle] = self.fzfDailyDict.get(urlTitle, 0) + 1 
                    self.sum=self.sum+1
        return self

    def output(self, dataDir = "../data"):
        if not os.path.exists(dataDir):
            os.mkdir(dataDir)

        lines = []
        lines.append("<table border='1' id='click404'>")
        lines.append("<tr><th colspan='2'>点击404</th></tr>")
        lines.append("<tr><th>请求url</th><th>数量</th><tr>")
        sortedDict= sorted(self.fzfDailyDict.items(),key=lambda d: d[1],reverse=True)
        lines.append("<tr><td>汇总</td><td>%d</td></tr>" % (self.sum))
        for i in range(len(sortedDict)):
            lines.append("<tr><td>%s</td><td>%d</td></tr>" % (sortedDict[i][0], sortedDict[i][1]))
        lines.append("</table>")

        logFile = "%s/%s.click-404-log.html" % (dataDir, self.date_str)
        open(logFile, "w").write("\n".join(lines))

if __name__ == "__main__":
    """
    for line in open("log"):
        line = LineParser.parse(line)
        if line != None:
            print line
    """

    ClickAccessLogParser(sys.argv[2:]).parse(sys.argv[1]).output()
