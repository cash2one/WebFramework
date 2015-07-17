#!/bin/bash

rm -rf ../html_data/*.html

for file in `ls ./table_data`
do
    ./TestDriver.py output_html ./table_data/$file ../html_data/$file.html
done
