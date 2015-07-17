<?php

// ==========================================================================
// for simpleSelect
// ==========================================================================
function simpleSelectCond($sub_arr) {
    foreach ($sub_arr["query_field_names"] as $cond_val) {
        echo "<tr class='input_val'><th style='width:100px;'>$cond_val:</th><td><input style='width:100%' placeholder='" . $sub_arr["desc"] . "'/></td></tr>\n";
    }
}

// ==========================================================================
// for simpleSelect
// ==========================================================================
function noInputCond($sub_arr) {
    foreach ($sub_arr["query_field_names"] as $cond_val) {
        echo "<tr class='input_val'><th style='width:100px;'>无需输入:</th><td><input style='width:100%' placeholder='" . $sub_arr["desc"] . ", $cond_val'/></td></tr>\n";
    }
}
