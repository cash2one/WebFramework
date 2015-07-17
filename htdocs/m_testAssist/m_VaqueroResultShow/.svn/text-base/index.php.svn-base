<html>
<head>
    <meta charset="utf-8" />

    <link rel="stylesheet" type="text/css" href="./css/index.php.css" />
    <link rel="stylesheet" href="../../css-base/jquery-ui.min.css" type="text/css" media="all" />

    <script src="../../js-base/jquery.min.js"></script>
    <script src="../../js-base/jquery-ui.min.js"></script>
    <script src="../../js-base/ldap_login.js"></script>
    <script src="../../js-base/json.min.js"></script>
</head>

<body>
<h2>Vaquero性能测试结果</h2> -- 查看Vaquero上的结果(<a href='./index2.php'>老版本</a>)

<br>
<br>

选择用户: <select id="users">
<?php
    $users = glob("./vaquero_data/*");  
    foreach ($users as $user) {
        $user = basename($user);
        echo "<option>$user</option>";
    }
?>
</select>

&nbsp;
每行图片数: <select id="count_in_row">
<?php
    for ($i = 1; $i <= 10; $i ++) {
        if ($i != 3) {
            echo "<option>$i</option>";
        } else {
            echo "<option selected>$i</option>";
        }
    }
?>
</select>

&nbsp;
时间选择: <select id="period">
<?php
    $period_names = Array("hour", "8hour", "day", "week", "month", "manual");
    foreach ($period_names as $name) {
        echo "<option>$name</option>";
    }
?>
</select>

<span id="manual_detail">
    From <input id="start_date" style='width:75px;'/> <input id="start_ts" style='width:40;' value='01:00'/>
    To  <input id="end_date" style='width:75px;'/> <input id="end_ts" style='width:40;' value='01:00'/>
</span>

<br>
<br>

<table id="output" border='1'>
    <thead>
        <tr><th>cub</th><th>prod</th><th>type</th><th>选择</th></tr>
    </thead>
    <tbody>
    </tbody>
</table>

<br>
<br>
<a href="" id="download">打开性能结果图片</a>    
<br>
<hr>
<br>

<table id="image_area" border='1'>
</table>

<script src="./js/index.php.js"></script>
</body>

</html>
