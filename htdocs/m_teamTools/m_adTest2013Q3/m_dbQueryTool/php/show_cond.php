<?php

$id = $_POST["id"];
$refer_db_name = $_POST["refer_name"];

include("../user_conf/query_conf.php");
include("../user_conf/db_conf.php");
include("../user_conf/func_cond_conf.php");
?>

<table style="width:100%">
<?php

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

    $title_name = $sub_arr["title_name"];
    $host_name  = $sub_arr["db_host"];
    $db_name    = $sub_arr["db_name"];
    $table_name = $sub_arr["table_name"];
    echo "<thead><th colspan='2'>$title_name(数据来源:$host_name:$db_name:$table_name)</th></thead>\n";
    if (is_callable($query_type . "Cond")) {
        call_user_func($query_type . "Cond", $sub_arr);
    } else {
        call_user_func("simpleSelectCond", $sub_arr);
    }
    echo "<tfoot><th colspan=2><input type=button value='提交' /></th></tfoot>\n";

?>
</table>
