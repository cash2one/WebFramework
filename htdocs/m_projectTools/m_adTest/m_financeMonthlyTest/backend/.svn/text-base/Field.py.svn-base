#!/usr/bin/python

from Type import *

class Field:
    def __init__(self, name, type, value):
        self.name  = name
        self.type  = type
        self.value = value

    def get_sql_cmd_value(self):
        if self.value == None:
            return "null"

        if self.type in (Type.varchar, Type.datetime, Type.date, Type.timestamp):
            return '"%s"' % self.value
        elif self.type in (Type.smallint, Type.int, Type.bigint, Type.float):
            return "%s" % self.value
        else:
            raise Exception, "Unknown type:%s" % self.type
