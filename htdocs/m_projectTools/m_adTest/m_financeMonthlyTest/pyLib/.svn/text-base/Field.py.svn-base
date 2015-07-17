#!/usr/bin/python

from Type import *

class Field:
    def __init__(self, name, type, value):
        self.name  = name
        self.type  = type
        self.value = value

    def get_sql_value(self):
        if self.type in (Type.int, Type.bigint):
            return "%s" % self.value
        elif self.type in (Type.varchar, Type.timestamp, Type.datetime, Type.date):
            return '"%s"' % self.value
