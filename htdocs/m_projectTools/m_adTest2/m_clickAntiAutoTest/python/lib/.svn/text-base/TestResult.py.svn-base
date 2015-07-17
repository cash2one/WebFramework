#!/usr/bin/python

from Utility import *
from ResultCollection import *
from Log import *

class TestResult:

    def __init__(self, resultCollection):
        self.resultCollection = resultCollection

        self.retVal = ""
        self.retMsg = ""

    def setVal(self, val):
        Log.write("set val: %s" % val)
        self.retVal = val

    def setMsg(self, msg):
        Log.write("set msg: %s" % msg)
        self.retMsg += msg
