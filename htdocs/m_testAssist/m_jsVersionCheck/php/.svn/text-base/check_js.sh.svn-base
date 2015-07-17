#!/bin/sh
#$1 svn old: $2 svn new
username="xxxx"
password="xxxxxx"
jsPostfix=(".js" ".js.template" ".css" ".css.template")
filters=(".svn")
needChangeFiles=
function checkout() {
    svn=${1/@/ -r }  
    svn co $svn --username $username --password $password $2

}

function check_js_css()
{
    line=$1;
    for i in $jsPostfix;do
        temp=${line##*/}
        echo ${temp} | grep $i > /dev/null
        if [ "$?" = "0" ];then
            echo $temp;
        fi   
    done;
}

function diff_js_css()
{
    #svn diff --old=$1 --new=$2 --username $username --password $password --summarize > diff
    diff=$1
    #echo $diff
    less $diff | grep "  http" > file
    less file | awk '{print $2}' > diffFiles
    cat diffFiles | while read line
    do  
        check_js_css $line
    done
}

function not_filter()
{   
    temp=$1;
    for s in $filters;do
        echo "$temp" | grep $s > /dev/null
        if [ "$?" = "0" ];then
            return 1;
        fi
    done
    return 0;
}

#find contentFile list which contains x.js
function getTargetFile()
{
    dir=$1
    file=$2
    j=0
    find $dir -type f | xargs grep $file -n > tmpref
    sed 's///g' tmpref > referer 
    sed 's/ /,/g' referer > tmpref
    cat tmpref | while read line
    do 
        echo $line
    done
}

function getVersion()
{
   line=$1
   echo $line | awk -F '?' '{print $2}' | awk -F '["=]' '{print $2}'
}

function getLineNo()
{
   line=$1
   echo $line | awk -F ':' '{print $2}'
}

function getFile()
{
   line=$1
   echo $line | awk -F ':' '{print $1}'
}

function printArray()
{
    ar2=$1
    for o in ${ar2[@]};do
        tv=$(getVersion $o)
        tl=$(getLineNo $o)
        tf=$(getFile $o)
        tf=$(echo ${tf#*/})
        tf=$(echo ${tf#*/})
        ans=$ans"\t\tArray(\"${tf#*/}\",\"$tl\",\"$tv\"),\n"
    done
    echo $ans
}

function compare()
{
    ar1=$1
    ar2=$2
    #echo "ar2"${#ar2[@]}
    tag=1
    for s in ${ar1[@]};do
       v=$(getVersion $s)
       l=$(getLineNo $s)
       f=$(getFile $s)
       f=${f#*/}
       f=${f#*/}
       f=${f#*/}
       if [ ! $v ]
       then
           tag=0
           echo -e "\t\tArray(\"$f\",\"$l\",\"$v\")," >> $phpFile
       else
           for o in ${ar2[@]};do
               ov=$(getVersion $o)
               if [ "$v" = "$ov" ]
               then
                   tag=0
                   echo -e "\t\tArray(\"${f#*/}\",\"$l\",\"$v\")," >> $phpFile
                   break
               fi
           done
       fi
    done
    return $tag
}

function genResult()
{
    old=$2
    new=$3
    fileList=$1
    fileList=$(echo ${fileList#*@})
    phpFile="result.php"
    echo "<?php" > $phpFile
    echo "\$new_array = Array(" >> $phpFile
    for f in $fileList;do
        f=$(echo $f | tr -d "\r")
        fileName=${f##*/}
        echo -e "\t\""$fileName"\" => Array(" >> $phpFile
        #all files that referer js in new version
       
        newFiles=$(getTargetFile $new $f)
        #all files that referer js in old version
        oldFiles=$(getTargetFile $old $f)
        compare "${newFiles[*]}" "${oldFiles[*]}"
        if [ "$?" = "0" ]
        then
            js_file="\t\""$fileName"\" => Array(\n"
            temp=$(printArray "${oldFiles[*]}")
            total=$total$js_file$temp"),"
        fi
        echo  ")," >> $phpFile
    done 
    echo ");" >> $phpFile
    echo "\$old_array = Array(" >> $phpFile
    if $ans 
    then
        echo -e $total >> $phpFile
    fi
    echo ");" >> $phpFile
    echo "?>" >> $phpFile
}

#checkout $1 old
#checkout $2 new
#diff_js_css $1



result=$(diff_js_css $3)
#echo ${result[@]}
#nimei=$(getTargetFile $new1 "adStats.js")
#echo ${nimei[@]}
genResult "@"${result[*]} $1 $2


