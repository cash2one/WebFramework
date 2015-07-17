#!/usr/bin/python
#encoding: utf-8

user_list = (
    ("zhangpei", "张培"),
    ("shixx", "史晓星"),
    ("guojing", "郭静"),
    ("lisn", "李斯宁"),
    ("lirui", "李瑞"),
    ("kuyan", "库燕"),
    ("cuijh", "崔军辉"),
    ("wuhao", "吴昊"),
    ("zhudy", "朱丹阳"),
    ("ruanhh", "阮会会"),
    ("youjy", "尤家莹"),
    ("yufang", "余芳"),
    ("lihy", "李红艳"),
    ("jiangjg", "蒋金谷"),
    ("luqy", "卢秋英"),
    ("liushen", "刘申"),
    ("liuzhe", "刘哲"),
)

temp_list = []
for ldap, name in dict(user_list).items():
    temp_list.append("{{http://weekly.corp.youdao.com/avatar/%s.jpg}} <<BR>> [[http://weekly.corp.youdao.com/address/information.php?UserName=%s|%s]]" % (ldap, ldap, name))

count_in_row = 9
for i in range(0, len(temp_list), count_in_row):
    print "||" + "||".join(temp_list[i:i + count_in_row]) + "||"
