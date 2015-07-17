#!/usr/bin/python

from Utility import *
from Log import *

class UnchargedClicksTable:
    def __init__(self, db_url_str):
        self.db_url_str = db_url_str

    def empty(self):
        Log.write("clean uncharged_clicks table") 
        Utility.emptyUnchargedClicksTable(self.db_url_str)
