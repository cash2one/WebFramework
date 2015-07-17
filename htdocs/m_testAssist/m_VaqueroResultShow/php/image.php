<?php

$cmd = "/home/zhangpei/tts/download 'http://vaquero.corp.youdao.com//image?type=img&product=ead-click&name=nc107&drawname=ead.click.status.proportion&period=hour&cubtype=ead-click_click&width=800&height=600'";
system($cmd . " > abc");

$img = imagecreatefromgif("abc");
$x = imagesx($img);
$y = imagesy($img);

$img_container = imagecreate($x * 2 + 10, $y);
imagecopy($img_container, $img, 0, 0, 0, 0, $x, $y);
imagecopymerge($img_container, $img, $x + 10, 0, 0, 0, $x, $y, 100);
imagegif($img_container, "./abc2.gif");
imagedestroy($img_container);
imagedestroy($img);
