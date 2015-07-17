#!/bin/sh
prodName="`sed -n '1p' prodInfo.txt`"
version="`sed -n '2p' prodInfo.txt`"
ts="`sed -n '3p' prodInfo.txt`"

#fileName=`date --date "-1 day -10 minute " "+%Y%m%d%H%M.txt"`
#status=`cat status.txt`
#echo $fileName $status $prodName $version $ts
#if [ $status == "running" ];then
#  ssh nb014 "scp /disk1/lihy/$fileName tb037:/disk2/qatest/svn_code/qa/WebFramework/htdocs/m_teamTools/m_adTest2013Q3/m_onlineRegressionMonitor/mock"
#  cd /disk2/qatest/svn_code/qa/WebFramework/htdocs/m_teamTools/m_adTest2013Q3/m_onlineRegressionMonitor/php;./php getAndSaveData.php ../mock/$fileName $prodName $version "$ts"
#  echo "done."
#fi

savedFileName=""
newFileName=""
#echo "status is "$status
while [ 1=1 ];
# [[ $status == "running" ]]
do
  prodName="`sed -n '1p' prodInfo.txt`"
  version="`sed -n '2p' prodInfo.txt`"
  ts="`sed -n '3p' prodInfo.txt`"
  status=`cat status.txt`
  echo "statu is "$status
  if [[ $status == "running" ]];then
    newFileName=`tail -1 run.log`
    #  echo "newFileName is "+$newFileName >> getData.log
    if [[ $newFileName != $savedFileName ]];then
      cd php;/disk2/qatest/lamp/php/bin/php getAndSaveData.php $newFileName $prodName $version $ts;cd ..
      echo "save "$newFileName >> getData.log
      savedFileName="$newFileName"
    fi
  fi
  sleep 5
done
