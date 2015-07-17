<html>
<head>
<title>测试设备登记</title>
<meta charset="utf-8">
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>
<style>
    a {text-decoration: none; color: blue}
    a:hover {text-decoration: underline}
</style>
<?php
    
    date_default_timezone_set("PRC");
    $file = "./data/log.txt";
    if (!file_exists($file)) touch($file);
    $user_list = array();
    $device_list = array();

    $lines = file($file);
    foreach ($lines as $line) {
        $fields = explode("", $line);
        if (count($fields) != 3) continue;
        list($time, $user, $device) = $fields;
        array_push($user_list, $user);
        array_push($device_list, $device);
    }
?>
</head>

<body style="background:#20B2AA">
<h3 style="display:inline">测试设备使用登记</h3> 网址：<a href="http://d2.iyoudao.net">http://d2.iyoudao.net</a>

<br>
<br>

<center>
<table>
<tr>
<td>
    设备名称:
</td>
<td>
    <select id="device_list" name="device">
        <?php
            foreach (array_unique($device_list) as $device) {
                echo "<option>$device</option>", "\n";
            }
        ?>
    </select>
</td>
<td>
    <a href='' id="add_d" title="添加新设备">+</a>
</td>
</tr>
<tr>
<td>
    用户名:
</td>
<td>
    <select id="user_list" name="user">
        <?php
            foreach (array_unique($user_list) as $user) {
                echo "<option>$user</option>", "\n";
            }
        ?>
    </select>
</td>
<td>
    <a href='' id="add_u" title="添加新用户">+</a>
</td>
</tr>
<tr>
    <td></td>
    <td><input type=button id=commit value="添加" /></td>
    <td></td>
</tr>
</table>

<br>

<table id="outputTable" style="width:100%" border=1>
<?php
    echo "<tr><th>设备名称</th><th>借用人</th><th>登记时间</th></tr>", "\n";
    foreach (array_reverse($lines) as $line) {
        $fields = explode("", $line);
        if (count($fields) != 3) continue;
        list($time, $user, $device) = $fields;
        $time = date("Y-m-d H:i:s", $time);
        echo "<tr><td>$device</td><td>$user</td><td>$time</td></tr>", "\n";
    }
?>
</table>
</center>

<script>
$(function(e) {
    $("a").click(function(e) {
        var id = $(this).attr("id");
        var info = "";
        if (id == "add_d") {
            info = "请输入设备名称:";

        } else if(id == "add_u") {
            info = "请输入用户名:";
        } else {
            return true;
        }
        var val = prompt(info);
        if (val == "" || val == null) 
            return false;

        if (id == "add_d") {
            $("select#device_list").append("<option selected>" + val + "</option>");
        } else if(id == "add_u") {
            $("select#user_list").append("<option selected>" + val + "</option>");
        }

        e.preventDefault();

    });

    $("input#commit").click(function(e) {
        var device = $("select#device_list").val();
        var user = $("select#user_list").val();
        if (device == null || user == null) {
            alert("请输入设备名或者用户名");
            return false;
        }
        
        $(this).attr("disabled", "disabled");
        $.post("./result.php", {"device": device, "user": user}, function(data) {
            window.location.reload();
        });
    });
});
</script>
</body>
</html>
