#!/bin/bash

date_str=`date +"%Y-%m-%d" -d'1 days ago'`

tool_dir=/disk2/qatest/svn_code/qa/WebFramework/htdocs/m_miniTools/m_clickKParser/click_code
log_file=/disk2/qatest/svn_code/qa/WebFramework/htdocs/m_teamTools/m_adTools/m_clickReq/bin/temp/click.log
output_dir=/disk2/qatest/svn_code/qa/WebFramework/htdocs/m_teamTools/m_adTools/m_clickReq/click_data/$date_str

ssh nc092 "head -100000 /disk4/eadop/click-resin/logs/access.log.$date_str" > temp/click.log

mkdir $output_dir

cd $tool_dir
./classify.sh $log_file $output_dir
