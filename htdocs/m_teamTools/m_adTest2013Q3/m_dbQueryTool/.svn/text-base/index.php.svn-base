<!DOCTYPE html>

<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="../../../js-base/easyui/themes/default/easyui.css" />
<link rel="stylesheet" type="text/css" href="../../../js-base/easyui/themes/icon.css" />
<link rel="stylesheet" type="text/css" href="../../../js-base/easyui/demo/demo.css" />
<link rel="stylesheet" type="text/css" href="../../../css-base/grid.css" />
<link rel="stylesheet" type="text/css" href="./css/index.php.css" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/json.min.js"></script>
<script type="text/javascript" src="../../../js-base/easyui/jquery.easyui.min.js"></script>

<?php
    include("./user_conf/query_conf.php");
    include("./user_conf/category_conf.php");
    include("./user_conf/db_conf.php");

    $filter_name = "all";
    $filter_db_host = "all";

    if (array_key_exists("filter_user", $_GET))
        $filter_name = $_GET["filter_user"];
    if (array_key_exists("filter_host", $_GET))
        $filter_db_host = $_GET["filter_host"];
?>
</head>

<body>
<h2>广告测试组常用数据库快捷查询</h2>
<a href="" id="set_filter">过滤</a>
<a target="_blank" href="./readme.php">readme</a>

<br>
<br>

<div id="filter_set_area">
按用户:
<select id="filter_by_user">
<?php
    $user_list = Array("all");
    foreach ($queries as $query) {
        $users = $query["users"];
        foreach ($users as $user) {
            if (!in_array($user, $user_list))
                array_push($user_list, $user);
        }
    }

    foreach ($user_list as $user) {
        if ($user == $filter_name)
            echo "<option selected>$user</option>\n";
        else
            echo "<option>$user</option>\n";
    }
?>
</select>

 按数据库:
<?php
    $db_list = Array("all");
    foreach ($queries as $query) {
        $db_refer_names = $query["refer_db_info"];
        foreach ($db_refer_names as $db_refer_name) {
            $db_obj = $db_info[$db_refer_name];
            $db_host = $db_obj["db_host"];
            if (!in_array($db_host, $db_list))
                array_push($db_list, $db_host);
        }
    }
?>

<select id="filter_by_db">
<?php
    foreach ($db_list as $db_host) {
        if ($db_host == $filter_db_host)
            echo "<option selected>$db_host</option>\n";
        else
            echo "<option>$db_host</option>\n";
    }
?>
</select>

<input type=button id="do_filter" value="过滤" />

<br><br>
</div>

<div class="yui3-g">
    <div class="yui3-u-1-5" id="func_idx">
<?php
    $tree_list = Array();
    foreach ($queries as $sub_arr) {
        $users = $sub_arr["users"];
        $cate_names = $sub_arr["cate_list"];
        $id = $sub_arr["id"];
        $title_name = $sub_arr["title_name"];
        $desc = $sub_arr["desc"];
        $refer_db_list = $sub_arr["refer_db_info"];
        if ($filter_name != "all" && ! in_array($filter_name, $users))
            continue;

        foreach ($cate_names as $cate) {
            $cate_name = $cate;
            if (array_key_exists($cate, $cate_conf_list)) {
                $cate_name = $cate_conf_list[$cate];
            }

            if (!array_key_exists($cate_name, $tree_list)) {
                $tree_list[$cate_name] = Array();
            }

            array_push($tree_list[$cate_name], Array($id, $title_name, $desc, $refer_db_list));
        }
    }
?>

<!-- tree definition -->
<ul class="easyui-tree">

<?php
foreach ($tree_list as $cate_name => $arr_list) {

    $cnt = 0;
    $str = "";
    foreach ($arr_list as $sub_arr) {
        list($id, $title, $desc, $refer_db_list) = $sub_arr;
        foreach ( $refer_db_list as $refer_db_info) {
            $host = $db_info[$refer_db_info]["db_host"];
            $db = $db_info[$refer_db_info]["db_name"];
            if ($filter_db_host != "all" && $host != $filter_db_host)
                continue;
            $str .= "<li><a class='query_li' data-host='$host' data-id='$id' data-refer_name='$refer_db_info' title='$desc'>$title($host:$db)</a></li>\n";
            $cnt ++;
        }
    }

    if ($cnt == 0) continue;

    echo "<li data-options=\"state:'closed'\">\n";
    echo "<span>$cate_name<label style='color:blue'>($cnt)</label></span>\n";
    echo "<ul>\n";
    echo $str;
    echo "</ul>\n";
    echo "</li>\n";
}
?>
</ul>
<!-- end of tree definition -->
    </div>

    <div class="yui3-u-3-4" id="func_area">
<?php
    foreach($queries as $query_arr) {
        $id = $query_arr["id"];
        echo "<div class='container' name='$id'>\n";
        echo "  <div class='cond_area'></div>\n";
        echo "  查询结果：\n";
        echo "  <hr>\n";
        echo "  <div class='result_area'></div>\n";
        echo "</div>\n";
    }
?>
    </div>
</div>

<script src="./js/index.php.js"></script>
</body>
</html>
