#!/bin/bash

desc=$1
if [[ -z $desc ]]; then
    echo "Usage: $0 desc"
    exit 1
fi
timestamp=`date +%s`

filename=`ls ./table_data/|grep .$desc`
if [[ ! -z $filename ]]; then
    file="./table_data/$filename"
else
    echo "[Error]: 您还没有:$desc"
    exit 1
fi

./TestDriver.py delete
./TestDriver.py write $file

echo "文件（$file)内容已导到数据库"
