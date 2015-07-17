#!/usr/bin/python

import os

dirs = os.listdir("./result_data")
for dir in dirs:
    if dir[0] == ".":
        continue

    dir = "./result_data/%s" % dir
    if not os.path.isdir(dir):
        continue

    filenames = os.listdir(dir)
    for filename in filenames:
        file = "%s/%s" % (dir, filename)

        temp_dict = {}
        for line in open(file).readlines():
            line = line.strip()
            key, value = line.split(":", 1)
            if not temp_dict.has_key(key):
                temp_dict[key] = int(value)
                continue

            if int(value) > temp_dict.get(key, 0):
                temp_dict[key] = int(value)

        lines = []
        for key, val in temp_dict.items():
            lines.append("%s:%s\n" % (key, val))
        
        lines.sort()
        open(file, "w").writelines(lines)
