#!/usr/bin/python
#encoding:utf-8

import re
import os
import sys
import time

def get_milli_timestamp(time_str):
    if len(time_str) == 8:
        struct_time = time.strptime(time_str, "%Y%m%d")
    elif len(time_str) == 10:
        struct_time = time.strptime(time_str, "%Y%m%d%H")

    return int(time.mktime(struct_time) * 1000)


class LogType:
    ValidClickLog = 0
    UnValidClickLog = 1
    ExceptionLog = 2
    ErrorLog = 3

class LineParser:

    @staticmethod
    def getPlatformName(syndId):
        syndIdVal = int(syndId)
        if syndIdVal in range(5, 11):
            return "EADM"
        elif syndIdVal in range(10, 16) + range(17, 21):
            return "EADC"
        elif syndIdVal == 16:
            return "OFFLINE"
        elif syndIdVal in range(50, 101):
            return "EADD"
        elif syndIdVal in range(101, 151):
            return "DSP"
        elif syndIdVal >= 1000:
            return "EADU"
        else:
            return "EADS"
    
    @staticmethod
    def parse(line):
        if ("INFO CacFilterContainer" in line) and ("PASS]" not in line):
            return None
        elif line.startswith("201"):
            log_time = line[:14].replace("-", "").replace(":", "").replace(" ", "")  # 到小时粒度 yymmddhh
        else:
            return None

        if "[PASS]" in line:
            retStatus = "PASS"
            reObj = re.search("syndId=(\d+)", line)
            if reObj:
                syndId = reObj.group(1)
            
            return LogType.ValidClickLog, log_time, LineParser.getPlatformName(syndId)

        elif "[NOT PASS]" in line:
            retStatus = "FAIL"
            reObj = re.search("\[NOT PASS\]\[(\w+).*?\].*syndId=(\d+)", line)
            if reObj:
                filterName = reObj.group(1)
                syndId = reObj.group(2)
                return LogType.UnValidClickLog, log_time, LineParser.getPlatformName(syndId), filterName

        elif "Exception" in line:
            field1, field2, exceptionTitle = line.split(" ", 2)
            return LogType.ExceptionLog, log_time, exceptionTitle[:-1]

        elif "ERROR" in line:
            field1,errorTitle = line.split(" ERROR ", 1)
            return LogType.ErrorLog, errorTitle

        else:
            return None

class AntiLogParser:
    
    def __init__(self, log_files): # date_str format like yyyymmdd
        self.log_file_list = log_files
        self.clickHourlyDict = {}
        self.clickDailyDict = {}
        self.exceptionDailyDict = {}
        self.errorDailyDict = {}

        self.eePlatformDailyDict = {}
 
        self.platformList = ["EADM", "EADC", "EADU", "OFFLINE", "EADS", "EADD", "DSP"]
        self.platformDict = {"EADM":"邮箱", "EADC":"频道", "EADU":"联盟", "OFFLINE":"线下直销", "EADS":"搜索", "EADD":"词典", "DSP":"智选", "Exception":"异常", "ClickException":"点击异常","ClickInvalid":"点击非法","click404":"点击404"}

        self.platformFilterNameDict = {}
        for platform in self.platformList:
            self.platformFilterNameDict[platform] = set()

    def parse(self, date_str):
        self.date_str = date_str

        for platform in self.platformList:
            self.clickHourlyDict[platform] = {}
            for i in range(24):
                tsKey = "%s%02d" % (date_str, i)
                self.clickHourlyDict[platform][tsKey] = {}

        for platform in self.platformList:
            self.clickDailyDict[platform] = {}

        for log_file in self.log_file_list:
            if not os.path.exists(log_file):
                continue

            platform_type = None
            ee_title = None
            ee_line  = None
            
            for line in open(log_file):
                retObj = LineParser.parse(line)
                if retObj == None: continue

                if retObj[0] == LogType.ValidClickLog:
                    log_time, platform = retObj[1:]
                    self.clickHourlyDict[platform][log_time]["valid"] = self.clickHourlyDict[platform][log_time].get("valid", 0) + 1
                    self.clickDailyDict[platform]["valid"] = self.clickDailyDict[platform].get("valid", 0) + 1

                    platform_type = platform

                elif retObj[0] == LogType.UnValidClickLog:
                    log_time, platform, filterName = retObj[1:]
                    self.platformFilterNameDict[platform].add(filterName)

                    self.clickHourlyDict[platform][log_time]["unValid"] = self.clickHourlyDict[platform][log_time].get("unValid", 0) + 1
                    self.clickDailyDict[platform]["unValid"] = self.clickDailyDict[platform].get("unValid", 0) + 1

                    self.clickHourlyDict[platform][log_time][filterName] = self.clickHourlyDict[platform][log_time].get(filterName, 0) + 1
                    self.clickDailyDict[platform][filterName] = self.clickDailyDict[platform].get(filterName, 0) + 1

                    platform_type = platform

                elif retObj[0] == LogType.ExceptionLog:
                    log_time, exceptionTitle = retObj[1:] 
                    self.exceptionDailyDict[exceptionTitle] = self.exceptionDailyDict.get(exceptionTitle, 0) + 1

                    ee_line  = line
                    ee_title = exceptionTitle
 
                elif retObj[0] == LogType.ErrorLog:
                    errorTitle = retObj[1]
                    self.errorDailyDict[errorTitle] = self.errorDailyDict.get(errorTitle, 0) + 1

                    ee_line  = line
                    ee_title = errorTitle
                
                ### 统计每个平台的异常
                if (retObj[0] in (LogType.ExceptionLog, LogType.ErrorLog)) and (platform_type != None):
                    # 平台在前，异常或错误在后
                    for ee_str in ("ERROR ClickLogDbUpdateQueue", "ERROR FraudClickLogDbUpdateQueue"):
                        if ee_str in ee_line:
                            if not self.eePlatformDailyDict.get(ee_title):
                                self.eePlatformDailyDict[ee_title] = {}
                            self.eePlatformDailyDict[ee_title][platform_type] = self.eePlatformDailyDict[ee_title].get(platform_type, 0) + 1

                            ee_title = None
                            platform_type = None
                            break

                elif (ee_title != None) and (retObj[0] in (LogType.ValidClickLog, LogType.UnValidClickLog)):
                    # 平台在后，异常或错误在前
                    for ee_str in ("ERROR CacFilterContainer",):
                        if ee_str in ee_line:
                            if not self.eePlatformDailyDict.get(ee_title):
                                self.eePlatformDailyDict[ee_title] = {}
                            self.eePlatformDailyDict[ee_title][platform_type] = self.eePlatformDailyDict[ee_title].get(platform_type, 0) + 1

                            ee_title = None
                            platform_type = None
                            break
        return self

    def output(self, dataDir = "../data"):
        if not os.path.exists(dataDir):
            os.mkdir(log_dir)

        if not os.path.exists("%s/%s" % (dataDir, self.date_str)):
            os.mkdir("%s/%s" % (dataDir, self.date_str))

        lines = []
        lines.append("<html><head><meta charset='utf8'></head><body>")
        lines.append(" ".join(map(lambda x: "<a href='%s'>%s</a>" % (x, self.platformDict[x]), self.platformList + ["Exception", "ClickException","ClickInvalid","click404"])))
        lines.append("<br><br>")
        for platform in self.platformList:
            lines.append("<table border='1' id='%s'>" % platform)
            lines.append("<tr><th colspan='%d'>%s</th></tr>" % (len(self.platformFilterNameDict[platform]) + 3, platform))
            lines.append("<tr><th>时间/过滤规则</th><th>" + "</th><th>".join(self.platformFilterNameDict[platform]) + "<th>被过滤的总点击数</th><th>有效点击数</th></th></tr>")

            for i in range(24):
                ts_key = "%s%02d" % (self.date_str, i)
                values = map(lambda x: self.clickHourlyDict[platform][ts_key].get(x, 0), self.platformFilterNameDict[platform])
                values.append(self.clickHourlyDict[platform][ts_key].get("unValid", 0))
                values.append(self.clickHourlyDict[platform][ts_key].get("valid", 0))
                lines.append("<tr><td>%02d:00</td><td>" % i + "</td><td>".join(map(lambda x: str(x), values)) + "</td></tr>")

            values = map(lambda x: self.clickDailyDict[platform].get(x, 0), self.platformFilterNameDict[platform])
            values.append(self.clickDailyDict[platform].get("unValid", 0))
            values.append(self.clickDailyDict[platform].get("valid", 0))
            lines.append("<tr><td>all day</td><td>" + "</td><td>".join(map(lambda x: str(x), values)) + "</td></tr>")
            lines.append("</table>")

            keys = list(self.platformFilterNameDict[platform]) + ["unValid", "valid"]
            open("%s/%s/%s" % (dataDir, self.date_str, platform), "w").write("\n".join(map(lambda x, y: x + ":" + str(y), keys, values)))

        lines.append("<table border='1' id='exception'>")
        lines.append("<tr><th colspan='2'>反作弊服务中的异常</th></tr>")
        lines.append("<tr><th>标题</th><th>异常数量</th></tr>")
        for expTitle, expCnt in sorted(self.exceptionDailyDict.items(), key=lambda x: x[1], reverse=True):
            if "truncation" in expTitle:
                expTitle = "<font color='red'>%s</font>" % expTitle
            lines.append("<tr><td>%s</td><td>%d %s</td></tr>" % (expTitle, expCnt, str(self.eePlatformDailyDict.get(expTitle, {}))))
        [ v for v in sorted(self.errorDailyDict.values())]
        for errTitle, errCnt in self.errorDailyDict.items():
            lines.append("<tr><td>%s</td><td>%d %s</td></tr>" % (errTitle, errCnt, str(self.eePlatformDailyDict.get(errTitle, {}))))
        lines.append("</table>")

        click_log = "%s/%s.click-log.html" % (dataDir, self.date_str)
        if os.path.exists(click_log):
            lines.append(open(click_log).read())

        click_invalid_log = "%s/%s.click-invalid-log.html" % (dataDir, self.date_str)
        if os.path.exists(click_invalid_log):
            lines.append(open(click_invalid_log).read())
        
        click_404_log = "%s/%s.click-404-log.html" % (dataDir, self.date_str)
   
        if os.path.exists(click_404_log):
 
            lines.append(open(click_404_log).read())

        lines.append("</body></html>")
        logFile = "%s/%s.log.html" % (dataDir, self.date_str)
        open(logFile, "w").write("\n".join(lines))

        return self

    def output2(self, dataDir = "../result_data"):
        if not os.path.exists(dataDir):
            # should be 777 file mode
            os.mkdir(dataDir)

        for platform in self.platformList:
            plat_dir = "%s/%s" % (dataDir, platform)
            if not os.path.exists(plat_dir):
                os.mkdir(plat_dir)

            self.platformFilterNameDict[platform].add("unValid")
            self.platformFilterNameDict[platform].add("valid")

            for filterName in self.platformFilterNameDict[platform]:
                filterFile  = "%s/%s.hourly" % (plat_dir, filterName)
                filterFile2 = "%s/%s.daily" % (plat_dir, filterName)

                handle = open(filterFile, "a")
                handle2 = open(filterFile2, "a")
                for i in range(24):
                    ts_key = "%s%02d" % (self.date_str, i)
                    click_cnt_hourly = self.clickHourlyDict[platform][ts_key].get(filterName, 0)
                    handle.write("%s:%s\n" % (get_milli_timestamp(ts_key), click_cnt_hourly))

                click_cnt_daily = self.clickDailyDict[platform].get(filterName, 0)
                handle2.write("%s:%s\n" % (get_milli_timestamp(self.date_str), click_cnt_daily))

                handle.close()
                handle2.close()


if __name__ == "__main__":
    """
    for line in open("log"):
        line = LineParser.parse(line)
        if line != None:
            print line
    """

    AntiLogParser(sys.argv[2:]).parse(sys.argv[1]).output().output2()
