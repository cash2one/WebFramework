<?php

date_default_timezone_set('Asia/Shanghai');

$mail_list_str = stripslashes($_GET["url"]);
$cc = $_GET["cc"];
$url = $mail_list_str . "&cc=" . $cc;

$time_str = strftime("%Y-%m-%d %H:%M:%S", time());
$log_file = "../logs/log.txt";

file_put_contents($log_file, "$time_str\t$url\n", FILE_APPEND);

echo "Need email client installed to work<br>Find me here: <a href='http://mail.iyoudao.net'>http://mail.iyoudao.net</a>";

echo "
<script>
    window.location.href = \"$url\";
</script>
";

?>
