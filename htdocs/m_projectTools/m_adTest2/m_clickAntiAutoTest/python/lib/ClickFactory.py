#!/usr/bin/python

import random

class ClickFactory:

    linesDSP = open("./testdata/DSP").read().splitlines()
    linesOFFLINE = open("./testdata/OFFLINE").read().splitlines()
    linesEADM = open("./testdata/EADM").read().splitlines()
    linesEADU = open("./testdata/EADU").read().splitlines()
    linesEADS = open("./testdata/EADS").read().splitlines()
    linesEADD = open("./testdata/EADD").read().splitlines()
    linesEADC = open("./testdata/EADC").read().splitlines()

    @staticmethod
    def getDspUrl():
        return random.choice(ClickFactory.linesDSP)

    @staticmethod
    def getOfflineUrl():
        return random.choice(ClickFactory.linesOFFLINE)

    @staticmethod
    def getDictUrl():
        return random.choice(ClickFactory.linesEADD)

    @staticmethod
    def getMailUrl():
        return random.choice(ClickFactory.linesEADM)

    @staticmethod
    def getUnionUrl():
        return random.choice(ClickFactory.linesEADU)

    @staticmethod
    def getSearchUrl():
        return random.choice(ClickFactory.linesEADS)

    @staticmethod
    def getChannelUrl():
        return random.choice(ClickFactory.linesEADC)

if __name__ == "__main__":
    print ClickFactory.getDsp()
