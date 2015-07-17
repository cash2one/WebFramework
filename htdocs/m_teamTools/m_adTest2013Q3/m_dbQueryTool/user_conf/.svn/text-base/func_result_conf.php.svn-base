<?php

// ==========================================================================
// for simpleSelect
// ==========================================================================
function simpleSelectSqlBuilder($sub_arr, $cond_values_str, $schema_file) {
    include($schema_file);

    $sql_str = "select ";
    $start = true;
    foreach ($sub_arr["result_field_names"] as $field) {
        if ($start == true) {
            $start = false;
            $sql_str .= $field;
            continue;
        }
        $sql_str .= ",$field";
    }

    $sql_str .= " from " . $sub_arr["table_name"];

    $value_list = explode("", $cond_values_str);
    for($i = 0; $i < count($sub_arr["query_field_names"]); $i++) {
        $cond_name = $sub_arr["query_field_names"][$i];
        $cond_value = $value_list[$i];
        if (strpos($schema_arr[$cond_name], "char") != false) {
            $cond_value = "'$cond_value'";
        }

        if ($i == 0) {
            $sql_str .= " where $cond_name=$cond_value";
            continue;
        }       
        $sql_str .= " and $cond_name=$cond_value";
    }

    return $sql_str;
}

function simpleSelectShowResult($sub_arr, $result) {
    echo "<tr><th>" . implode("</th><th>", $sub_arr["result_field_names"]) . "</th></tr>\n";
    while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
        echo "<tr><td>" . implode("</td><td>", $row) . "</td></tr>\n";
    }    
}

// ==========================================================================
// for valueReadableMap
// ==========================================================================
function titleValueMapShowResult($sub_arr, $result) {
    $field_name_map = Array(
        "SPONSOR_BALANCE" => Array(
            "ACTUAL_BALANCE" => "实际余额",
            "VIRTUAL_BALANCE" => "体验金余额",
            "DISCOUNT_RATE" => "折扣率",
            "CREDIT_LIMIT" => "信用额度",
            "ACTUAL_AMOUNT_BALANCE" => "实际结余",
            "VIRTUAL_AMOUNT_BALANCE" => "体验金结余",
            "DISCOUNT_TYPE" => "折扣类型",
            "SETTLEMENT_TYPE" => "结算类型"
        ),
        "AGENT_BALANCE" => Array(
            "ACTUAL_BALANCE" => "实际余额",
            "VIRTUAL_BALANCE" => "体验金余额",
        ),
    
        "FILTERARG" => Array(
            "SERVER_ID" => "对应的平台",
        ),
    );
    $field_value_map = Array(
        "SPONSOR_BALANCE" => Array(
            "ACTUAL_BALANCE" => Array(
               "__type__" => "str_append",
               "__param__" => "分",
            ),
            "VIRTUAL_BALANCE" => Array(
               "__type__" => "str_append",
               "__param__" => "分",
            ),
            "DISCOUNT_RATE" => Array(
               "__type__" => "math_divide",
               "__param__" => 10000.0,
            ),
            "CREDIT_LIMIT" => Array(
               "__type__" => "str_append",
               "__param__" => "分",
            ),
            "ACTUAL_AMOUNT_BALANCE" => Array(
               "__type__" => "str_append",
               "__param__" => "分",
            ),
            "VIRTUAL_AMOUNT_BALANCE" => Array(
               "__type__" => "str_append",
               "__param__" => "分",
            ),
            "DISCOUNT_TYPE" => Array(
                "__type__" =>  "static_map",
                "__param__" => Array("0" => "普通固定折扣", "1" => "月结固定折扣", 2 => "月度总消费条件折扣", 3 => "月度日均消费条件折扣"),
            ), 
            "SETTLEMENT_TYPE" => Array(
                "__type__" =>  "static_map",
                "__param__" => Array("0" => "预付款", "1" => "月结"),
            ), 
        ),
        "AGENT_BALANCE" => Array(
            "ACTUAL_BALANCE" => Array(
               "__type__" => "str_append",
               "__param__" => "分",
            ),
            "VIRTUAL_BALANCE" => Array(
               "__type__" => "str_append",
               "__param__" => "分",
            ),
        ),

        "FILTERARG" => Array(
            "SERVER_ID" => Array(
                "__type__" =>  "static_map",
                "__param__" => Array(
                        "1" => "词典", 
                        "2" => "搜索", 
                        "3" => "邮箱", 
                        "4" => "联盟", 
                        "5" => "线下直销", 
                        "6" => "频道", 
                        "7" => "智选", 
                    ),
            ),    
        ),
    );

    $title_names = table_title_convert($sub_arr, $field_name_map);

    echo "<tr><th>" . implode("</th><th>", $title_names) . "</th></tr>\n";
    while ($row = mysql_fetch_array($result, MYSQL_NUM)) {
        $field_values = row_value_convert($row, $sub_arr, $field_value_map);
        echo "<tr><td>" . implode("</td><td>", $field_values) . "</td></tr>\n";
    }    
}

// ==========================================================================
// for oneOrSelect
// ==========================================================================
function oneOrSelectSqlBuilder($sub_arr, $cond_values_str, $schema_file) {
    include($schema_file);

    $sql_str = "select ";
    $start = true;
    foreach ($sub_arr["result_field_names"] as $field) {
        if ($start == true) {
            $start = false;
            $sql_str .= $field;
            continue;
        }
        $sql_str .= ",$field";
    }

    $sql_str .= " from " . $sub_arr["table_name"] . " where ";
    $value_list = explode("", $cond_values_str);
    for($i = 0; $i < count($sub_arr["query_field_names"]); $i++) {
        $cond_name = $sub_arr["query_field_names"][$i];
        $cond_value = $value_list[$i];
        if ($cond_value == "") continue;

        if (strpos($schema_arr[$cond_name], "char") != false) {
            $cond_value = "'$cond_value'";
        }

        $sql_str .= "$cond_name=$cond_value";
        break;
    }

    return $sql_str;
}

// ==========================================================================
// for noInput
// ==========================================================================
function noInputSqlBuilder($sub_arr, $cond_values_str, $schema_file) {
    $sql_str = "select ";
    $start = true;
    foreach ($sub_arr["result_field_names"] as $field) {
        if ($start == true) {
            $start = false;
            $sql_str .= $field;
            continue;
        }
        $sql_str .= ",$field";
    }

    $sql_str .= " from " . $sub_arr["table_name"] . " where ";
    for($i = 0; $i < count($sub_arr["query_field_names"]); $i++) {
        $cond_name = $sub_arr["query_field_names"][$i];
        if ($i == 0)
            $sql_str .= "$cond_name";
        else 
            $sql_str .= " and $cond_name";
       
        break;
    }

    return $sql_str;
}
