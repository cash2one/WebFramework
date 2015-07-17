#!/usr/bin/python
#encoding: utf-8

import re
from Schema import *
from DBConnection import *

class TableStyle:
    horizontal = "horizontal"
    vertical  = "vertical"

class Table:
    def __init__(self, schemaClass, main_key, table_style = TableStyle.vertical):
        self.schemaClass = schemaClass
        self.main_key = main_key
        self.row_list = []
        self.table_style = table_style

        self.head_name_dict = {}
        self._my_init()

    def _my_init(self):
        schema_file = "./table_conf/%s.schema" % self.schemaClass.table_name
        for line in open(schema_file):
            line = line.strip()
            if not line or line[0] == "#": continue
            key, val = re.split("\s*:\s*", line)
            self.head_name_dict[key] = val

    def _get_readable_value(self, field):
        field_dict = DBConnection.table_value_readable_dict.get(self.schemaClass.table_name, None)
        if field.value == None:
            return ""

        if field_dict == None:
            return field.value
        value_dict = field_dict.get(field.name, None)
        if value_dict == None:
            return field.value

        if value_dict.has_key("*"):
            func_name = value_dict["*"]
            return func_name(field.value)

        return value_dict.get(field.value, field.value)

    def add_row(self, fields):
        row = []
        for idx, field in enumerate(fields):
            key  = self.schemaClass.schema_fields[idx].name
            type = self.schemaClass.schema_fields[idx].type
            value = field
            row.append(Field(key, type, value))
        self.row_list.append(row) 

    def get_insert_value_rows(self):
        ret_rows = []
        for row in self.row_list:
            temp_list = [] 
            for field in row:
                key  = field.name
                type = field.type
                value = field.value
                if value == None:
                    value = "null"
                elif type in (Type.varchar, Type.date, Type.datetime, Type.timestamp):
                    value = '"%s"' % value
                else:
                    value = str(value)
                temp_list.append(value)

            ret_rows.append(",".join(temp_list))
        return ret_rows

    def get_html_str(self, table_class = ""):
        lines = []
        lines.append("<table border='1' style='font-size:0.7em; font-weight:normal' class='%s %s %s'>" % (self.schemaClass.table_name, table_class, self.table_style))
        # table head
        lines.append("<thead>")

        # 以水平方式显示表格
        if self.table_style == TableStyle.vertical:
            lines.append("<tr><th colspan='%s'>%s(%s)</th></tr>" % (len(self.schemaClass.schema_fields), self.schemaClass.table_desc, self.schemaClass.table_name))
            lines.append("<tr>")
            key_list = map(lambda field: field.name, self.schemaClass.schema_fields)
            lines.append("".join(map(lambda x: "<td>%s</td>" % self.head_name_dict.get(x, x), key_list)))
            lines.append("</tr>")
            lines.append("</thead>")
            # table body
            for fields in self.row_list:
                td_list = map(lambda field: "<td>%s</td>" % self._get_readable_value(field), fields) 
                lines.append("<tr>" + "".join(td_list) + "</tr>")

        # 以垂直方式显示表格
        elif self.table_style == TableStyle.horizontal:
            # table head
            lines.append("<tr><th colspan='%s'>%s<br>(%s)</th></tr>" % (len(self.row_list) + 1, self.schemaClass.table_desc, self.schemaClass.table_name))
            lines.append("</thead>")
            # table body
            key_list = map(lambda field: field.name, self.schemaClass.schema_fields)
            temp_list = map(lambda x: "<tr><td>%s</td>" % self.head_name_dict.get(x, x), key_list)
            for fields in self.row_list:
                for idx, field in enumerate(fields):
                    temp_list[idx] += "<td>%s</td>" % self._get_readable_value(field)
            for tr_str in temp_list:
                lines.append(tr_str + "</tr>")

        lines.append("</table>")
        lines.append("<br>")
        return "\n".join(lines)
