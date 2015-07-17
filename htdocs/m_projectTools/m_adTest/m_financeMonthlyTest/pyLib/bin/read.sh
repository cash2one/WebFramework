#!/bin/bash

desc=$1
if [[ -z $desc ]]; then
    echo "Usage: $0 desc"
    exit 1
fi

timestamp=`date +%s`

#filename=`ls ./table_data/|pgrep ".$desc$"`
#if [[ -z $filename ]]; then
#    filename="case_$timestamp.$desc"
#else
#    read -p "filename: '$filename' exists, continue(y/n)? " answer
#    if [[ "$answer" != "y" ]]; then
#        echo "Exit with doing nothing"
#        exit 1
#    fi
#fi
#file="./table_data/$filename"

./TestDriver.py read $file
echo "Data saved into file: $file"

html_file="../html_data/$filename.html"
./TestDriver.py output_html $file $html_file
echo "Html page is Saved as $html_file"
