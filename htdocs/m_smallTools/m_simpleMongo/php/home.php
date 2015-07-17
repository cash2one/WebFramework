<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../../css-base/grid.css" type="text/css" />
    <link rel="stylesheet" href="../js/treeview/jquery.treeview.css" type="text/css" />
    <link rel="stylesheet" href="../css/home.php.css" type="text/css" />
    <link rel="stylesheet" href="../../m_webTechPages/js/jPages-master/css/jPages.css" type="text/css" />

    <script src="../../../js-base/jquery.min.js" type="text/javascript"></script>
    <script src="../../../js-base/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../../js-base/json.min.js" type="text/javascript"></script>
    <script src="../../m_webTechPages/js/jPages-master/js/jPages.min.js" type="text/javascript"></script>

    <script src="../js/treeview/jquery.treeview.js" type="text/javascript"></script>

<?php
    $hostname = $_POST["host"];
    $port     = $_POST["port"];
    $username = $_POST["username"];
    $password = $_POST["password"];

    function get_db_info($hostname, $port, $username, $password, &$output) {
        $cmd = "./python/get_db_info.py '$hostname' '$port' '$username' '$password'";
        exec($cmd, $output, $ret);
        return $ret;
    }

    $output = Array(); //data format: dbnamecollection1collection2dbname2collection1collection2...
    $ret = get_db_info($hostname, $port, $username, $password, $output);
    if ($ret != 0) {
        echo "<font color='red'>错误：数据库连接错误，请确保输入的数据正确！</font><a href='../index.php'>重新输入</a>";
        exit($ret);
    }
    file_put_contents("../data/login.txt", "$hostname$port$username\n", FILE_APPEND);
    // $output = "db1table1table2table3db2table1table2";
?>

    <script>
        <?php
            echo "var _hostname = '$hostname';\n";
            echo "var _port = '$port';\n";
            echo "var _user = '$username';\n";
            echo "var _password= '$password';\n";
        ?>
    </script>
</head>

<body class="colorful">
    <div>
        <?php
            echo "<b>HOST:</b>$hostname  <b>PORT:</b>$port  <b>USER:</b>$username";
        ?>
        <span style="position:absolute;right:10px;">
            <input type=checkbox id="show_result_only"/> 只显示结果
            <a href="../index.php" id="logout_link">登出</a>
        </span>
    </div>

    <hr>

    <div class="yui3-g" style="height:100%">
        <div class="yui3-u-1-5" id="index"> 
            <ul id="db_table_list" class="filetree">

        <?php
            $temp_list = explode("", $output[0]);
            foreach ($temp_list as $dbname_collection_str) {
                $dbco_list = explode("", $dbname_collection_str);
                if (count($dbco_list) == 0) continue;

                $db_name = $dbco_list[0];
                echo "<li>\n";
                echo "  <span class='dbname'>$db_name(" . (count($dbco_list) - 1)  . ")</span>\n";
                echo "  <ul>\n";
                foreach (array_splice($dbco_list, 1) as $table_info) {
                    list($table_name, $row_cnt) = explode("", $table_info);
                    echo "      <li>\n";
                    echo "          <span class='table'><a name='$db_name:$table_name' href=''><img src='../resources/table.png'/>$table_name(" . $row_cnt . ")</a></span>\n";
                    echo "      </li>\n";
                }
                echo "  </ul>\n";
                echo "</li>\n";
            }
        ?>

            </ul>
        </div>

        <div class="yui3-u-4-5" style="background:#ffffff;z-index:1;">
            <div id="condition_set">
                <table border='1' class="query_it" id="query_table">
                    <caption>欢迎查询!</caption>
                    <tr>
                        <th>字段名选择</th>
                        <th>查询条件</th>
                        <th>条件范围(<a href='./help.php' target='_blank'>样例</a>)</th>
                        <td class="op"><a href="" name="add">添加</a> <a href="" name="del" class='head_a'>删除</a></td>
                    </tr>
                </table>
    
                <div id="show_json" class="query_it">
                </div>

                <input id="limit" placeholder="默认10最大1000行" class="query_it" />
                <input type=button id="query" value="查询" class="query_it" />
                <span class="query_it"><input type=checkbox id="debug" />显示json</span>
            </div>
    
            <div id="query_result">
            </div>
        </div>
    </div>

    <script src="../js/home.php.js" type="text/javascript"></script>
</body>
</html>
