#!/usr/bin/python
#encoding: utf-8

import xml.dom.minidom
import re
import os
import sys

def get_xml_normalize_content(xml_file):
    """remove spaces between tags"""
    temp_lines = []
    lines = open(xml_file).read().splitlines()
    for line in lines:
        line = line.strip()
        if not line: continue
        temp_lines.append(line + " ") #行后加空格，确保不相关的字段没有连到一起

    temp_content = "".join(temp_lines)
    temp_content = re.sub("<!--.*?-->", "", temp_content) #remove comment lines
    temp_content = re.sub(">\s+<", "><", temp_content) #remove spaces between tags

    return temp_content

def get_xml_root_node_list(root_dir, xml_file_list):
    root_node_list = []
    for xml_file in xml_file_list:
        xml_file_path = os.path.join(root_dir, xml_file)
        xml_file_content = get_xml_normalize_content(xml_file_path)
        dom = xml.dom.minidom.parseString(xml_file_content)
        root = dom.documentElement
        root_node_list.append((root, xml_file))
    return root_node_list

if __name__ == "__main__":
    #print get_normal_xml_content("./m2.0/war/WEB-INF/applicationContext.xml")
    print get_xml_root_node_list([
        "./m2.0/war/WEB-INF/applicationContext.xml",
        "./m2.0/war/WEB-INF/dataAccessContext-jta.xml",
        "./m2.0/war/WEB-INF/eadfin-servlet.xml"
        ])
