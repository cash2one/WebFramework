#!/usr/bin/python

import glob
import os

testDirList = (
    "smokeTest",
    "commonTest",
    "channelTest",
    "dictTest",
    "dspTest",
    "mailTest",
    "offlineTest",
    "searchTest",
    "unionTest",
)

open("testcases/__init__.py", "w").write("\n".join(map(lambda x: "import %s" % x, testDirList)))

for dir in testDirList:
    fileList = glob.glob("./testcases/%s/case_*.py" % dir)
    open("testcases/%s/__init__.py" % dir, "w").write("\n".join(map(lambda x: "import %s" % os.path.basename(x)[:-3], fileList)))
