<?php

function get_tree($dir, &$ret_array) {
    foreach (glob($dir . "/*") as $file_path) {
        if (is_file($file_path)) {
            $filename = basename($file_path);
            if ($filename == "index.tts.php") {
                include($file_path);
                foreach ($treeIndex as $toolName => $subArray) {
                    if ($toolName == "title") continue;
                    array_push($ret_array, '<li data-options="attributes:{file_path:\'' . $file_path . '\', tool_name:\'' . $toolName . '\'}">');
                    array_push($ret_array, "<span>" . $subArray["name"] . "</span>");
                    array_push($ret_array, "</li>");
                }
            }
        } else if(file_exists($file_path . "/index.tts.php")) {
                include($file_path . "/index.tts.php");

                array_push($ret_array, '<li data-options="state:\'closed\'">');
                array_push($ret_array, "<span>" . $treeIndex["title"] . "</span>");
                array_push($ret_array, "<ul>");

                get_tree($file_path, $ret_array);

                array_push($ret_array, "</ul>");
                array_push($ret_array, "</li>");
        }
    }
}

?>
