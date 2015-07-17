<?php

date_default_timezone_set('Asia/Shanghai'); 
define("TEMPLATE_FILE", "../conf/svn_diff.template");
define("LOG_FILE", "../log/log.txt");

$ldap    = $_POST["ldap"];
$passwd  = $_POST["passwd"];
$code_v1 = $_POST["code1"];
$code_v2 = $_POST["code2"];
$skip_test_files = $_POST["skip_test_files"];
$show_diff_detail = $_POST["show_diff_detail"];

### dollar sign is key word for expect script
$passwd = str_replace('$', '\\$', $passwd);

/*
$ldap    = "zhangpei";
$passwd  = "";
$code_v1 = "https://dev.corp.youdao.com/svn/outfox/products/ad/adstat/branches/mgen-2.0.1@368231";
$code_v2 = "https://dev.corp.youdao.com/svn/outfox/products/ad/adstat/branches/mgen-2.0.1@405326";
$skip_test_files = "1";
*/

// In case someone like to use https://xxx:34343
$code_v1 = preg_replace("/:(\d+)$/", "@\\1", $code_v1);
$code_v2 = preg_replace("/:(\d+)$/", "@\\1", $code_v2);

// save user query log
function save_log($ldap, $code_v1, $code_v2) {
    $line = "<tr><td>" . strftime("%Y-%m-%d %H:%M:%S", time()) . "</td><td>" . $ldap . "</td><td>" . $code_v1 . "</td><td>" . $code_v2 . '</td></tr>';
    file_put_contents(LOG_FILE, "$line\n", FILE_APPEND);
}

// build expect script from template by filling some info
function get_diff_lines($ldap, $passwd, $code_v1, $code_v2) {
    $output_file = "./svn_diff.exp";
    
    $content = file_get_contents(TEMPLATE_FILE);
    $content = str_replace('$current_file$', $output_file, $content);
    $content = str_replace('$svn_old$', $code_v1, $content);
    $content = str_replace('$svn_new$', $code_v2, $content);
    $content = str_replace('$user_name$', $ldap, $content);
    $content = str_replace('$password$', $passwd, $content);

    file_put_contents($output_file, $content);

    $cmd = "chmod 700 $output_file; $output_file";
    exec($cmd, $lines, $ret);
    if ($ret != 0) {
        echo "Error: Failed when exec expect script!";
        exit(1);
    }

    return $lines;
}

// remove interactive lines
function get_valid_diff_lines($lines) {
    $ret_lines = Array();
    $start = false;

    foreach ($lines as $line) {
        if (substr($line, 0, 7) == "Index: ") {
            $start = true;

        } else if ($line == "-----------------------------------------------------------------------") {
            $start = false;
        }

        if (strstr($line, "was not found in the repository at revision")) {
            echo "<b><font color='red'>Error: " . $line . "</font></b>";
            exit(1);
        }

        if ($start == true) {
            array_push($ret_lines, $line);
        }
    }

    return $ret_lines;
}

// get diff lines, push into objects
function get_diff($lines) {
    $diffObjList = Array();
    $max_idx = -1;
    $add_lines_cnt = 0;
    $del_lines_cnt = 0;
    $c1_index = 0;
    $c2_index = 0;

    // remove lines NOT belong to diff result
    $lines = get_valid_diff_lines($lines);
    if (count($lines) == 0) {
        echo "<b><font color='blue'>Info: No Diff</font></b>";
        exit(1);
    }

    array_push($lines, "_EOF_"); // add a flag line

    foreach ($lines as $line) {
        // if (strstr($line, "No newline at end of file")) continue;

        $line03 = substr($line, 0, 3);
        $line04 = substr($line, 0, 4);
        $line05 = substr($line, 0, 5);
        $line07 = substr($line, 0, 7);

        // make diff count the same
        if ($line07 == "Index: " || $line05 == "_EOF_" || $line03 == "@@ ") {
            $delta = $add_lines_cnt - $del_lines_cnt;
            if ($delta > 0) {
                for ($i = 0; $i < $delta; $i++) {
                    if ($del_lines_cnt == 0) {
                        array_push($diffObjList[$max_idx]["c1_list"], "__add__");
                    } else {
                        array_push($diffObjList[$max_idx]["c1_list"], "__update__");
                    }
                    array_push($diffObjList[$max_idx]["c1_idx_list"], "");
                } 
            } else {
                for ($i = 0; $i < abs($delta); $i++) {
                    if ($add_lines_cnt == 0) {
                        array_push($diffObjList[$max_idx]["c2_list"], "__del__");
                    } else {
                        array_push($diffObjList[$max_idx]["c2_list"], "__update__");
                    }
                    array_push($diffObjList[$max_idx]["c2_idx_list"], "");
                } 
            }

            $add_lines_cnt = 0;
            $del_lines_cnt = 0;
        }

        // check for each condition
        if ($line07 == "Index: ") {
            $diffObj = Array(
                "file" => substr($line, 7),
                "ver1" => null, # svn version
                "ver2" => null, # svn version
                "c1_list" => Array(),
                "c2_list" => Array(),
                "c1_idx_list" => Array(),
                "c2_idx_list" => Array(),
            );
            array_push($diffObjList, $diffObj);
            $max_idx ++;

        } elseif ($line04 == "--- ") {
            // get version for old file
            preg_match("/.*\(revision (\d+)\)/", $line, $matches);
            $diffObjList[$max_idx]["ver1"] = $matches[1];

        } elseif ($line04 == "+++ ") {
            // get version for new file
            preg_match("/.*\(revision (\d+)\)/", $line, $matches);
            $diffObjList[$max_idx]["ver2"] = $matches[1];

        } else if (substr($line, 0, 1) == "-") {
            // lines removed
            array_push($diffObjList[$max_idx]["c1_list"], substr($line, 1));
            array_push($diffObjList[$max_idx]["c1_idx_list"], $c1_index);
            $del_lines_cnt ++;
            $c1_index ++;
                 
        } else if (substr($line, 0, 1) == "+") {
            // lines added
            array_push($diffObjList[$max_idx]["c2_list"], substr($line, 1));
            $add_lines_cnt ++;

            array_push($diffObjList[$max_idx]["c2_idx_list"], $c2_index);
            $c2_index ++;

        } else if($line03 == "===") {
            // find split line "==================================================================="
            // just need to do nothing

        } else if ($line03 == "@@ ") {
            // find line no info, like @@ -1,26 +0,0 @@
            preg_match("/^@@ -(\d+),(\d+) \+(\d+),(\d+) @@$/", $line, $matches) or
            preg_match("/^@@ -(\d+),(\d+) \+(\d+) @@$/", $line, $matches) or
            preg_match("/^@@ -(\d+) \+(\d+) @@$/", $line, $matches2);
            if ($matches) { 
                $c1_index = $matches[1];
                $c2_index = $matches[3];
            } else {
                // find line no info, like @@ -0,0 +1 @@
                $c1_index = $matches2[1];
                $c2_index = $matches2[2];
            }
        } else if ($line05 == "_EOF_") {
            break;
                
        } else {

            $delta = $add_lines_cnt - $del_lines_cnt;
            if ($delta > 0) {
                for ($i = 0; $i < $delta; $i++) { 
                    if ($del_lines_cnt == 0) {
                        array_push($diffObjList[$max_idx]["c1_list"], "__add__");
                    } else {
                        array_push($diffObjList[$max_idx]["c1_list"], "__update__");
                    }
                    array_push($diffObjList[$max_idx]["c1_idx_list"], "");
                }       
            } else {
                for ($i = 0; $i < abs($delta); $i++) { 
                    if ($add_lines_cnt == 0) {
                        array_push($diffObjList[$max_idx]["c2_list"], "__del__");
                    } else {
                        array_push($diffObjList[$max_idx]["c2_list"], "__update__");
                    }
                    array_push($diffObjList[$max_idx]["c2_idx_list"], "");
                }       
            }  
            $add_lines_cnt = 0;
            $del_lines_cnt = 0;

            // find not updated lines
            array_push($diffObjList[$max_idx]["c1_list"], substr($line, 1));
            array_push($diffObjList[$max_idx]["c1_idx_list"], $c1_index);
            $c1_index ++;

            array_push($diffObjList[$max_idx]["c2_list"], substr($line, 1));
            array_push($diffObjList[$max_idx]["c2_idx_list"], $c2_index);
            $c2_index ++;
        }

    } # end of foreach

    return $diffObjList;
}

function output_html_result($diffObjList) {
    global $skip_test_files;
    global $show_diff_detail;
    global $code_v1, $code_v2;
    $code1_url = preg_replace("/@.*/", "", $code_v1);
    $code2_url = preg_replace("/@.*/", "", $code_v2);

    $url_html_lines = Array();
    $diff_content_lines = Array();

    // $temp_array = Array();
    
    $index = 0;
    foreach($diffObjList as $diffObj) {
        if ($skip_test_files == "1") {
            // don't return test files
            $filename = basename($diffObj["file"]);
            $lfilename = strtolower($filename);
            if (strstr($lfilename, ".java") && strstr($lfilename, "test")) {
                continue;
            }
        }

        $file = $diffObj["file"];
        $code1_ver = $diffObj["ver1"];
        $code2_ver = $diffObj["ver2"];
        $code1_lines = $diffObj["c1_list"];
        $code2_lines = $diffObj["c2_list"];
        $code1_idx_lines = $diffObj["c1_idx_list"];
        $code2_idx_lines = $diffObj["c2_idx_list"];
        $lines_count = count($code1_lines);

        if (count($code1_lines) != count($code2_lines)) {
            echo "Error: Count for diff lines NOT the same";
            exit(1);

        } else if (count($code1_idx_lines) != count($code2_idx_lines)) {
            echo "Error: Count for diff lines' index NOT the same";
            exit(1);
        }

        // build links html str
        array_push($url_html_lines, "<a href='#filename_$index'>" . ($index + 1) . "." . $file . "</a>\n<br>");

        // build diff content
        array_push($diff_content_lines, "<table class='diff_detail_tbl'>");
        if ($code1_url == $code2_url) {
            $file_path = $code1_url . "/" . $file;
            array_push($diff_content_lines, "<tr id='filename_$index'><th colspan=4 class='fileheader'>" . $file . " <a title='注意:只是大版本的路径' href='$file_path' target=_blank>code</a></th></tr>");

        } else {
            $file_path1 = $code1_url . "/" . $file;
            $file_path2 = $code2_url . "/" . $file;
            array_push($diff_content_lines, "<tr id='filename_$index'><th colspan=4 class='fileheader'>" . $file . " <a title='注意:只是大版本的路径' href='$file_path1' target=_blank>code1</a> <a title='注意:只是大版本的路径' href='$file_path2' target=_blank>code2</a></th></tr>");
        }

        if ($code1_ver == "0") {
            array_push($diff_content_lines, "<tr><th colspan=2 class='fileheader'></th><th class='fileheader' colspan=2>New File</th></tr>");

        } else {
            array_push($diff_content_lines, "<tr><th colspan=2 class='fileheader'>Revision " . $code1_ver . "</th><th colspan=2 class='fileheader'>New Change</th></tr>");
        }

        $old_class = "init";
        for ($i = 0; $i < $lines_count; $i++) {
            $c1_line = $code1_lines[$i];
            $c1_idx  = $code1_idx_lines[$i];
            $c2_line = $code2_lines[$i];
            $c2_idx  = $code2_idx_lines[$i];

            // array_push($temp_array, $c1_line . "|" . $c2_line);

            $c1_line = str_replace("&", "&amp;", $c1_line);
            $c1_line = str_replace("<", "&lt;", $c1_line);
            $c1_line = str_replace(">", "&gt;", $c1_line);

            $c2_line = str_replace("&", "&amp;", $c2_line);
            $c2_line = str_replace("<", "&lt;", $c2_line);
            $c2_line = str_replace(">", "&gt;", $c2_line);

            if ($c1_line == "__add__") {
                # new line added in ver2
                $c1_line = "";
                if ($old_class != "add") {
                    $old_class = "add";
                    array_push($diff_content_lines, "<tr class='add headline'><th class='index'>$c1_idx</th><td><pre><span>$c1_line</span></pre></td><th class='index'>$c2_idx</th><td><pre><span>$c2_line</span></pre></td></tr>");

                } else {
                    array_push($diff_content_lines, "<tr class='add'><th class='index'>$c1_idx</th><td><pre><span>$c1_line</span></pre></td><th class='index'>$c2_idx</th><td><pre><span>$c2_line</span></pre></td></tr>");
                }

            } else if ($c2_line == "__del__") {
                # new line deleted in ver2
                $c2_line = "";
                if ($old_class != "delete") {
                    $old_class = "delete";
                    array_push($diff_content_lines, "<tr class='delete headline'><th class='index'>$c1_idx</th><td><pre><span>$c1_line</span></pre></td><th class='index'>$c2_idx</th><td><pre><span>$c2_line</span></pre></td></tr>");

                } else {
                    array_push($diff_content_lines, "<tr class='delete'><th class='index'>$c1_idx</th><td><pre><span>$c1_line</span></pre></td><th class='index'>$c2_idx</th><td><pre><span>$c2_line</span></pre></td></tr>");
                }

            } else if(trim($c1_line) != trim($c2_line)) {
                if ($c1_line == "__update__") $c1_line = "";
                elseif ($c2_line == "__update__") $c2_line = "";

                if ($c1_line != "" && $c2_line != "") {
                    $c1_line2 = str_replace("\"", "\\\"", $c1_line);
                    $c2_line2 = str_replace("\"", "\\\"", $c2_line);
                    $cmd = "cd perl; perl diff.pl \"$c1_line2\" \"$c2_line2\"";
                    $lines2 = Array();
                    exec($cmd, $lines2, $ret);
                    if (count($lines2) == 1 && $show_diff_detail == "1") {
                        list($c1_line, $c2_line) = explode("", $lines2[0]);
                    }
                }

                # line updated in ver2
                if ($old_class != "update") {
                    $old_class = "update";
                    array_push($diff_content_lines, "<tr class='update headline'><th class='index'>$c1_idx</th><td><pre><span>$c1_line</span></pre></td><th class='index'>$c2_idx</th><td><pre><span>$c2_line</span></pre></td></tr>");

                } else {
                    array_push($diff_content_lines, "<tr class='update'><th class='index'>$c1_idx</th><td><pre><span>$c1_line</span></pre></td><th class='index'>$c2_idx</th><td><pre><span>$c2_line</span></pre></td></tr>");
                }

            } else {
                # no update for line
                if ($old_class != "") {
                    $old_class = "";
                    array_push($diff_content_lines, "<tr class='headline'><th class='index'>$c1_idx</th><td><pre><span>$c1_line</span></pre></td><th class='index'>$c2_idx</th><td><pre><span>$c2_line</span></pre></td></tr>");

                } else {
                    array_push($diff_content_lines, "<tr><th class='index'>$c1_idx</th><td><pre><span>$c1_line</span></pre></td><th class='index'>$c2_idx</th><td><pre><span>$c2_line</span></pre></td></tr>");
                }
            }
        }
        array_push($diff_content_lines, "</table>\n<br>");
    
        $index ++;
    }

    // file_put_contents("abc.txt", implode("\n", $temp_array));

    $url_html_str = implode("\n", $url_html_lines);
    $diff_content_str = implode("\n", $diff_content_lines);

    echo "<div id='filelist' class='content'>\n";
    echo $url_html_str . "\n";
    echo "</div>\n";
    echo "<br>\n";

    echo "<div id='diff_content'>\n";
    echo $diff_content_str. "\n";
    echo "</div>\n";
    echo "<br>\n";

    echo "<div id='filelist2' class='content'>\n";
    echo $url_html_str . "\n";
    echo "</div>\n";
    echo "<br>\n";
}

save_log($ldap, $code_v1, $code_v2);

$lines = get_diff_lines($ldap, $passwd, $code_v1, $code_v2);
$diffObjList = get_diff($lines);
output_html_result($diffObjList);
?>
