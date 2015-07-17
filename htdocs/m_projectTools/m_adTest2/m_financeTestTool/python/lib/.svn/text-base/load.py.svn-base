#!/usr/bin/python

import sys
import pickle

def output_obj(obj):
    if type(obj) == list:
        print "[", 
        for v in obj:
            output_obj(v)
        print "],"
    elif type(obj) == dict:
        print "{"
        for k, v in obj.items():
            print k, ":", 
            output_obj(v)
        print "},"
    else:
        print obj, ",",

file = open(sys.argv[1])
obj = pickle.load(file)
output_obj(obj)
file.close()
