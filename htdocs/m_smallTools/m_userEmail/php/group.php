<?php

date_default_timezone_set('Asia/Shanghai');

$group_name = $_GET["group_name"];
if ($group_name == "") {
    $group_name = strftime("%Y%m%d%H%M%S", time());
}
$mail_list_str = stripslashes($_GET["url"]);
$cc = $_GET["cc"];
$url = $mail_list_str . "&cc=" . $cc;

$time_str = strftime("%Y-%m-%d %H:%M:%S", time());
$log_file = "../logs/group.txt";

file_put_contents($log_file, "{\"name\": \"$group_name\", \"url\": \"$url\"}\n", FILE_APPEND);

echo "Find me here: <a href='http://mail.iyoudao.net'>http://mail.iyoudao.net</a>";

echo "
<script>
    window.location.href = \"http://mail.iyoudao.net\";
</script>
";

?>
