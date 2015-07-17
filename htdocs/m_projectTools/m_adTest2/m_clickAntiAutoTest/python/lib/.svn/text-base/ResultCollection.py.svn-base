#!/usr/bin/python

from Utility import *
from Log import *

class ResultCollection:

    def __init__(self, outputFile):
        self.outputFile = outputFile
        self.outputDict = {}

    def __getitem__(self, key):
        if not self.outputDict:
            Log.write("load data in output file: %s" % self.outputFile)
            self.outputDict = Utility.checkAndReadConfFile(self.outputFile)
        return self.outputDict.get(key, None)

    def reset(self):
        Log.write("clear output file: %s" % self.outputFile)
        Utility.emptyFile(self.outputFile)
        return self
