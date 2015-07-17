#!/usr/bin/python
#encoding: utf-8

# refer page: http://stackoverflow.com/questions/279237/python-import-a-module-from-a-folder

import imp
import os
import sys
import glob
from lib import *

class TestDriver:

    @staticmethod
    def run(testDescription, caseRootDirName = "testcases"):
        caseDirList = ("smokeTest", "commonTest", "channelTest", "dictTest", "dspTest", "mailTest", "offlineTest", "searchTest", "unionTest")
        Log.saveEntryHtml(testDescription)

        for caseDir in caseDirList:
            caseFileList = glob.glob("%s/%s/case_*.py" % (caseRootDirName, caseDir))
            Log.setTestSuiteName(caseDir)

            for caseFile in caseFileList:
                caseFilename = os.path.basename(caseFile)
                fileMain = caseFilename[:-3]
                Log.setCaseName(fileMain)
                module = imp.load_source(fileMain, caseFile)
                module.tc.executor.execute()
            
if __name__ == "__main__":
    """
    try:
        TestDriver.run(sys.argv[1])
    except:
        print "错误! 请给出测试描述。"
        print "Usage: ./testRunner desription"
    """

    TestDriver.run(sys.argv[1])
