#!/bin/bash

# run by crontab in zhangpei's use
# temp_data_dir & ../data must be 777
# need to executed by users who have the permission for antifraud online machines

date=`date -d last-day +%Y-%m-%d`
date2=`date -d last-day +%Y%m%d`

#date="2013-07-14"
#date2="20130714"

if [ -e ../data/$date2.log.html ];
then
    echo "Error: file ../data/$date2.log.html Already exists!"
    # exit -1
fi

# -------------------------------------------
# deal with click log files
# -------------------------------------------
for host in nc072 nc092
do
    scp $host:/disk4/eadop/click-resin/logs/log.$date ./temp_data_dir/log.$date.$host
done

for host in ws129 ws130
do
    scp $host:/disk5/eadop/click-resin/logs/log.$date ./temp_data_dir/log.$date.$host
done

./clickLogParser.py $date2 temp_data_dir/log.$date.ws129 temp_data_dir/log.$date.nc072 temp_data_dir/log.$date.nc092 temp_data_dir/log.$date.ws130

rm temp_data_dir/*
chmod 777 ../data/*.log.html

# -------------------------------------------
# deal with click accessLog files
# -------------------------------------------

for host in nc072 nc092
do 
    scp $host:/disk4/eadop/click-resin/logs/access.log.$date ./temp_data_dir/access.log.$date.$host
done

for host in ws129 ws130
do 
    scp $host:/disk4/eadop/click-resin/logs/access.log.$date ./temp_data_dir/access.log.$date.$host
done

./clickAccessLogParser.py $date2 temp_data_dir/access.log.$date.ws129 temp_data_dir/access.log.$date.nc072 temp_data_dir/access.log.$date.nc092 temp_data_dir/access.log.$date.ws130

rm temp_data_dir/*
chmod 777 ../data/*.log.html
#
# -------------------------------------------
# deal with anti log files
# -------------------------------------------
for host in hs026 hs027 nc096
do
    scp $host:/disk2/eadop/antifrauder/logs/log.$date ./temp_data_dir/log.$date.$host
done

./antiLogParser.py $date2 temp_data_dir/log.$date.hs026 temp_data_dir/log.$date.hs027 temp_data_dir/log.$date.nc096

rm temp_data_dir/*
chmod 777 ../data/*.log.html
