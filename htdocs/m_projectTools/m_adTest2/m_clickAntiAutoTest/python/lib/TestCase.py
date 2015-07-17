#!/usr/bin/python

from TestConf import *
from ClickAction import *
from Redis import *
from UnchargedClicksDB import *
from FraudClickDB import *
from ResultCollection import *
from Validator import *
from Request import *
from Log import *

class Setup:
    def __init__(self, tcObj):
        self._tcObj = tcObj
        self.clickAction = self._tcObj.clickAction
        self.redis = self._tcObj.redis
        self.unchargedClicksTable = self._tcObj.unchargedClicksTable
        self.fraudClickTable = self._tcObj.fraudClickTable
        self.validator = self._tcObj.validator
        self.request = self._tcObj.request

    def setValidator(self, validator):
        Log.write('set Validator: %s' % validator)
        if self._tcObj.validator == None:
            self._tcObj.validator = validator
        else:
            self._tcObj.validator.setNextValidator(validator)

        return self


class Executor:
    def __init__(self, tcObj):
        self._tcObj = tcObj

    def execute(self):
        Log.write("Execute test")
        self._tcObj.request.request()
        self._tcObj.validator.validate(self._tcObj.testResult)
        Log.saveCaseDetail(self._tcObj)


class Cleanup:
    def __init__(self, tcObj):
        self._tcObj = tcObj

    def clean(self):
        print self._tcObj.testResult.retVal, self._tcObj.testResult.retMsg


class TestCase:
    def __init__(self, title, description):
        Log.write("")
        Log.write("===== title: %s" % title)
        Log.write("===== description: %s" % description)
        self.title = title
        self.description = description

        testConfObj         = TestConf("conf/global_setting.conf")
        self.clickAction    = ClickAction(testConfObj["InputFile"]).reset()
        self.redis          = Redis(testConfObj["redisHost"], testConfObj["redisPort"])
        self.unchargedClicksTable = UnchargedClicksTable(testConfObj["clickDbStr"])
        self.fraudClickTable = FraudClickTable(testConfObj["clickDbStr"])
        self.validator          = None
        self.resultCollection   = ResultCollection(testConfObj["OutputFile"]).reset()
        self.request    = Request(testConfObj["ClickUrlPrefix"], testConfObj["userAgent"])
        self.testResult = TestResult(self.resultCollection)

        self.setup    = Setup(self)
        self.executor = Executor(self)
        self.cleanup  = Cleanup(self)

if __name__ == "__main__":
    clickUrl = "/clk/request.s?k=dBFmyUMlQE5co6lz9IzRHn0JdExfUa3JWMLI2SPfhvKDkrhJsqy4JgqDZrOALHfj67qnj0A9Ftph9KgHXdmZQ%2F%2BOsoUguAp7%2FvYx%2BYyyBNN6hUa%2FxyQ8l3MC04u2BXSgxozbzAj06xdZD%2F5qhe9JHC7NiaIhJoibnXFpz6DpaqmKHjzIxtFDvJvnRcaf6361iunmfyzZrh%2FgyMKWH31pO3Xt75cOvyvGH7fClguLQGVP2lA%2FCyPIZBFqNB8pQFyD3Rgh2bVPUahC1%2F43hVYh5H43jxV2Bk4pEhILW5w%2Fj4vfAQf0MFWWn7pOZQdWaWisYflxWgPdqGVStZchZaibBTJRozqcffYAK5OsDkZ8akWHfHByhN1ZhY7DhTvl%2Bfv%2BiVlM29FxcP98IHrq5Q3WZ9fGj6hHFwmmOMCAq5WIKq%2FXxo%2BoRxcJpjjAgKuViCqvUbA%2Ba3ZarJqh014KJcY9NbL%2BgchVEWB5AdS1TNreugA%3D&d=http%3A%2F%2Fwvvw.tairen.net.cn%2F%3Fyx02-01&s=8"

    tc = TestCase("Hello World", "Content for Hello World")
    tc.setup.clickAction.reset().setClickerIp("192.168.1.1")
    tc.setup.setValidator(ClickShouldFilter("IpConsistFilter")).setValidator(ClickShouldFilter("IpConsistFilter2"))
    tc.setup.request.addClickUrl(clickUrl)
    tc.executor.execute()
    tc.cleanup.clean()
