#!/usr/bin/python
#coding:utf-8

import cPickle as pickle
import sys

from GlobConfReader import *
from SchemaReader import *

class WebPageBuilder:
    def __init__(self, conf_file, schema_dir, data_file1, data_file2):
        self.conf_file = conf_file
        self.schema_dir = schema_dir
        self.data_file1 = data_file1
        self.data_file2 = data_file2

    def check(self):
        table_data_list1 = self.get_file_data(self.data_file1)
        table_data_list2 = self.get_file_data(self.data_file2)

        self.check_list(table_data_list1, table_data_list2)

    def get_file_data(self, file):
        handle = open(file)
        table_data_list = pickle.load(handle)
        handle.close()
        return table_data_list

    def check_list(self, data_list1, data_list2):

        ret_list = [
            "<html>",
            "<head>",
            "<meta charset='utf8'>",
            "</head>",
            "<body>",
        ]

        title_list = []
        body_list = []

        confReader = GlobConfReader(self.conf_file)
        confReader.read()
        schema_list = confReader.get_schema_list()

        data_list1_len = len(data_list1)
        data_list2_len = len(data_list2)
        if data_list1_len != data_list2_len:
            pass

        for idx in range(data_list1_len):
            schema_name = schema_list[idx]
            schema_file = "%s/%s.schema" % (self.schema_dir, schema_name)
            schemaReader = SchemaReader(schema_file)
            schemaReader.read()
            field_name_list = schemaReader.get_field_name_list()
            field_name_html_str = "<tr><th>" + "</th><th>".join(field_name_list) + "</th></tr>"

            table1_name, table1_data_dict = data_list1[idx]
            table2_name, table2_data_dict = data_list2[idx]

            if table1_name != table2_name:
                pass

            sort_key_list = []
            sort_key_list.extend(table1_data_dict.keys())
            for key in table2_data_dict.keys():
                if key not in sort_key_list:
                    sort_key_list.append(key)
            # 按数字排序
            sort_key_list.sort(cmp=lambda x,y:cmp(int(x), int(y)))

            list1_html, list2_html, is_same = self.get_html_result(schemaReader, sort_key_list, table1_data_dict, table2_data_dict)
            if is_same == True:
                title_list.append("<td><a href=''>" + table1_name + "</a></td>")
            else:
                title_list.append("<td><a class=red href=''>" + table1_name + "</a></td>")

            body_list.append("<table border='1' class='table_results' name='" + table1_name + "'>")
            body_list.append("<thead><tr><th colspan=" + str(schemaReader.get_fields_count()) + ">" + table1_name + "(" + schemaReader.get_table_name() + " 1)" +  "</th></tr></thead>")
            body_list.append(field_name_html_str)
            body_list.extend(list1_html)

            # body_list.append("<tr><th colspan=" + str(schemaReader.get_fields_count()) + ">状态二</th></tr>")
            body_list.append("<tr><th colspan=" + str(schemaReader.get_fields_count()) + ">" + table1_name + "(" + schemaReader.get_table_name() + " 2)" +  "</th></tr>")
            body_list.extend(list2_html)
            body_list.append("</table>")

        ret_list.append("<table border='1' id='index_table'><tr>" + "\n".join(title_list) + "</tr></table>")
        ret_list.append("<br>");
        ret_list.append("\n".join(body_list))
        ret_list.append("</body>")
        ret_list.append("</html>")

        print "\n".join(ret_list)

    def get_html_result(self, schemaReader, sorted_keys, data1_dict, data2_dict):
        list1_html = []
        list2_html = []

        is_same = True

        for key in sorted_keys:
            list1 = data1_dict.get(key, None)
            list2 = data2_dict.get(key, None)

            # for readable
            if list1:
                list1 = schemaReader.get_row2(list1)
                list1 = map(lambda x: x.replace("&", "&amp;"), list1)
                list1 = map(lambda x: x.replace(">", "&gt;"), list1)
                list1 = map(lambda x: x.replace("<", "&lt;"), list1)
                list1 = map(lambda x: x.replace("None", ""), list1)
            if list2:
                list2 = schemaReader.get_row2(list2)
                list2 = map(lambda x: x.replace("&", "&amp;"), list2)
                list2 = map(lambda x: x.replace(">", "&gt;"), list2)
                list2 = map(lambda x: x.replace("<", "&lt;"), list2)
                list2 = map(lambda x: x.replace("None", ""), list2)

            if list1 == None:
                list2_str = "<tr class='green'><td>" + "</td><td>".join(list2) + "</td></tr>"
                list2_html.append(list2_str)
                is_same = False
            elif list2 == None:
                list1_str = "<tr class='red'><td>" + "</td><td>".join(list1) + "</td></tr>"
                list1_html.append(list1_str)
                is_same = False
            else:
                list1_len = len(list1)
                temp_list1 = []
                temp_list2 = []
                for i in range(list1_len):
                    if list1[i] != list2[i]:
                        temp_list1.append("<td class='yellow'>" + list1[i] + "</td>")
                        temp_list2.append("<td class='yellow'>" + list2[i] + "</td>")
                        is_same = False
                    else:
                        temp_list1.append("<td>" + list1[i] + "</td>")
                        temp_list2.append("<td>" + list2[i] + "</td>")

                list1_str = "<tr>" + "".join(temp_list1) + "</tr>"
                list1_html.append(list1_str)

                list2_str = "<tr>" + "".join(temp_list2) + "</tr>"
                list2_html.append(list2_str)

        return list1_html, list2_html, is_same

if __name__ == "__main__":
    resultCheck = WebPageBuilder(sys.argv[1], sys.argv[2], sys.argv[3], sys.argv[4])
    resultCheck.check()
