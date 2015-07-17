<?php

date_default_timezone_set("PRC");

include("./sqlite_lib2.php");

$statusDict = Array(
    // 颜色列表：http://wenku.baidu.com/view/1fa9431ca300a6c30c229fc4.html
    "0" => Array("项目提出", "#ff99ff"),
    "1" => Array("需求草稿", "#cc33ff"),
    "2" => Array("需求确定", "#3366ff"),
    "3" => Array("项目进行", "#ff0000"),
    "4" => Array("项目取消", "#aaaaaa"),
    "5" => Array("项目暂停", "#00ffff"),
    "6" => Array("项目完成", "#00ff00"),
);

$sort  = $_GET["sort"];
$order = $_GET["order"];

$rows = load_all_projects($sort, $order);
$count = count($rows);

$retArr = Array(
    "total" => $count,
    "rows" => Array(),
);

foreach ($rows as $row) {
    list($id, $title, $create_time, $wiki, $home, $svn, $status, $creator, $members) = $row;
    if ($wiki != "")
        $wiki = "<a href='$wiki' target='_blank'>wiki</a>";
    if ($home != "")
        $home = "<a href='$home' target='_blank'>home</a>";
    if ($svn != "")
        $svn = "<a href='$svn' target='_blank'>svn</a>";
    if ($creator != "")
        $creator = "<a href='http://weekly.corp.youdao.com/address/information.php?UserName=$creator' target='_blank'>$creator<a/>";
    if ($members != "") {
        $mList = preg_split("/[^a-zA-Z]+/", $members);
        $tList = Array();
        foreach ($mList as $member) {
            array_push($tList, "<a href='http://weekly.corp.youdao.com/address/information.php?UserName=$member' target='_blank'>$member<a/>");
        }
        $members = implode(", ", $tList);
    }

    if (array_key_exists($status, $statusDict)) {
        $color   = $statusDict[$status][1];
        $status  = $statusDict[$status][0];
        $status = "<font color='$color'>$status</font>";
    }

    $create_time = date("Y-m-d H:i:s", $create_time);
    array_push($retArr["rows"], Array(
        "id" => $id,
        "title" => $title,
        "create_time" => $create_time,
        "wikipage" => $wiki,
        "accurl"   => $home,
        "svn"      => $svn,
        "status"   => $status,
        "username" => $creator,
        "members"  => $members,
    ));
}

echo json_encode($retArr);
