#!/usr/bin/python

from Log import *
from Utility import *

class Redis:

    def __init__(self, host, port):
        self.host = host
        self.port = port

    def empty(self):
        Log.write("clear redis")
        Utility.emptyRedis(self.host, self.port)
        return self
