<?php

date_default_timezone_set('PRC');
include("./dbUtil.php");

function getParam($keyName, $defValue) {
    global $_GET;
    if (array_key_exists($keyName, $_GET)) {
        return $_GET[$keyName];
    }
    return $defValue;
}

function getOpIssues(&$ticketList, &$noteList, &$resList, $startIdx, $cnt) {
    $retArr = array();
    $noteList2 = array();
    $resList2  = array();
    
    foreach ($noteList as $noteRow) {
        $ticketId = $noteRow["ticket_id"];
        if (!array_key_exists($ticketId, $noteList2)) {
            $noteList2[$ticketId] = array();
        }
        array_push($noteList2[$ticketId], $noteRow["title"] . "\n" . $noteRow["note"]);
    }

    foreach ($resList as $resRow) {
        $ticketId = $resRow["ticket_id"];
        if (!array_key_exists($ticketId, $resList2)) {
            $resList2[$ticketId] = array();
        }
        array_push($resList2[$ticketId], $resRow["response"]);
    }

    $maxIdx = $startIdx + $cnt;
    for ($idx = $startIdx; $idx < $maxIdx; $idx ++) {
        $tRow = $ticketList[$idx];
        $ticketId = $tRow["ticket_id"];
        $subject  = $tRow["subject"];
        $created  = $tRow["created"];
        $priority = $tRow["real_priority"];
        $noteStr  = "";
        $resStr   = "";
        
        if (array_key_exists($ticketId, $noteList2)) {
            $noteStr = implode("\n", $noteList2[$ticketId]);
        }
        if (array_key_exists($ticketId, $resList2)) {
            $resStr = implode("\n", $resList2[$ticketId]);
        }

        array_push($retArr, array($ticketId, $subject, $created, $priority, $noteStr . "\n" . $resStr));
    }

    return $retArr;
}

function getMaintainIssues(&$mlogsArr, &$mlogsCmtArr, $startIdx, $cnt) {
    $retArr = array();
    $cmtList = array();

    foreach ($mlogsCmtArr as $cmtRow) {
        $recId = $cmtRow["recordId"];
        if (!array_key_exists($recId, $cmtList)) {
            $cmtList[$recId] = array();
        }
        array_push($cmtList[$recId], $noteRow["title"] . "\n" . $noteRow["summary"]);
    }

    $maxIdx = $startIdx + $cnt;
    for ($idx = $startIdx; $idx < $maxIdx; $idx ++) {
        $rRow         = $mlogsArr[$idx];
        $recordId     = $rRow["Record_ID"];
        $reportedTime = $rRow["reportedTime"];
        $title        = $rRow["title"];
        $summary      = $rRow["summary"];
        $type         = $rRow["type"];
        $solution     = $rRow["solution"];
        $improvement  = $rRow["improvement"];
        $priority     = $rRow["priority"];
        $cmtStr       = "";

        if (array_key_exists($recordId, $cmtList)) {
            $cmtStr = implode("\n", $cmtList[$recordId]);
        }

        array_push($retArr, array($recordId, $title, $summary, $type, $priority, $solution . "\n" . $improvement . "\n" . $cmtStr));
    }
    return $retArr;
}

$startRowIdx = getParam("startRowIdx", 1);
$itemsInPage = getParam("itemsInPage", 50);
$startTime   = getParam("startTime", time() - 7 * 24 * 60 * 60);
$endTime     = getParam("endTime", time());
$issueType   = getParam("issueType", "all");

$retArr = array(
    "ret" => 0,
    "msg" => "",
    "content" => "",
    "itemsCount"  => -1,
    "startRowIdx" => $startRowIdx,
    "itemsInPage" => $itemsInPage,
    "startTime"   => $startTime,
    "endTime"     => $endTime,
    "issueType"   => $issueType,
);

list($ticketArr, $ticketNoteArr, $ticketResArr) = dbUtil(ReadOpIssues, "2011-01-17 09:49:52", "2011-02-17 09:49:52");
list($mlogsArr, $mlogsCmtArr) = dbUtil(ReadMaintainIssues, 1250851454611, 1299545507897);

if ($issueType == "all") {

} elseif ($issueType == "operation") {
    $itemsCount = count($ticketArr);

} elseif ($issueType == "maintain") {
    $itemsCount = count($mlogsArr);
}

#print_r($ticketArr);
#print_r($ticketNoteArr);
#print_r($ticketResArr);
#date('Y-m-d H:i:s', time());
#strptime($strf, $format);
