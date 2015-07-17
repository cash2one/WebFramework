#!/bin/bash

date_str=`date +%Y-%m-%d`
output_file=./raw_data/address-$date_str.txt

# 抓取员工地址页面, 结果保存日期文件output_file中
src_file=http://weekly.corp.youdao.com/address/
wget $src_file --http-user=$1 --http-passwd=$2 -O $output_file

if [ $? -ne 0 ];then
    rm $output_file
fi

./bin/infoExtract.py
