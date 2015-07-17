#!/bin/bash

### ==================== 输入条件设定区
access_log_path_list=(
    "nc072:/disk4/eadop/click-resin/logs"
    "nc092:/disk4/eadop/click-resin/logs"
    "ws129:/disk4/eadop/click-resin/logs"
    "ws130:/disk4/eadop/click-resin/logs"
);

log_date_list=(
    "2013-11-10"
    "2013-11-11"
);

output_dir=../temp

remove_exists=false

### ==================== Main Logic
for log_path in ${access_log_path_list[@]}
do
    hostname=${log_path%:*}
    for log_date in ${log_date_list[@]}
    do
        target_file=$output_dir/access.log.$log_date.$hostname
        if [ -e $target_file -a $remove_exists = true ]; then
            echo "File($target_file) already exists, skip it..." 
            continue 
        fi

        scp_cmd="scp $log_path/access.log.$log_date $target_file"
        echo $scp_cmd
        $scp_cmd

        if [ -e $target_file ]; then
            chmod 777 $target_file
        fi
        
    done
done
