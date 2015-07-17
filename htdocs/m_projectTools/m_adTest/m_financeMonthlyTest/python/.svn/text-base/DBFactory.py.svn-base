#!/usr/bin/python
#encoding: utf-8

import re
import os
import sys
import MySQLdb

# =========== Class definitions Area ==============
class TestDeploy:
    def __init__(self, finance_host, hibernate_prop_file):
        # read host and hibernate.properties file path
        self.finance_host              = finance_host
        self.hibernate_prop_file       = hibernate_prop_file
        self.local_hibernate_prop_file = "hibernate.properties"

        # save conf as key-value pairs in dict
        self.kv_dict = {}

        # read confs in hibernate.properties
        self._copy_file_to_local()
        self._read_conf_file()

    def _copy_file_to_local(self):
        cmd = "scp %s:%s %s" % (self.finance_host, self.hibernate_prop_file, self.local_hibernate_prop_file)
        ret = os.system(cmd)
        if ret != 0: sys.exit(1)

    def _read_conf_file(self):
        lines = open(self.local_hibernate_prop_file).read().splitlines()
        for line in lines:
            line = line.strip()
            if not line: continue
            if "=" not in line: continue

            key, val = line.split("=", 1)
            self.kv_dict[key] = val

    def getDict(self):
        return self.kv_dict


class DBFactory:
    # used to save connections
    kv_dict = None
    my_kv   = {}

    @staticmethod
    def setConf(conf_str):
        host, conf_path = conf_str.strip().split(":", 1)
        DBFactory.kv_dict = TestDeploy(host, conf_path).getDict()

    @staticmethod
    def getConn(type_name):
        if DBFactory.my_kv.has_key(type_name):
            return DBFactory.my_kv[type_name]

        if type_name in ("finance", "adclick", "adupdate", "adpublish"):
            url_key       = "hibernate.connection.%s.url" % type_name
            username_key  = "hibernate.connection.%s.username" % type_name
            passsword_key = "hibernate.connection.%s.password" % type_name

            url     = DBFactory.kv_dict.get(url_key, "")
            _user   = DBFactory.kv_dict.get(username_key, "")
            _passwd = DBFactory.kv_dict.get(passsword_key, "")
            if url == "" or _user == "" or _passwd == "":
                sys.exit(1)

            reObj = re.search("jdbc:mysql://(\w+)/(.*?)\?", url)
            if not reObj: sys.exit(1)

            _host = reObj.group(1)
            _db   = reObj.group(2)

            DBFactory.my_kv[type_name] = MySQLdb.connect(host=_host, user=_user, passwd=_passwd, db=_db, port=3306, charset='utf8')

        elif type_name in ("logdb", ):
            DBFactory.my_kv[type_name] = MySQLdb.connect(host="tb081", user="test", passwd="test", db="logdb", port=3306, charset='utf8')

        return DBFactory.my_kv[type_name]
