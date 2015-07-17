#!/bin/bash

time_str=`date +%s`
archive_dir="../html_data/$time_str.archive"
mkdir $archive_dir
mv ../html_data/*.html $archive_dir
