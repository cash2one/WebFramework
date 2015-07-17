#!/usr/bin/python

from Utility import *

class TestConf:

    def __init__(self, confPath):
        self.confDict = Utility.readConfFile(confPath)

    def __getitem__(self, key):
        return self.confDict.get(key, None)

if __name__ == "__main__":
    tConf = TestConf("../conf/global_setting.conf")
    print tConf["InputFile"]
