<div>
    <select>
        <option>选择用户</option>
    <?php
        $lines = file("./data/members.txt");
        $userCount = count($lines);
        foreach ($lines as $line) {
            $line = trim($line);
            list($name, $mail) = explode(":", $line); 
            echo "<option>$name</option>\n";
        }
    ?>
    </select>
    <a href="./php/start_votes.php" id="create_votes">发起投票</a>
</div>

<div id="vote_list">
<br>
<table border='1' style="width:100%" id="list">
    <tr>
        <th>发起时间</th>
        <th>主题</th>
        <th>发起人</th>
        <th>操作</th>
    </tr>
<?php

    date_default_timezone_set("PRC");

    try{
        $mongo = new MongoClient('mongodb://nb403:27127/admin:admin');
        $db = $mongo->youdaoSports;
        $collection = $db->votes;
        $cursor = $collection->find(array("deleted" => false));
        $lineList = Array();
        foreach ($cursor as $doc) {
            $users = $doc["users"];
            $vote_rate = count($users) . "/" . $userCount;
            $finish = $doc["finish"];
            if ($finish == true)
                $line = sprintf("<tr style='background:green' ");
            else
                $line = sprintf("<tr ");
            $line .= sprintf("data-id='%s' data-md5pass='%s'><td style='width:200'>%s</td><td style='width:1000'>%s<div><hr><pre>%s</pre></div></td><td style='width:100'>%s</td><td>
                    <a href='' name='vote'>投票($vote_rate)</a>
                    <a href='' name='delete'>删除</a>
                    <a href='' name='finish'>完成</a>
                    <a href='' name='view_detail'>投票详情</a>
                    </td></tr>\n", $doc["_id"], $doc["passwd"],  date("Y-m-d H:i:s", $doc["time"]), $doc["title"], $doc["content"], $doc["author"]);

            array_push($lineList, $line);
        }

        $lineList = array_reverse($lineList);

        echo implode("", $lineList);
        $mongo->close();
    } catch(MongoConnectionException $e) {
        //handle connection error
        die($e->getMessage());
    }
?>
</table>
</div>

<br> 
<div id='detail'>
</div>

<script>
    function get_user() {
        var user = $("select").val();
        if (user == "选择用户") {
            alert("请告诉我你是谁（选择用户）？");
            return false;
        }
        return user;
    }

    $("a#create_votes").click(function(e) {
        var user = get_user();
        if ( user == false) return false;
        window.location.href= $(this).attr("href") + "?user=" + user;
        e.preventDefault();
    });

    $("table#list td").hover(function(e) {
        $("td div").hide();
        $("div", this).show();
    });

    $("a[name='vote']").click(function(e) {
        var user = get_user();
        if ( user == false) return false;

        var ret = confirm("确定要支持这项决议?");
        if (ret != true) return false;

        var mg_id = $(this).parent().parent().data('id');
        
        window.location.href = "./php/vote.php?user=" + user + "&id=" + mg_id;

        e.preventDefault();
    });

    $("a[name='delete']").click(function(e) {
        var passwd = prompt("请输入密码:");
        if (passwd == null) return false;
        var passmd5 = $(this).parent().parent().data('md5pass');
        if (hex_md5(passwd) != passmd5) {
            alert("密码错误");
            return false;
        }
        
        var mg_id = $(this).parent().parent().data('id');
        window.location.href = "./php/delete.php?id=" + mg_id + "&passwd=" + passwd;

        e.preventDefault();
    });

    $("a[name='finish']").click(function(e) {
        var passwd = prompt("请输入密码:");
        if (passwd == null) return false;
        var passmd5 = $(this).parent().parent().data('md5pass');
        if (hex_md5(passwd) != passmd5) {
            alert("密码错误");
            return false;
        }
        
        var mg_id = $(this).parent().parent().data('id');
        window.location.href = "./php/finish.php?id=" + mg_id + "&passwd=" + passwd;

        e.preventDefault();
    });

    $("a[name='view_detail']").click(function(e) {
        var mg_id = $(this).parent().parent().data('id');
        $("div#detail").html("").load("./php/detail.php?id=" + mg_id);

        e.preventDefault();
    });
</script>
