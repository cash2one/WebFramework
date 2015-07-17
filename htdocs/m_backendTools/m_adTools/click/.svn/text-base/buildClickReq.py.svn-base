#!/usr/bin/python

import os

input_param_dict = {
    "log_files": [
        "../temp/access.log.2013-11-01",
    ],
    "max_lines": -1,                       # -1 means all lines in the input log files
    "output_file": "../temp/ddd",
    "save_type": 0,                        # 0:truncate file, 1:append to file
}


### ========== function definitions area
def check_input():
    if len(input_param_dict["log_files"]) == 0:
        raise Exception("log files EMPTY")

    for log_file in input_param_dict["log_files"]:
        if not os.path.exists(log_file):
            raise Exception("log file(%s) NOT exists" % log_file)

    if input_param_dict["save_type"] not in (0, 1):
        raise Exception("save type can ONLY be 0 or 1")

    # just for permission test
    open(input_param_dict["output_file"], "w")

def build_click_file():
    handle = None
    save_type = input_param_dict["save_type"]
    if save_type == 0:
        handle = open(input_param_dict["output_file"], "w")
    else:
        handle = open(input_param_dict["output_file"], "a")

    max_read_line_cnt = input_param_dict["max_lines"]
    for log_file in input_param_dict["log_files"]:
        if max_read_line_cnt == 0:
            break

        for line in open(log_file):
            start_idx = line.find("GET /clk/")
            if start_idx == -1:
                continue
            end_idx = line.find(" HTTP/1.0", start_idx)
            if end_idx == -1:
                continue

            handle.write(line[start_idx + 4 : end_idx] + "\n")
            max_read_line_cnt -= 1

            if max_read_line_cnt == 0:
                break

    handle.close()


### ========== main logic
if __name__ == "__main__":
    check_input()
    build_click_file()
