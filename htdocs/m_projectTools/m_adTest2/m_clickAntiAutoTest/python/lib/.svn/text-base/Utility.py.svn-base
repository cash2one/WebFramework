#!/usr/bin/python
#encoding: utf-8

import sys
sys.path.append("..")

import os
import time
import MySQLdb
import redis

from Log import *

class Utility:

    # =============== private functions used by Utility only ===========
    @staticmethod
    def getDBConnection(db_str):
        Log.write("get connection from db_url_str: %s" % db_str)
        host, port, dbName, user, passwd = db_str.split(":", 4)
        return MySQLdb.connect(host = host, user = user, passwd = passwd, db = dbName, charset = "utf8")

    @staticmethod
    def readConfFileContent(lines):
        retDict = {}
        for line in lines:
            if not line or line[0] == "#":
                continue
            key, val = line.split("=", 1)
            retDict[key.strip()] = val.strip()
        return retDict

    # ==================================================================

    @staticmethod
    def emptyFile(filePath):
        open(filePath, "w").write("")

    @staticmethod
    def appendToFile(filePath, line):
        Log.write("add line(%s) to file(%s)" % (line, filePath))
        if not line.endswith("\n"):
            line += "\n"
        open(filePath, "a").write(line)

    @staticmethod
    def readConfFile(filePath):
        Log.write("read conf file: %s" % filePath)
        if not os.path.exists(filePath):
            raise Exception("Error: Invalid file path: %s" % filePath)

        return Utility.readConfFileContent(open(filePath).read().splitlines())

    @staticmethod
    def checkAndReadConfFile(filePath, endLine = "*****", maxRetryTimes = 5, timeInterval = 1): # time unit is second
        retDict = {}

        lines = []
        for idx in range(maxRetryTimes):
            lines = open(filePath).read().splitlines()
            if lines and lines[-1] == endLine:
                break
            else:
                time.sleep(timeInterval)
        else:
            raise Exception("Error: read file %s error" % filePath)

        return Utility.readConfFileContent(lines[:-1])
            
    @staticmethod
    def emptyRedis(redisHost, redisPort):
        r = redis.Redis(host = redisHost, port = int(redisPort))
        for key in r.keys():
            r.delete(key)

    @staticmethod
    def emptyTable(click_db_str, tableName):
        connObj = Utility.getDBConnection(click_db_str)
        delete_sql_cmd = "delete from %s" % tableName
        cursor = connObj.cursor()
        cursor.execute(delete_sql_cmd)
        connObj.commit()
        cursor.close()
        connObj.close()

    @staticmethod
    def emptyUnchargedClicksTable(click_db_str):
        Utility.emptyTable(click_db_str, "uncharged_clicks")

    @staticmethod
    def emptyFraudClickTable(click_db_str):
        Utility.emptyTable(click_db_str, "FRAUD_CLICK")
