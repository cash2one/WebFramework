<?php

// ======= Variables settings =========
$machine_info_array = Array(
    "cpu" => Array(
        "cpu report",
        "cpu user",
        "cpu sys",
        "cpu nice",
        "cpu iowait",
        "cpu idle",
    ),

    "disk_block" => Array(
        "block report",
    ),

    "inode" => Array(
        "inode report", 
    ),

    "load" => Array(
        "load report",
        "load one",
        "load five",
        "load fifteen",
    ),

    "mem" => Array(
        "swap used rate",
        "mem report",
        "swap used",
        "swap total",
        "swap free",
        "mem used",
        "mem free",
        "mem cached",
        "mem buffer",
    ),

    "network" => Array(
        "packets report",
        "network report.avg",
        "network report",
        "packets out",
        "packets in",
        "bytes out",
    ),

    "ping" => Array(
        "response report",
        "ping report",
    )
);

// ======= Functions Defintions =========
function getMaxElementsCount($input_array) {
    $count = 0;

    foreach ($input_array as $sub_arr) {
        if (count($sub_arr) > $count) {
            $count = count($sub_arr);
        }
    }

    return $count;
}

function output_machine_info($input_array) {
    $max_row_count = getMaxElementsCount($input_array);
    $temp_array = Array();
    for ($i = 0; $i <= $max_row_count; $i ++) {
        // include header 
        array_push($temp_array, Array());
    }

    // fill in some elements to keep count the same
    foreach ($input_array as $name => $sub_array) {
        array_push($temp_array[0], "<th>" . $name . "</th>");

        if (count($sub_array) < $max_row_count) {
            for ($i = count($sub_array) + 1; $i <= $max_row_count; $i++) {
                array_push($input_array[$name], "");
            }
        }

        for ($i = 0; $i < $max_row_count; $i ++) {
            $name_show = $input_array[$name][$i];
            if ($name_show == "") {
                array_push($temp_array[$i + 1], "<td></td>");

            } else {
                array_push($temp_array[$i + 1], "<td><input type='checkbox' for='$name_show' name=" . $name  . ">" . $name_show . "</input></td>");
            }
        }
    }

    for ($i = 0; $i <= $max_row_count; $i++) {
        if ($i == 0) {
            // table header
            echo "<thead>\n";
            echo "<tr><th colspan='" . count($input_array) . "'>显示额外的信息</th></tr>\n";
            echo "<tr>" . implode("", $temp_array[$i]) . "</tr>\n";
            echo "</thead>\n";
            echo "<tbody id='machine_info_tbody'>\n";

        } else {
            echo "<tr>" . implode("", $temp_array[$i]) . "</tr>\n";
        }
    }
    echo "</tbody>\n";
}

output_machine_info($machine_info_array);
