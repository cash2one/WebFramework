<html>
<head> 
    <meta charset="utf-8" />
    <link rel="stylesheet" type="text/css" href="./css/view.php.css" />
    <link rel="stylesheet" href="./css/grid.css" type="text/css" media="all" />
    <script src="../../js-base/jquery.min.js"></script>
    <script src="../../js-base/jquery-ui.min.js"></script>
    <script src="../../js-base/json.min.js"></script>
</head>

<body>

<?php
include("./php/mysqlUtil.php");
include("./php/mongoUtil.php");
function getVal($paramName, $defVal) {
    if (array_key_exists($paramName, $_POST))
        return $_POST[$paramName];
    return $defVal;
}

$server_name = getVal("server_name", "");
$server_port = getVal("server_port", "");
$username    = getVal("username", "");
$password    = getVal("password", "");
if ($server_name == "" || $server_port == "" || $username == "" || $password == "") {
    echo "输入参数不可为空，<a href='./index.php'>返回</a>";
    return;
}
setMysqlAuth($server_name, $server_port, $username, $password);
echo "<script>", "\n";
echo "  var server_name = '$server_name';", "\n";
echo "  var server_port = '$server_port';", "\n";
echo "  var username = '$username';", "\n";
echo "  var password = '$password';", "\n";
echo "</script>", "\n";
?>


<div id="header">
    <input type=checkbox id="edit_mode" />编辑模式
    <input type=button id="save" value="保存" disabled />
    <a href="./index.php">返回首页</a>
</div>

<hr>

<div class='yui3-g'>
    <div class='yui3-u-1-6'>
        <table border='1' id="db_area">
            <tr>
                <th>数据库</th>
            </tr>

            <?php
                $dbList = getDBList();
                $commentDbList = get_comment_db($server_name, $server_port);
                foreach ($dbList as $dbName) {
                    if (in_array($dbName, $commentDbList)) {
                        echo "<tr><td><a href='' name='$dbName' class='db commented'>$dbName</a></td></tr>", "\n";
                    } else {
                        echo "<tr><td><a href='' name='$dbName' class='db'>$dbName</a></td></tr>", "\n";
                    }
                }
            ?>
        </table>
    </div>

    <div class='yui3-u-1-4'>
        <table border='1' id="table_area">
            <thead>
                <tr>
                    <th>数据库表</th>
                </tr>
            </thead>

            <tbody>
            </tbody>
        </table>
    </div>

    <div class='yui3-u-7-12'>
        <table id="detail" border='1'>
        </table>
    </div>
</div>

<script src="./js/view.php.js"></script>
</body>

</html>
