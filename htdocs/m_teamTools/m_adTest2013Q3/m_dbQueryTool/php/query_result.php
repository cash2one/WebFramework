<?php

$id = $_POST["id"];
$refer_db_name = $_POST["refer_name"];
$cond_values_str = $_POST["cond_values_str"];

include("../user_conf/query_conf.php");
include("../user_conf/db_conf.php");
include("../user_conf/func_result_conf.php");
include("../user_conf/convert_func_list.php");

show_result($id, $refer_db_name, $cond_values_str);

function build_schema_file($sub_arr) {
    global $conf_list;

    $host = $sub_arr["db_host"];
    $port = $sub_arr["db_port"];
    $db_name = $sub_arr["db_name"];
    $table_name = $sub_arr["table_name"];
    list($username, $passwd) = $conf_list[$host . ":" . $port];
    $schema_file = "schema-list/$host-$port-$db_name-$table_name.php";

    if (!file_exists($schema_file)) {
        $conn = mysql_connect("$host:$port", $username, $passwd) or die("Could not connect: " . mysql_error());
        mysql_select_db($db_name, $conn);
        $result = mysql_query("DESC $table_name", $conn) or die("无效的查询条件: " . mysql_error());

        $handle = fopen($schema_file, "w");
        fwrite($handle, "<?php\n");
        fwrite($handle, "\$schema_arr = Array(\n");
        while ($row = mysql_fetch_assoc($result)) {
            $user_name = $row["Field"];
            $type      = $row["Type"];
            fwrite($handle, "\t\"$user_name\" => \"$type\",\n") or die("不能写入到文件 $schema_file");
        }
        fwrite($handle, ");\n");
        fclose($handle);

        mysql_free_result($result);
        mysql_close($conn);
    }

    return $schema_file;
}

function show_result($id, $refer_db_name, $cond_values_str) {
    global $queries;
    global $db_info;
    global $conf_list;

    $sub_arr = Array();
    foreach ($queries as $sub_arr1) {
        if ($sub_arr1["id"] == $id) {
            $sub_arr = $db_info[$refer_db_name];
        
            foreach($sub_arr1 as $key => $val) {
                $sub_arr[$key] = $val;
            }
            break;
        }
    }
    
    $query_type = "simpleSelect";
    if (array_key_exists("query_type", $sub_arr))
        $query_type = $sub_arr["query_type"];
    $schema_file = build_schema_file($sub_arr);
    if (is_callable($query_type . "SqlBuilder")) {
        $sql_str = call_user_func($query_type . "SqlBuilder", $sub_arr, $cond_values_str, $schema_file);
    } else {
        $sql_str = call_user_func("simpleSelectSqlBuilder", $sub_arr, $cond_values_str, $schema_file);
    }  

    $host = $sub_arr["db_host"];
    $port = $sub_arr["db_port"];
    $db_name = $sub_arr["db_name"];
    $table_name = $sub_arr["table_name"];
    list($username, $passwd) = $conf_list[$host . ":" . $port];
    $conn = mysql_connect("$host:$port", $username, $passwd) or die("Could not connect: " . mysql_error());
    mysql_query("set names utf8");
    mysql_select_db($db_name, $conn);
    $result = mysql_query($sql_str, $conn) or die("无效的查询条件: " . mysql_error());

    echo "<table border='1'>\n";
    if (is_callable($query_type . "ShowResult")) {
        call_user_func($query_type . "ShowResult", $sub_arr, $result);
    } else {
        call_user_func("simpleSelectShowResult", $sub_arr, $result);
    }  
    echo "</table>\n";

    mysql_free_result($result);
    mysql_close($conn);
}
