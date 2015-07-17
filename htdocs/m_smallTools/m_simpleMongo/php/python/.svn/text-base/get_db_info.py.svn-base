#!/usr/bin/python
import sys
pymongoPath = './python/pymongo/pymongo-2.6.2-py2.7-linux-i686.egg'
sys.path.append(pymongoPath)

from pymongo import MongoClient

CTRL_A = '\x01'
CTRL_B = '\x02'
CTRL_C = '\x03'


def getMongoDBInfo(host, port=27017):
    try:
        client = MongoClient(host, port)
        dbs = []
        for db in client.database_names():
            tmp = client[db].collection_names()
            tmp.sort()

            collectionInDB = ''
            for collectionName in tmp:
                count = client[db][collectionName].count()
                collectionInDB += CTRL_B + collectionName + CTRL_C + str(count)

            dbStr = db + collectionInDB
            dbs.append(dbStr)

        ret = ''
        for i in range(1, len(dbs)):
            ret += (CTRL_A + dbs[i])
        ret = dbs[0] + ret
        return ret
    except Exception, e:
        return ''


if __name__ == '__main__':
    if len(sys.argv) < 3:
        print ''
    host = sys.argv[1]
    port = sys.argv[2]
    print getMongoDBInfo(host, int(port))
