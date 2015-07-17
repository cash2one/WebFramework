#!/usr/bin/python
#encoding:utf-8
import sys
pymongoPath = './python/pymongo/pymongo-2.6.2-py2.7-linux-i686.egg'
sys.path.append(pymongoPath)

from pymongo import MongoClient
import json
import re


regExp = re.compile('^[+-]?\d+$')


def getQueryResult(host, port, dbName, collectionName, query, start_index, limit):
    client = MongoClient(host, port)
    db = client[dbName]
    collection = db[collectionName]
    queryCondition = parseQuery(query)
    #'$host' '$port' '$user' '$password' '$dbname' '$table' '$query_str' '$start_index' '$count'";
    result = []
    columns = set()
    count = 0
    for item in collection.find(queryCondition).skip(start_index).limit(limit=limit):
        if '_id' in item:
            item.pop('_id')
        columns |= set(item.keys())
        result.append(item)
        count += 1
    if count == 0:
        ret = '<h3>empty result!!!</h3>'
        return ret
    columns = list(columns)
    columns.sort()
    ret = '</h3><table id="result_table">\n<caption><h3>查询结果行数: ' + str(count) + '</h3></caption>\n'
    ret += '<tr>'
    for column in columns:
        ret += '\t<th>' + str(column) + '</th>\n'
    ret += '</tr>\n'
    for item in result:
        ret += '\t<tr>\n'
        for i in columns:
            if i in item:
                ret += '\t\t<td>' + str(item[i]) + '</td>\n'
        ret += '\t</tr>\n'
    ret += '</table>'
    return ret


def isInteger(strInt):
    if re.search(regExp, strInt) is None:
        return False
    return True


def parseQuery(query):
    query = query.replace('&#039;', "'")
    #tmp1 = json.loads(query)
    #tmp = dict()
    #for item in tmp1.keys():
    #    key = str(item)
    #    tmp[key] = eval(str(tmp1[item]))
    tmp = eval(str(query))
    for item in tmp.keys():
        if isinstance(tmp[item], int):
            pass
        elif isinstance(tmp[item], str):
            if isInteger(tmp[item]):
                tmp[item] = int(tmp[item])
        else:
            for i in tmp[item].keys():
                if isInteger(str(tmp[item][i])):
                    tmp[item][i] = int(tmp[item][i])
                else:
                    inList = []
                    for j in tmp[item][i]:
                        if isInteger(j):
                            inList.append(int(j))
                        else:
                            inList.append(j)
                    tmp[item][i] = inList
    ret = tmp
    return ret


if __name__ == '__main__':
    if len(sys.argv) < 3:
        print ''
    host = sys.argv[1]
    port = sys.argv[2]
    dbName = sys.argv[5]
    collectionName = sys.argv[6]
    query = sys.argv[7]
    start_index = int(sys.argv[8]) - 1
    limit = sys.argv[9]
    print getQueryResult(host, int(port), dbName, collectionName, query, start_index, int(limit))
