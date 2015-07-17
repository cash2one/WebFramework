<html>

<head>
    <meta charset="utf-8"/>
<style>
    p { padding-left: 15px; padding-top: 5px; padding-bottom:5px; }
    th {text-align: left}
</style>
</head>

<body>
<?php

$req_php_file = $_POST["file_path"];
$tool_name    = $_POST["tool_name"];
/*
$req_php_file = "./click/index.tts.php";
$tool_name    = "buildClickReq.py";
*/
include("../" . $req_php_file);

?>

<pre>

<h4>说明</h4>
<p>
<?php echo dirname($req_php_file) . "/" . $tool_name, " -- ", $treeIndex[$tool_name]["title"], "\n" ?>
</p>

<h4>参数</h4>
<p>
<table>
<?php
    foreach ($treeIndex[$tool_name]["params"] as $key => $val) {
        echo "<tr><th>$key</th><td></td></tr>";
        echo "<tr><th></th><td>$val</td></tr>";
    }
?>
</table>
</p>

<h4>使用说明</h4>
<p>
<ul>
<?php
    foreach ($treeIndex[$tool_name]["usages"] as $val) {
        echo "<li>$val</li>";
    }
?>
</ul>
</p>

<h4>作者</h4>
<p>
<table>
<?php
    foreach ($treeIndex[$tool_name]["author"] as $key => $val) {
        echo "<tr><th>$key:</th><td>$val</td></tr>";
    }
?>
</table>
</p>

<h4>日志</h4>
<p>
<table>
<?php
    foreach ($treeIndex[$tool_name]["backlog"] as $log_line) {
        echo "<tr><td>$log_line</td></tr>", "\n";
    }
?>
</table>
</p>

</pre>

</body>
</html>
