#!/bin/bash
# coding: utf-8
# filename: gcmon.sh
##################################
# @author liujia
# @date 2013-07-26
##################################
# for nagios

usage()
{
echo "please use like: $0 /logpath/xx.log key -c value key key key ..."
}

log=`echo $1|awk -F. '{print $NF}'`

if [ $log == "log" ];then
	log=$1
	shift
else
	usage
	exit 1
fi

while [ $# -gt 0 ]
do
	case $1 in
	-c)
		usage
		exit 1
	esac
	
	case $2 in
	-c)
		wkey=(${wkey[*]} $1)
		value=(${value[*]} $3)
		shift
		shift
		shift
	;;
	*)
		key=(${key[*]} $1)
		shift
	esac
done

#echo $log
#echo ${wkey[*]}
#echo ${#wkey[*]}
#echo ${value[*]}
#echo ${key[*]}

sumkey=`echo ${#wkey[*]}+${#key[*]}|bc`
path=`dirname $0`
result=(`cd $path && /usr/bin/python gcMonitor.py $log ${wkey[*]} ${key[*]}`)
if [ ! $sumkey -eq ${#result[*]} ];then
	echo "key_num != result_num"
	exit 1
fi

for (( i=0;i<${#wkey[*]};i++))
do
	if [ ! ${result[$i]} = 'NaN' ];then
		if [ `awk 'BEGIN{ print ('"${result[$i]}"' > '"${value[$i]}"') }'` -eq 1 ];then
			bad="$bad ${wkey[$i]}"
			badvalue="$badvalue ${wkey[$i]}=${result[$i]};;;;"
		else
			ok="$ok ${wkey[$i]}"
			okvalue="$okvalue ${wkey[$i]}=${result[$i]};;;;"
		fi
	else
		ok="$ok ${wkey[$i]}"
		okvalue="$okvalue ${wkey[$i]}=${result[$i]};;;;"
	fi
done

for (( i=0;i<${#key[*]};i++))
do
	t=`echo $i+${#wkey[*]}|bc`
	ok="$ok ${key[$i]}"
	okvalue="$okvalue ${key[$i]}=${result[$t]};;;;"
done

if [[ $bad = '' ]];then
	echo "all is ok|$okvalue"
	exit 0
else
	echo "$bad is bad|$badvalue$okvalue"
	exit 1
fi
