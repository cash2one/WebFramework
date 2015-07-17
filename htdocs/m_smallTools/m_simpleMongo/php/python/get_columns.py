#!/usr/bin/python
import sys
pymongoPath = './python/pymongo/pymongo-2.6.2-py2.7-linux-i686.egg'
sys.path.append(pymongoPath)

from pymongo import MongoClient
from json import dumps


def getColumns(host, port, dbName, collectionName):
    client = MongoClient(host, port)
    db = client[dbName]
    collection = db[collectionName]
    columns = set()
    for item in collection.find(limit=1000):
        columns |= set(item.keys())
    if '_id' in columns:
        columns.remove('_id')
    ret = list(columns)
    ret.sort()
    return dumps(ret)


if __name__ == '__main__':
    if len(sys.argv) < 6:
        print ''
    host = sys.argv[1]
    port = sys.argv[2]
    dbName = sys.argv[5]
    collectionName = sys.argv[6]
    print getColumns(host, int(port), dbName, collectionName)
