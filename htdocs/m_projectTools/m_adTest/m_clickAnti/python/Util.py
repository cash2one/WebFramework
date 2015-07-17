#!/usr/bin/python

import os
import urllib
from Config import *
import time

def requestClickService(url):
    # refer web page: http://www.blogjava.net/ashutc/archive/2011/03/21/346695.html
    urllib.urlopen(url).read()


def set_flag(case_id):
    open(REQUEST_FLAG_FILE, "w").write(case_id)


def check_and_wait_result(timeout_secs = 10):
    for i in range(timeout_secs):
        if os.path.exists(RESPONSE_FLAG_FILE):
            return True
        time.sleep(1)
    return False


def read_result():
    result_str = open(RESPONSE_FLAG_FILE).read()
    return result_str


def clear_result_file():
    os.unlink(RESPONSE_FLAG_FILE)


def result_check(
 

if __name__ == "__main__":
    pass
    url = "http://nc107x.corp.youdao.com:18382/clk/request.s?d=http%3A%2F%2Fwww.smjxxm.com&k=rFl%2BgwuyesLZjWG7IJynNYkwNyc22h8Yo3l9X7Y6nUqXg3OJr2LuR98QTZv8DV0Sn4JDfHMvOWq%2FrJvFpXfTx8WOnOvoSwuYfVg264vEmTmzj%2B%2FgcOGcmO%2Bi1adXfhfLhcoUcCQM5ddO5gK10gOt3B7ANPGdk269rF%2F47v1ZcnBSyE3ZKQIbKWKvpfrNbBx2k0Vik7p9M9eCQBkEZx%2BtxK4oyAg%2FU9OWsNjslmKRkE0TaTHIKKktSwf%2FYx3c4OPTTh8Th1io93p83PcybB4l4VPaBvACTZYGPbMhFqrJzyFcGMBQx48yFxIqxhaXIC%2BX7vzwOnwk8CC1oR6KcYupqsQh0%2F8Xls7AQlKhY1p5zCDXxo%2BoRxcJpjjAgKuViCqv18aPqEcXCaY4wICrlYgqr9fGj6hHFwmmOMCAq5WIKq%2BEtL%2BQfPtpC51%2BPP8Vl%2B1sCpfwJIu9JySE02y3LC1jrcM707Ho9Z0n8vV3CB%2F3PMk%3D&s=1"
    requestClickService(url)
