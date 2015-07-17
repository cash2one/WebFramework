#!/usr/bin/python

import urllib
import urllib2
import time

from Log import *

class Request:
    def __init__(self, clickUrlPreStr, userAgent, reqTimeInterval = 1):
        self.clickUrlPreStr = clickUrlPreStr
        self.userAgent = userAgent
        self.reqTimeInterval = reqTimeInterval
        self.urlList = []

    def addClickUrl(self, url, times = 1):
        for i in range(times):
            Log.write("add url: %s" % self.clickUrlPreStr + url)
            self.urlList.append(self.clickUrlPreStr + url)

    def request(self):
        for clickUrl in self.urlList:
            Log.write("request url: %s" % clickUrl)
            requestObj  = urllib2.Request(clickUrl)
            requestObj.add_header('User-Agent', self.userAgent)
            response = urllib2.urlopen(requestObj)
            response.read()
            time.sleep(self.reqTimeInterval)
