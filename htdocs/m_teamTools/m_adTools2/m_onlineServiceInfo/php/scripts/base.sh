#!/bin/bash

function get_deploy_dir {
    proc_keyword=$1
    pid_list=$(pgrep -f $proc_keyword)
    pid_files=$(ls /disk*/*/*/*.pid)

    for file in $pid_files
    do
        base_dir=`dirname $file`
        if [ -L $base_dir ];then
            continue
        fi

        pid_in_file=`cat $file`
        for pid in $pid_list
        do
            if [ $pid -eq $pid_in_file ]; then
                echo `dirname $file`
            fi
        done
    done
}

function get_resin_deploy_dir {
    proc_keyword=resin
    xml_filename=$1
    pid_list=$(pgrep -f $proc_keyword)
    pid_files=$(ls /disk*/*/*/*.pid)

    for file in $pid_files
    do
        base_dir=`dirname $file`
        if [ -L $base_dir ];then
            continue
        fi

        pid_in_file=`cat $file`
        for pid in $pid_list
        do
            if [ $pid -eq $pid_in_file ]; then
                dirname=`dirname $file`
                ead_servlet_xml_file=$dirname/webapps/imp/WEB-INF/ead-servlet.xml
                grep  --quiet webapps/imp/WEB-INF/$xml_filename  $ead_servlet_xml_file > /dev/null 2>&1
                if [ $? -eq 0 ]; then
                    echo $dirname
                fi
            fi
        done
    done
}

function get_click_resin_deploy_dir {
    proc_keyword=resin
    pid_list=$(pgrep -f $proc_keyword)
    pid_files=$(ls /disk*/*/*/*.pid)

    for file in $pid_files
    do
        base_dir=`dirname $file`
        if [ -L $base_dir ];then
            continue
        fi

        pid_in_file=`cat $file`
        for pid in $pid_list
        do
            if [ $pid -eq $pid_in_file ]; then
                dirname=`dirname $file`
                ead_servlet_xml_file=$dirname/webapps/clk/WEB-INF/clk-servlet.xml
                grep  --quiet clickController $ead_servlet_xml_file > /dev/null 2>&1
                if [ $? -eq 0 ]; then
                    echo $dirname
                fi
            fi
        done
    done
}
