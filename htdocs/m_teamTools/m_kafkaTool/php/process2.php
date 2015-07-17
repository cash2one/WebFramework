<?php

    $zkAddr = $_POST["zkAddr"];
    $topic = $_POST["topic"];
    $part = $_POST["partition"];
    $leader = $_POST["leader"];
    $cmd = $_POST["cmd"];

//    echo ("in php,topic is "+$topic);
    
    if($cmd == "getOffsetMsg"){
        $offset = $_POST["offset"];
        $msg = getMessageByOffset($zkAddr,$topic,$part,$offset);
        echo $msg;
        
    }else if($cmd == "getLastOffset"){
        $lastOffset = getLastOffset($zkAddr,$topic,$part); 
        echo $lastOffset;
    }

    function getCmdStrByTopicAndPartition($zkAddr,$topic,$part){
        //获取topic信息
        $cmdListTopic = "cd ../kafka/zookeeper-3.3.3/bin;./zkCli.sh -server $zkAddr get /brokers/topics/$topic | grep partitions";
        $topicInfos = system($cmdListTopic);
        $topicInfos2 = json_decode($topicInfos);
       // print("1:== ".$topicInfos2)
        $partitions = $topicInfos2->{"partitions"};

     //   $partitions2 = json_decode($partitions);
        $id = $partitions->{"$part"};
        $id = $id[0];
        $cmd = "cd ../kafka/zookeeper-3.3.3/bin;./zkCli.sh -server $zkAddr get /brokers/ids/$id| grep port";
       // echo $cmd;
        $info=system($cmd);
        $info2 = json_decode($info);

    //    $idInfo = $this->getIdInfoByIdAndZk($zkAddr,$id);
        $host = $info2->{"host"};
        $port = $info2->{"port"};
        $cmd2 = "cd ../kafka/kafka-tool/;bin/kafka-tool --host $host --port $port --topic $topic --partition $part ";
  //      echo "$cmd2";
       // $lastOffset = system($cmd2);
        return $cmd2;
    }

    function getLastOffset($zkAddr,$topic,$part){
        $cmd = getCmdStrByTopicAndPartition($zkAddr,$topic,$part)."get_last_offset";
        $lastOffset = system($cmd);
       // echo "1==> ".$cmd."<hr/>";
        return $lastOffset;
    }

    function getMessageByOffset($zkAddr,$topic,$part,$offset){
        $cmd = getCmdStrByTopicAndPartition($zkAddr,$topic,$part)."get_message_by_offset $offset";
       // echo "2==>".$cmd."<hr/>";
//        $msg = system($cmd);
        return $cmd;
       // return $msg;
    }
?>
