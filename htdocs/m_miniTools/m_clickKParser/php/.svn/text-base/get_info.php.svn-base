<?php

$url = $_GET["url"];
// $url = "http://d.clkservice.youdao.com/clk/request.s?k=uoUDmtFzs8eNcLhmGdY%2FTGLphd2JhCQhHJ5VJhnERbwW1ATzIpmKpG%2BdJV5AFja1oXpPzt%2FBBnoRappTs8Riy0VWKNgzi1vaOGH0f3Fw%2Bf2uNIMk7bAy3Yef81CrXmDfRGQOqVammt%2BBVvBhlqR5uvEuVk1Pm6UlpXwLsmvjXyIM37V7lHW7Qqm1F9Q813Ia2i96TbCXP6WSAEO0cyMR8gJ%2BD9oC4MWyiy8%2FC%2BT1l40E6%2FWMhtyaZirjpfeYE%2BRoMT%2FkFHgEQMfIbhLsC0a52VR7RmDnaxeAeptUyiYQwiN2a8guUNxsIK0xP3aElYMx18aPqEcXCaY4wICrlYgqr9fGj6hHFwmmOMCAq5WIKq%2FXxo%2BoRxcJpjjAgKuViCqvZEFbYcRrGvnpGcHLKCsGcw%3D%3D&d=http%3A%2F%2Fwww.u-edu.cn&s=16&cac_all=cac_a-1__cac_b-4179480__cac_c-66.9.66.9.66.9__cac_d-111.1__cac_e-0__cac_f-58";
$url = urldecode($url);

$k = "";
$s = "100"; # 100没有逻辑上的意义，主要是为了凑一个参数
$fields = preg_split("/&|\?/", $url);
foreach ($fields as $field) {

    $pos = strpos($field, '=');
    if ($pos == false) continue;
    list($key, $val) = explode("=", $field, 2);
    if ($key == "k") {
        $k = $val;
    } elseif ($key == "s") {
        $s = $val;
    }
}

$cmd = "cd ../click_code; ./run.sh '$k' '$s'";
$output = exec($cmd);
echo preg_replace("/(\w+)=([^,{]*)/", "<font color='red'><b>\$1=</b></font>\$2", $output);

preg_match("/syndId=(\d*)/", $output, $matches);
if ($matches) {
    $syndId = (int)$matches[1];
} else {
    $syndId = -1;
}

echo "<br>";
echo "<br>";

echo "<font color='red'>所属平台: </font>";
if ($syndId >= 5 && $syndId < 11) {
    echo "邮箱";

} elseif ($syndId == 16) {
    echo "线下直销";

} elseif ($syndId >= 10 && $syndId < 21) {
    echo "频道";

} elseif ($syndId >= 50 && $syndId < 101) {
    echo "词典";

} elseif ($syndId >= 101 && $syndId < 151) {
    echo "智选";

} elseif ($syndId >= 1000) {
    echo "联盟";

} else {
    echo "搜索";
}
