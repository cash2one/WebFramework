#!/usr/bin/python
#encoding:utf-8

import re
import glob

def getAddressInfo():
    raw_data_dir = "./raw_data"
    invalidUserList = ("Note OpenAPI", "atwork", "employeetest1", "pingguadmin", "wangyihui", "youdaoaudit", "youdaokeya")
    files = glob.glob(raw_data_dir + "/address-*.txt")
    raw_file = files.pop()

    ret_dict = {}
    lines = open(raw_file).read().splitlines()
    lines_len = len(lines)
    for index in range(lines_len):
        line = lines[index] 
        line = line.strip()
        if not line:
            continue
        
        if line.startswith("<TR "):
            index += 1
            line = lines[index]
            reObj = re.search("information\.php\?UserName=(.+)\">(.*)</a>", line)
            if reObj:
                ldap = reObj.group(1).strip()
                name = reObj.group(2).strip()
                if name not in invalidUserList:
                    ret_dict[ldap] = name

    return ret_dict

def getBugzillaInfo():
    ret_dict = {}

    # from url by manual: view-source:https://dev.corp.youdao.com/bugs/enter_bug.cgi?product=EAD
    raw_file = "conf/bugzilla-2014-02-08.txt"

    temp_lines = []
    found_mark_line = False
    lines = open(raw_file).read().splitlines()
    for line in lines:
        line = line.strip()
        if not line:
            continue

        if '<select name="cc" multiple="multiple" size="5"' in line:
            found_mark_line = True
        elif found_mark_line != True:
            continue
        elif "</select>" in line:
            break

        temp_lines.append(line)

    content_str = "".join(temp_lines)
    # get pingying name AND ldap
    for name, ldap in  re.findall("<option value=\"[^\"]*\">([^>]*?)[ >]&lt;([\w-]+)&#64;rd.netease.com&gt;</option>", content_str):
        ret_dict[ldap] = name.lower().replace("&#64;rd.netease.com", "").replace("-", "").replace("_", "")

    return ret_dict

def build_output_file(raw_data_dir, output_file):
    output_lines = []
    group_ldap_list = open("conf/group.txt").read().splitlines()

    dict1 = getAddressInfo()
    dict2 = getBugzillaInfo()

    line_format1 = "\t{\"ldap\": \"%s\", \"name\": \"%s\", \"pname\": \"%s\", \"to\": false, \"cc\": false, \"type\": \"user\"},\n"
    line_format2 = "\t{\"ldap\": \"%s\", \"name\": \"%s\", \"pname\": \"%s\", \"to\": false, \"cc\": false, \"type\": \"group\"},\n"
    
    for ldap, name in dict1.items():
        pname = dict2.get(ldap, "")
        plist = list(pname)
        plist.sort()
        pname = "".join(plist)
        output_lines.append(line_format1 % (ldap, name, pname))

    for ldap, name in dict2.items():
        if ldap in group_ldap_list:
            output_lines.append(line_format2 % (ldap, name, "NULL"))

    output_str = "[\n" + "".join(output_lines)[:-2] + "\n]"
    open(output_file, "w").write(output_str)

if __name__ == "__main__":
    build_output_file("./raw_data", "./output_data/output.json")
