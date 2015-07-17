<?php

$url_list_str = $_GET["url"];
$cnt_in_row = $_GET["cnt_in_row"] * 1;
// $url_list_str = "http://vaquero.corp.youdao.com//image?type=img&product=_eadm&name=nb292&drawname=eadm.mail.impr.elapse.response&cubtype=mail_impr&period=hour;http://vaquero.corp.youdao.com//image?type=img&product=_eadm&name=nb292&drawname=eadm.mail.impr.elapse.throughput&cubtype=mail_impr&period=hour;http://vaquero.corp.youdao.com//image?type=img&product=_eadm&name=nb292&drawname=eadm.mail.impr.res.response&cubtype=mail_impr&period=hour;http://vaquero.corp.youdao.com//image?type=img&product=_eadm&name=nb292&drawname=eadm.mail.impr.cpu.response&cubtype=mail_impr&period=hour";

// $url_list_str = "http://vaquero.corp.youdao.com//image?type=img&product=ead-click&name=nc107&drawname=ead.click.status.throughput&cubtype=click&period=hour;http://vaquero.corp.youdao.com//image?type=img&product=ead-click&name=nc107&drawname=ead.click.status.proportion&cubtype=click&period=hour;http://vaquero.corp.youdao.com//image?type=img&product=ead-click&name=nc107&drawname=ead.click.elapse.response&cubtype=click&period=hour;http://vaquero.corp.youdao.com//image?type=img&product=ead-click&name=nc107&drawname=ead.click.elapse.throughput&cubtype=click&period=hour";
// $cnt_in_row = 3;

$urls      = explode(";", $url_list_str);
$md5_value = md5($url_list_str);

$dir = "./temp_img_dir/$md5_value";
if (file_exists($dir) == false) {
    mkdir($dir);
}

$output_img_file = $dir . ".png";

$file_list = Array();
$tool = "/home/zhangpei/tts/download";
$max_width = 0;
$max_height = 0;
for ($i = 0; $i < count($urls); $i++) {
    $url = $urls[$i];
    $md5_url = md5($url);
    $img_path = "$dir/$md5_url";
    $cmd = "$tool '$url' > $img_path";
    system($cmd);

    $x = 0;
    $y = 0;
    set_image_size($img_path, $x, $y);
    if ($x > $max_width) $max_width = $x;
    if ($y > $max_height) $max_height = $y;
    array_push($file_list, Array($img_path, $x, $y));
}

$file_cnt = count($file_list);
$row_cnt = $file_cnt % $cnt_in_row == 0 ? $file_cnt / $cnt_in_row :  $file_cnt / $cnt_in_row + 1;
$row_cnt = (int)$row_cnt;
$img_container = imagecreate($max_width * $cnt_in_row, $max_height * $row_cnt);
imagecolorallocate($img_container, 255, 255, 255);

$y_idx = 0;
for ($i = 0; $i < count($file_list); $i++) {
    list($img_path, $x, $y) = $file_list[$i];
    $img = imagecreatefromgif($img_path);

    $x_idx = $i % $cnt_in_row;
    if ($x_idx == 0 && $i != 0) {
        $y_idx ++;
    } 

    imagecopy($img_container, $img, $x_idx * $max_width, $y_idx * $max_height, 0, 0, $x, $y);
    imagedestroy($img);
}

imagepng($img_container, $output_img_file);
imagedestroy($img_container);

echo "1:./php/" . $output_img_file;

# -------------------------------------------------------------
# function definitions area
# -------------------------------------------------------------
function set_image_size($image_path, &$x, &$y) {
    $img = @imagecreatefromgif($image_path) or die("0:错误,有无效的图片!");
    $x = imagesx($img);
    $y = imagesy($img);
    imagedestroy($img);
}
