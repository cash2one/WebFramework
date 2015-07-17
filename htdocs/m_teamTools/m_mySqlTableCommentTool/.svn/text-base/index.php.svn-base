<html>
<head> <meta charset="utf-8" />
  <link rel="stylesheet" type="text/css" href="./css/index.php.css" />
  <link rel="stylesheet" href="../../css-base/jquery-ui.min.css" type="text/css" media="all" />
  <link rel="stylesheet" href="../../css-base/grid.css" type="text/css" media="all" />
  <script src="../../js-base/jquery.min.js"></script>
  <script src="../../js-base/jquery-ui.min.js"></script>
  <script src="../../js-base/json.min.js"></script>

  <!-- link rel="stylesheet" href="../../js-base/bootstrap/css/bootstrap.min.css" type="text/css" media="all" / -->
  <!-- script src="../../js-base/bootstrap/js/bootstrap.min.js"></script -->
</head>

<body>
<h3>Mysql数据库字段备注工具</h3>

<form method="post" action="view.php">
    <table align="center">
        <tr>
            <th>服务器名: </th>
            <td><input type=text name="server_name" /></td>
        </tr>
        <tr>
            <th>端口号: </th>
            <td><input type=text value="3306" name="server_port" /></td>
        </tr>
        <tr>
            <th>用户名: </th>
            <td><input type=text name="username" /></td>
        </tr>
        <tr>
            <th>密码: </th>
            <td><input type=password name="password" /></td>
        </tr>
        <tr>
            <th></th>
            <td>
                <input type=submit value="查看" />
            </td>
        </tr>
    </table>
</form>

<br>
<br>
已有备注数据库表：<br>
<?php
    include("./php/mongoUtil.php");
    $retArray = getCommentInfo();
    foreach ($retArray as $key => $tableNameList) {
        $tableNameList = array_unique($tableNameList);
        echo "$key : ", implode(", ", $tableNameList), "<br>";
    }
?>

<script src="./js/index.php.js"></script>
</body>

</html>
