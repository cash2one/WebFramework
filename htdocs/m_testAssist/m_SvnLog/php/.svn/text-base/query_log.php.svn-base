<?php

date_default_timezone_set('Asia/Shanghai'); 
define("TEMPLATE_FILE", "../conf/svn_log.template");
define("LOG_FILE", "../log/log.txt");

$svn_url = $_POST["svn_url"];
$ldap    = $_POST["ldap"];
$passwd  = $_POST["passwd"];

// save user query log
function save_log($ldap, $code) {
    $row  = "<tr><td>" . strftime("%Y-%m-%d %H:%M:%S", time()) . "</td><td>" . $ldap . "</td><td>" . $code . '</td></tr>';
    file_put_contents(LOG_FILE, "$row\n", FILE_APPEND);
}

// remove interactive lines
function get_valid_diff_lines($lines) {
    $ret_lines = Array();
    $start = false;

    foreach ($lines as $line) {
        if (preg_match("/^r\d+/", $line)) {
            $start = true;
        }

        if (strstr($line, "path not found")) {
            echo "<tr><td colspan='5'><b><font color='red'>Error: " . $line . "</font></b></td></tr>";
            exit(1);
        }

        if ($start == true) {
            array_push($ret_lines, $line);
        }
    }

    return $ret_lines;
}

// build expect script from template by filling some info
function build_expect_file($ldap, $passwd, $code) {
    $output_file = "./svn_log.exp";
    
    $content = file_get_contents(TEMPLATE_FILE);
    $content = str_replace('$current_file$', $output_file, $content);
    $content = str_replace('$svn_url$', $code, $content);
    $content = str_replace('$user_name$', $ldap, $content);
    $content = str_replace('$password$', $passwd, $content);

    file_put_contents($output_file, $content);
    return $output_file;
}

// return lines
function output_svn_log($lines) {
    $version = null;
    $user = null;
    $time = null;
    $comment = null;

    $retArray = Array();
    foreach ($lines as $line) {
        $line1 = substr($line, 0, 1);
        $line5 = substr($line, 0, 5);

        if (preg_match("/r(\d+) \| (\w+) \| (\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})/", $line, $matches)) {
            // revision line
            $version = $matches[1];
            $user = $matches[2];
            $time = $matches[3];

        } else if ($line5 == "-----") {
            $tr_line = "<tr><td><input type=checkbox class='choice'></td><td class='version'>$version</td><td class='user'>$user</td><td class='time'>$time</td><td class='comment'><span><pre>$comment</span></pre></td></tr>";
            array_push($retArray, $tr_line);
            $comment = "";
        } else {
            $comment .= $line;
        }
    }
    
    echo implode("\n", $retArray);
}

save_log($ldap, $svn_url);

$expect_file = build_expect_file($ldap, $passwd, $svn_url);
$cmd = "chmod 700 $expect_file; $expect_file";
exec($cmd, $lines, $ret);
$lines = get_valid_diff_lines($lines);
output_svn_log($lines);
