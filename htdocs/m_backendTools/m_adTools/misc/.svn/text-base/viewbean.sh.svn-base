#!/bin/bash

tool_name="vim"
path=`echo $1 |sed -e 's/\./\//g'`
curDir=`pwd`
serviceDir=`echo $curDir|sed -e 's#/java/.*#/java#'`
serviceDir2=`echo $curDir|sed -e 's#/src/.*#/src#'`

if [ $curDir != $serviceDir -a -e $serviceDir/$path.java ]; then
  cmd="$tool_name $serviceDir/$path.java"

elif [ $curDir != $serviceDir2 -a -e $serviceDir2/$path.java ]; then
  cmd="$tool_name $serviceDir2/$path.java"

elif [ -e $path ];then
  cmd="$tool_name $path"

elif [ -e $path.java ];then
  cmd="$tool_name $path.java"

elif [ -e src/$path ]; then
  cmd="$tool_name src/$path"

elif [ -e src/$path.java ]; then
  cmd="$tool_name src/$path.java"

elif [ -e src/java/$path ]; then
  cmd="$tool_name src/java/$path"

elif [ -e src/java/$path.java ]; then
  cmd="$tool_name src/java/$path.java"

else
  echo "Failed to $tool_name file"
  exit 1;
fi

echo $cmd
$cmd
