<html>
<head>
<meta charset="utf-8">

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>
<script src="./js/DataTables/media/js/jquery.dataTables.min.js"></script>

</head>

<body>
<h3>Kafka前端工具</h3>
<br/>

<div>
    <p>list topics</p>
    <br/>
    <form action="" method="post">
        zk地址：(如：tb083:2181)<input type="text" name="zkAddr" id="zkAddr" />
        <input type="submit" name="submit" value="list topics"/>
    </form>
    <?php
        function list_topics($zkAddr){
            $tmpFile=time();
            //echo $tmpFile;
            $cmd = "cd ./kafka/kafka-0.8.0-ead-release;bin/kafka-list-topic.sh --zookeeper $zkAddr >>../../data/$tmpFile";
            $topics = system($cmd);

            $fd = fopen("./data/$tmpFile","r");

            echo "<table border='1'>
                      <tr>
                          <th><label id='zk'>$zkAddr</label></th>
                          <th>topic</th>
                          <th>partition</th>
                          <th>leader</th>
                          <th>last offset</th>
                          <th>message</th>
                      </tr>";
            $line = fgets($fd);
            $rowNum = 1;
            while ($line != ""){
                //echo $line;
                $tp = strpos ($line,"topic:");
                $pp = strpos ($line,"partition:");
                $lp = strpos ($line,"leader:");
                $rp = strpos ($line,"replicas:");
                $topic=trim(substr($line,$tp+6,$pp-6));
                $partition=trim(substr($line,$pp+10,2));
                $leader=trim(substr($line,$lp+7,2));
                echo "<tr>
                          <td>
                              <input type='checkbox' id='$topic|$partition|$leader' />
                          </td>
                          <td>$topic</td>
                          <td>$partition</td>
                          <td>$leader</td>
                          <td><button class='btn' id='getLastOffset|$topic|$partition|$leader'>Get Last Offset</button><br/>
                              <label id='OffsetLabel-$topic-$partition-$leader' />
                          </td>
                          <td><input type='text' id='setOffset-$topic-$partition-$leader' />
                              <button class='btn' id='getOffsetMsg|$topic|$partition|$leader'>Get Msg</button><br/>
                              <label id='msg-$topic-$partition-$leader' />
                          </td>
                      </tr>";

                $line = fgets($fd);
            }
            fclose($fd);
            $delCmd = "cd ./data;rm $tmpFile";
            system($delCmd);

            echo "</table>";
        }

        if(isset($_POST["zkAddr"])&&$_POST["zkAddr"]){
            $zkAddr = $_POST['zkAddr'];
            list_topics($zkAddr);
        }
    ?>
</div>
<hr/>
<script src="./js/index.php.js">
</script>
</body>
</html>
