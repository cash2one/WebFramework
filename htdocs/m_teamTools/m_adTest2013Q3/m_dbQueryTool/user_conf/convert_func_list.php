<?php

function table_title_convert($sub_arr, $field_name_map) {
    $title_names = Array();

    $table_name = $sub_arr["table_name"];
    for ($i = 0; $i < count($sub_arr["result_field_names"]); $i++) {
        $field_name = $sub_arr["result_field_names"][$i];
        if (array_key_exists($field_name, $field_name_map[$table_name])) 
            $title_names[$i] = $field_name_map[$table_name][$field_name] . "($field_name)";
        else
            $title_names[$i] = $field_name;
    }

    return $title_names;
}

function row_value_convert($row, $sub_arr, $field_value_map) {
    $field_values = Array();

    $table_name = $sub_arr["table_name"];
    for ($i = 0; $i < count($sub_arr["result_field_names"]); $i++) {
        $field_values[$i] = $row[$i];
        $field_name = $sub_arr["result_field_names"][$i];
        if (array_key_exists($field_name, $field_value_map[$table_name])) {
            $func_name = $field_value_map[$table_name][$field_name]["__type__"];
            $param     = $field_value_map[$table_name][$field_name]["__param__"];

            if ($func_name == "str_append") {
                $field_values[$i] .= $param;

            } elseif ($func_name == "math_divide") {
                $field_values[$i] /= $param;

            } elseif ($func_name == "static_map") {
                $field_values[$i] = $param[$field_values[$i]] . "(" . $field_values[$i] . ")"; 
            }
        }
    }

    return $field_values;
}
