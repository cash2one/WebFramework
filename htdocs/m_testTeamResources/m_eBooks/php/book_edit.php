<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/book_edit.php.css" />
    <script src="../../../js-base/jquery.min.js"></script>

<?php
    $target_name = $_GET["tname"];
    include("./sqlite_lib.php");
    $results = load_book_info($target_name);
    if (count($results) != 1) {
        echo "错误！ 载入数据失败, 数据库中未找到相关信息!", "\n";
        echo "<a href='../index.php'>返回</a>", "\n";
        return;
    }

    list($tname, $name, $owner, $ctime, $tags, $deleted, $size, $field, $douban) = $results[0];
?>
</head>

<body>

<?php
    echo "<table border='1' name='$target_name'>\n";
?>
    <caption>书籍信息修改</caption>
    <tr>
        <th>书籍名称:</th>
<?php
        echo "<td><input class='input' type=text name='name' value='$name' /></td>";
?>
    </tr>
    <tr>
        <th>书籍标签:</th>
<?php
        echo "<td><input class='input' type=text name='tags' value='$tags' placeholder='标签用于查找' /></td>";
?>
    </tr>
    <tr>
        <th>豆瓣书评:</th>
<?php
        echo "<td><input class='input' type=text name='douban' value='$douban' placeholder='请添加http头' /></td>";
?>
    </tr>
    <tr>
        <td colspan='2'><center><a href='' class='update'>更新</a> <a href='../index.php'>返回</a></center></td>
    </tr>
</table>

<script src="../js/book_edit.php.js"></script>
</body>
</html>
