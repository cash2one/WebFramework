#!/usr/bin/python

from Utility import *
from Log import *

class FraudClickTable:
    def __init__(self, db_url_str):
        self.db_url_str = db_url_str

    def empty(self):
        Log.write("clear Fraud_Click db")
        Utility.emptyFraudClickTable(self.db_url_str)
