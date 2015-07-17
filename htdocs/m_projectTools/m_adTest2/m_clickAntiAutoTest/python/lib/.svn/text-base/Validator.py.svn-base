#!/usr/bin/python
#encoding:utf-8

from TestResult import *

class Validator:
    def __init__(self):
        self.nextValidator = None

    def validate(self, testResult):
        self.doValidate(testResult)

        if testResult.retVal == "PASS":
            if self.nextValidator != None:
                self.nextValidator.validate(testResult)

    def setNextValidator(self, validator):
        if self.nextValidator == None:
            self.nextValidator = validator
        else:
            self.nextValidator.setNextValidator(validator)


class ClickShouldFilter(Validator):
    def __init__(self, filterName):
        self.filterName = filterName
        Validator.__init__(self) 

    def doValidate(self, testResult):
        if self.filterName in testResult.resultCollection["filter_list_str"]:
            testResult.setVal("PASS")
            testResult.setMsg("Validation PASS for ClickShouldFilter(%s); " % self.filterName)
        else:
            testResult.setVal("FAIL")
            testResult.setMsg("Validation FAIL for ClickShouldFilter(%s); " % self.filterName)

    def __str__(self):
        return "点击应该被过滤掉"


class ClickShouldNotFilter(Validator):
    def __init__(self, filterName):
        self.filterName = filterName
        Validator.__init__(self) 

    def doValidate(self, testResult):
        if self.filterName not in testResult.resultCollection["filter_list_str"]:
            testResult.setVal("PASS")
            testResult.setMsg("Validation PASS for ClickShouldNotFilter(%s); " % self.filterName)
        else:
            testResult.setVal("FAIL")
            testResult.setMsg("Validation FAIL for ClickShouldNotFilter(%s); " % self.filterName)

    def __str__(self):
        return "点击不应该被过滤掉"
