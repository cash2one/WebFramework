#!/usr/bin/python
#encoding:utf-8

import pickle

class FileAdapter:

    @staticmethod
    def read(input_file):
        reader = open(input_file, "rb")
        obj = pickle.load(reader)
        reader.close()
        return obj 

    @staticmethod
    def write(obj, output_file):
        # output to file
        writer = open(output_file, "wb")
        pickle.dump(obj, writer)
        writer.close()
