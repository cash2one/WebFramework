#!/usr/bin/python
#encoding:utf-8

import MySQLdb
from Type import *
from Field import *
import shutil

import os
import sys
reload(sys)
sys.setdefaultencoding('utf-8')

class Database:
    def __init__(self, hostname, username, password, dbname, port = 3306, charset="utf8"):
        self.connObj = MySQLdb.connect(hostname, username, password, dbname, port, charset)
        self.hostname = hostname
        self.username = username
        self.password = password
        self.dbname   = dbname
        self.port     = port
        self.charset  = charset

    def getConnection(self):
        return self.connObj


class Builder:
    def __init__(self):
        self.table_db_list = []
        self.rowFile       = "./Row.py"
        self.tableFile     = "./Table.py"
        self.tableListFile = "./TableList.py"

    #def add(self, table_name, dbObj, query_keyname, query_value = "64", deletable = True):
    def add(self, table_name, dbObj, param_tuple):
        temp_list = [table_name, dbObj]
        for i in range(4):
            temp_list.append(param_tuple[i])
        self.table_db_list.append(temp_list)

    def _get_class_name(self, table_name):
        name_fields = table_name.split("_")
        for idx, name in enumerate(name_fields):
            name = name.lower()
            name = name[0].upper() + name[1:]
            name_fields[idx] = name
        return "".join(name_fields)

    def build(self):
        row_file_lines = [
            "#!/usr/bin/python",
            "#encoding:utf-8\n",
            "from Field import *\n",
        ]

        table_file_lines = [
            "#!/usr/bin/python",
            "#encoding:utf-8\n",
            "from %s import *" % os.path.basename(self.rowFile)[:-3],
            "import MySQLdb", 
            "",
        ]

        tablelist_file_lines = [
            "#!/usr/bin/python",
            "#encoding:utf-8\n",
            "from %s import *" % os.path.basename(self.tableFile)[:-3],
            "",
        ]

        indent1 = "    "
        indent2 = indent1 * 2
        indent3 = indent1 * 3

        tablelist_file_lines.append("class TableListClass:")
        tablelist_file_lines.append("%sdef __init__(self):" % indent1)
        tablelist_file_lines.append("%sself.tables = [" % indent2)

        for table_name, dbObj, qry_keyname, qry_value, writable, deletable in self.table_db_list:
            sql_cmd = "desc %s" % table_name

            conn = dbObj.getConnection()
            cursor = conn.cursor()
            cursor.execute(sql_cmd)
            results = cursor.fetchall()
            cursor.close()

            table_name2 = self._get_class_name(table_name)

            rowClassName = "%sRow" % table_name2
            row_file_lines.append("class %s:" % rowClassName)
            row_file_lines.append("%sdef __init__(self):" % indent1)
            row_file_lines.append("%sself.fields = [" % indent2)

            tableClassName = "%sTableClass" % table_name2
            tablelist_file_lines.append("%s%sTable," % (indent3, table_name2))

            table_file_lines.append("class %s:" % tableClassName)
            table_file_lines.append("%sdef __init__ (self):" % indent1)
            table_file_lines.append("%sself.table_name = '%s'" % (indent2, table_name))
            table_file_lines.append("%sself.query_keyname = '%s'" % (indent2, qry_keyname))
            table_file_lines.append("%sself.query_value = '%s'" % (indent2, qry_value))
            table_file_lines.append("%sself.writable = %s" % (indent2, writable))
            table_file_lines.append("%sself.deletable = %s" % (indent2, deletable))
            table_file_lines.append("%sself.rows = []" % indent2)
            table_file_lines.append("%sself.conn = None\n" % indent2)

            table_file_lines.append("%sdef build_row(self):" % indent1)
            table_file_lines.append("%sreturn %s()\n" % (indent2, rowClassName))

            table_file_lines.append("%sdef get_conn(self):" % indent1)
            table_file_lines.append("%sreturn MySQLdb.connect(host='%s', user='%s', passwd='%s', db='%s', port=%d, charset='%s', use_unicode=True)\n" % (indent2, dbObj.hostname, dbObj.username, dbObj.password, dbObj.dbname, dbObj.port, dbObj.charset))
            table_file_lines.append("# 实例化Table(%s)对象" % tableClassName)
            table_file_lines.append("%sTable = %s()\n" % (table_name2, tableClassName))

            for slist in results:
                field_name  = slist[0]
                field_type  = slist[1].split("(")[0]
                def_value = slist[4]
                if field_type in (Type.varchar, Type.date, Type.datetime, Type.timestamp) and def_value != None:
                    def_value = "'%s'" % def_value
                row_file_lines.append("%sField('%s', Type.%s, %s)," % (indent3, field_name, field_type, def_value))

            row_file_lines.append("%s]" % indent2)
            row_file_lines.append("")

        tablelist_file_lines.append("%s]\n" % indent2)
        tablelist_file_lines.append("TableList = TableListClass()")

        open(self.rowFile, "w").write("\n".join(row_file_lines))
        open(self.tableFile, "w").write("\n".join(table_file_lines))
        open(self.tableListFile, "w").write("\n".join(tablelist_file_lines))
        

if __name__ == "__main__":
    financeDB   = Database("tb081", "test", "test", "financeDB")
    adClickDB   = Database("tb081", "test", "test", "adclickDB")
    adUpdateDB  = Database("tb081", "test", "test", "adupdateDB")
    adPublishDB = Database("tb081", "test", "test", "adpublishDB")
    logDB       = Database("tb081", "test", "test", "logdb")

    # param list
    query_keyname = "SPONSOR_ID"
    query_value = "64"
    writable = True
    deletable = True
    param_tuple = (query_keyname, query_value, writable, deletable)

    # form builder object
    builder = Builder()
    builder.add("Log_DA", logDB, ("adSponsorId", query_value, writable, deletable))
    builder.add("Log_DA_SIM", logDB, ("adSponsorId", query_value, writable, deletable))
    builder.add("Sponsor", adPublishDB, (query_keyname, query_value, False, False))
    builder.add("SPONSOR_BALANCE", financeDB, param_tuple)
    builder.add("SPONSOR_MONTHLY_CONTRACT", financeDB, param_tuple)
    builder.add("SPONSOR_MONTHLY_DISCOUNT", financeDB, param_tuple)
    builder.add("SPONSOR_MONTHLY_DISCOUNT_DETAIL", financeDB, ("SMD_ID", "*", writable, deletable))
    builder.add("SPONSOR_MONTHLY_SETTLEMENT", financeDB, param_tuple)
    builder.add("SPONSOR_ACCOUNT_HISTORY", financeDB, ("TO_USER_ID", query_value, writable, deletable))
    builder.add("CLICK", adClickDB, param_tuple)
    builder.add("AD_CLICK_CHARGE_UP_PROGRESS", financeDB, ("ACCUP_ID", "*", False, False))
    builder.add("SPONSOR_TODAY_COST", financeDB, param_tuple)
    builder.add("SPONSOR_DAILY_COST", financeDB, param_tuple)
    builder.add("SPONSOR_BALANCE_CHANGE", adUpdateDB, param_tuple)

    #builder.add("SPONSOR_TODAY_COST_copy", financeDB, param_tuple)
    #builder.add("SPONSOR_DAILY_COST_copy", financeDB, param_tuple)

    builder.build()
