<?php

date_default_timezone_set("PRC");
include("./sqlite_lib.php");

# ================ Variables ============================
$dir_array = array();
$root_dir = "..";
$html_lines = Array();
$link_array = Array();
$menu_name = "";
$popular_access_dict = load_popular_db_data();
$hot_array = Array();
$pop_array = Array();
$new_tools = Array();

# ================ Functions ============================
# get sub-dirs name begins with "m_" under given dir
function get_valid_dir($dir) {
    $ret_list = array();
    $dir_list = scandir($dir);
    foreach ($dir_list as $dir2) {
        if (is_dir($dir . "/" . $dir2) && substr($dir2, 0, 2) == "m_") {
            array_push($ret_list, $dir2);
        }
    }
    return $ret_list;
}

# walk dir to find all dirs' name begins with "m_", save results into dir_array
function read_dir($dir, &$dir_array, $root, &$html_lines) {
    global $link_array, $hot_array, $pop_array, $new_tools;
    global $menu_name;
    global $popular_access_dict;
    $dir_list = get_valid_dir($dir);

    if ($root == false && count($dir_list) != 0) {
        array_push($html_lines, "<ul>");
    }

    foreach ($dir_list as $dir2) {
        $path = $dir . "/" . $dir2;
        if (! checkShow($path)) {
            continue;
        }

        $dir_list2 = get_valid_dir($path);
        if (count($dir_list2) == 0) {
            $show_name = get_item_name($path);
            array_push($html_lines, "<li><a href='' name='$path'><span class='item'>" . $show_name . "</span></a>");
            $cnt = 0;
            $tip_str = "";
            if (array_key_exists($path, $popular_access_dict["hot"])) {
                $cnt = $popular_access_dict["hot"][$path];
                $tip_str = "点击次数:$cnt";
                $show_name = "$show_name($cnt)";
                array_push($link_array[$menu_name], "<a class='btn btn-danger btn-xs' title='hot($tip_str)' href='$path'>$show_name</a>");
                array_push($hot_array, "<a class='btn btn-danger btn-xs' title='hot($tip_str)' href='$path'>$show_name</a> ");
            }elseif (array_key_exists($path, $popular_access_dict["good"])) {
                $cnt = $popular_access_dict["good"][$path];
                $tip_str = "点击次数:$cnt";
                $show_name = "$show_name($cnt)";
                array_push($link_array[$menu_name], "<a class='btn btn-success btn-xs' title='popular($tip_str)' href='$path'>$show_name</a>");
                array_push($pop_array, "<a class='btn btn-success btn-xs' title='popular($tip_str)' href='$path'>$show_name</a> ");
            } else {
                if (array_key_exists($path, $popular_access_dict["all"])) {
                    $cnt = $popular_access_dict["all"][$path];
                }
                $tip_str = "点击次数:$cnt";
                $show_name = "$show_name($cnt)";
                array_push($link_array[$menu_name], "<a class='btn btn-link btn-xs' href='$path' title='$tip_str'>$show_name</a>");
            }

            $filetime = @filectime($path . "/index.html"); 
            if (file_exists($path . "/index.php")) {
                $filetime = @filectime($path . "/index.php"); 
            }
            $mtime =  date("F d Y H:i:s.", $filetime);
            $new_tools[$filetime] = "<a class='btn btn-info btn-xs' title='hot($tip_str, $mtime)' href='$path'>$show_name</a> ";
        } else {
            array_push($html_lines, "<li><a href='' class='parent' name='$path'><span class='item'>" . get_item_name($path) . "</span></a>");
        }

        $dir_array[$dir2] = array();
        read_dir($path, $dir_array[$dir2], false, $html_lines);

        array_push($html_lines, "</li>");
    }

    if ($root == false && count($dir_list) != 0) {
        array_push($html_lines, "</ul>");
    }
}

function get_item_name($dir) {
    $basename = basename($dir); 
    $name_file = $dir . "/name.tts";
    
    if (file_exists($name_file)) {
        $content = file_get_contents($name_file);
        $content = trim($content);

        if ($content != "") return $content;
    }

    return substr($basename, 2);
}

function checkShow($dir) {
    $skip_file = $dir . "/skip.tts";
    if (file_exists($skip_file)) {
        return false;
    }
    return true;
}

# ================ Main Logic ============================
$dir_list = get_valid_dir($root_dir);
$count = count($dir_list);
$idx = 0;
foreach ($dir_list as $dir2) {
    $path = $root_dir . "/" . $dir2;
    $item_name = get_item_name("$path");

    $menu_name = $item_name;
    $link_array[$menu_name] = Array();

    if ($idx == $count - 1) {
        array_push($html_lines, "<li class='last top'><a href='' name='$path' class='parent'><span>$item_name</span></a>");
    } else {
        array_push($html_lines, "<li><a href='' name='$path' class='parent top'><span>$item_name</span></a>");
    }
    array_push($html_lines, '<ul><li>');

    $dir_array[$dir2] = array();
    read_dir("$path", $dir_array[$dir2], true, $html_lines);

    array_push($html_lines, "</ul>");
    array_push($html_lines, "</li>");

    $idx += 1;
}

$cell_in_row = 8;
$output_lines = Array();
array_push($output_lines, "<table class='table table-bordered'>");
array_push($output_lines, "<tr><th><p class='text-info'>Hot</p></th><td colspan='$cell_in_row'>" . implode("", $hot_array) . "</td></tr>");
array_push($output_lines, "<tr><th><p class='text-info'>Popular</p></th><td colspan='$cell_in_row'>" . implode("", $pop_array) . "</td></tr>");
krsort($new_tools, SORT_NUMERIC);
$new_tools = array_slice($new_tools, 0, 10);
array_push($output_lines, "<tr><th><p class='text-info'>New (Update)</p></th><td colspan='$cell_in_row'>" . implode("", $new_tools) . "</td></tr>");
foreach ($link_array as $name => $linkList) {
    $row_cnt = count($linkList);
    $left = $row_cnt % $cell_in_row;
    for ($i = $left; $i < $cell_in_row && $i != 0; $i++) {
        array_push($linkList, "");
    }

    $rowspan = count($linkList) / $cell_in_row;

    array_push($output_lines, "<tr>");
    array_push($output_lines, "<th rowspan='$rowspan'><p class='text-info'>$name</p></th>");
    for($i = 0; $i < count($linkList); $i++) {
        $url = $linkList[$i];
        if ($i % $cell_in_row == 0 && $i != 0) {
            array_push($output_lines, "<tr>"); 
            array_push($output_lines, "<td>$url</td>");
        } elseif ($i % $cell_in_row == $cell_in_row - 1) {
            array_push($output_lines, "<td>$url</td>");
            array_push($output_lines, "</tr>");
        } else {
            array_push($output_lines, "<td>$url</td>");
        }
    }
}
array_push($output_lines, "</table>");
file_put_contents("../readme.tts2", implode("\n", $output_lines));

echo implode("\n", $html_lines);
echo '<script src="./js-base/jmenu-apycom/menu.js"></script>';
echo '<script src="../js/nav_menu.php.js"></script>';

?>
