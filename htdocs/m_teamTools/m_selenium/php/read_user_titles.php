<?php
require_once("Post.class.php");
require_once("PostDB.class.php");

$iTotal = 100;
$iFilteredTotal = 50;

function get_query_result($get_array) {
    global $iTotal,$iFilteredTotal;

    $aColumns = array('title', 'author', 'createTime');

    // 分页
    $sLimit = ""; 
    if (isset($get_array['iDisplayStart']) && $get_array['iDisplayLength'] != '-1') {
        $sLimit = "LIMIT " . intval($get_array['iDisplayStart']) . ", " . intval($get_array['iDisplayLength']);
    }

    // 排序
    $sOrder = "";
    if (isset($get_array['iSortCol_0'])) {
        $sOrder = "ORDER BY  ";
        for ($i=0; $i<intval($get_array['iSortingCols']); $i++)
        {
            if (intval($get_array['iSortCol_' . $i]) < count($aColumns) and $get_array['bSortable_' . intval($get_array['iSortCol_' . $i])] == "true") {
                $sOrder .= "postInfo." . $aColumns[intval($get_array['iSortCol_' . $i])] . " " . ($get_array['sSortDir_' . $i]==='asc' ? 'asc' : 'desc') . ", ";
            }
        }
        
        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY")
        {
            $sOrder = "";
        }
    }
    // 过滤
    $sWhere = "";
    if (isset($get_array['sSearch']) && $get_array['sSearch'] != "") {
        $sWhere = "AND (";
        $fields = Array("title","author","type","tag","content");
        foreach($fields as $field)
        {
            $sWhere .= $field . " LIKE '%" . $get_array['sSearch'] . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);
        $sWhere .= ')';
    }
    /*

    // 按照某一列过滤
    for ($i=0; $i<count($aColumns); $i++) {
        if (isset($get_array['bSearchable_' . $i]) && $get_array['bSearchable_' . $i] == "true" && $get_array['sSearch_' . $i] != '') {
            if ($sWhere == "") {
                $sWhere = "WHERE ";
            }
            else {
                $sWhere .= " AND ";
            }
            $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($get_array['sSearch_' . $i]) . "%' ";
        }
    }
    */
    // 设置sql请求串
    $sQuery = "
        SELECT postIndex.postid AS postid,postIndex.historyid AS historyid,postInfo.title AS title,postInfo.author AS author,postInfo.type AS type,postInfo.tag AS tag,postInfo.content AS content,postInfo.createTime AS createTime FROM postIndex,postInfo WHERE postIndex.historyid = postInfo.historyid AND postIndex.deleted = 0 
        $sWhere
        $sOrder
        $sLimit
        ";
    $postDB = new postDB();
    $rResult = $postDB->execSQL($sQuery); 
    $sQuery = "
        SELECT count(postIndex.postid) AS count FROM postIndex,postInfo WHERE postIndex.historyid = postInfo.historyid AND postIndex.deleted = 0";
    $result = $postDB->execSQL($sQuery); 
    $iTotal = $result[0]["count"];
    $sQuery = "
        SELECT count(postIndex.postid) AS count FROM postIndex,postInfo WHERE postIndex.historyid = postInfo.historyid AND postIndex.deleted = 0
        $sWhere";
    $result = $postDB->execSQL($sQuery); 
    $iFilteredTotal = $result[0]["count"]; 
    $postDB->close();
    return $rResult;
}

function fatal_error($sErrorMessage = '') {
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
    die($sErrorMessage);
}

function get_data_array($result){
    $array = Array();
    foreach($result as $entry){
        $dataid = $entry["postid"];
        $histid = $entry["historyid"];
        $op = "<a href='' name='new'>添加</a> <a href='' name='delete'>删除</a> <a href='' name='edit'>编辑</a> <input type='hidden' data-id='$dataid' data-histid='$histid'/>";
        array_push($array,Array($entry["title"],$entry["author"],$entry["createTime"],$op));
    }
    return $array;
}

$result = get_query_result($_GET);
$dataArray = get_data_array($result);

$output = array(
    "sEcho" => intval($_GET['sEcho']),
    "iTotalRecords" => $iTotal,
    "iTotalDisplayRecords" => $iFilteredTotal,
    "aaData" => $dataArray,
);

echo json_encode($output);


