<?php

define("ReadOpIssues", 1);
define("ReadMaintainIssues", 2);
define("DeleteIssue", 3);
define("CommentIssue", 4);

$dbArr = array(
    "status" => 0,
    "message" => "" 
);

$operationLink = null;
$maintainLink  = null;
$mongoDb       = null;

function set_db_status($status, $msg) {
    global $dbArr;
    $dbArr = array(
        "status" => $status,
        "message" => $msg
    );
}

function get_db_status($status, $msg) {
    global $dbArr;
    return $dbArr;
}

function getRetArr($sql_cmd, $linkRes) {
    $retArr = array();
    $result = mysql_query($sql_cmd, $linkRes);
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        array_push($retArr, $row);
    }
    return $retArr;
}

function readMaintainIssues($start_time, $end_time) {
    // MLOGS_RECORD: Record_ID, product, reportedTime, happened, serviceDown, reporter, title, summary, type, solution, improvement, priority
    // MLOGS_COMMENT: COMMENT_ID, recordId, username, title, summary
    // url: http://mlogs.corp.youdao.com/record?product=ead&rid=20

    global $maintainLink;
    mysql_query('SET NAMES UTF8', $maintainLink); // refer to http://bbs.csdn.net/topics/390099375
    
    $retArr = array();
    $sql_cmd = "select * from MLOGS_RECORD where product = 'ead'";
    if ($start_time != -1)
        $sql_cmd .= " and reportedTime >= $start_time";
    if ($end_time != -1)
        $sql_cmd .= " and reportedTime <= $end_time";

    $mlogsArr = getRetArr($sql_cmd, $maintainLink);
    $mlogsCmtArr = getRetArr("select * from MLOGS_COMMENT", $maintainLink);
    return array($mlogsArr, $mlogsCmtArr);
}

function readOpIssues($start_time, $end_time) {
    // ost_ticket: ticket_id, ticketID, dept_id, email, name, subject, pre_id, real_priority
    // ost_ticket_attachment: attach_id, ticket_id, ref_id, file_size, file_name, created
    //      http://eadticket.corp.youdao.com/attachment.php?id={attach_id}&ref=dfefb40130ec985acaf21d1d5c86734c
    // ost_ticket_note: note_id, ticket_id, title, note, created
    // ost_ticket_response: response_id, msg_id, ticket_id, staff_name, response, created
    // url: http://eadticket.corp.youdao.com/tickets.php?id=7690

    global $operationLink;
    mysql_query('SET NAMES UTF8', $operationLink);
    
    $sql_cmd = "select * from ost_ticket where 1 = 1";
    if ($start_time != -1)
        $sql_cmd .= " and created >= '$start_time'";
    if ($end_time != -1)
        $sql_cmd .= " and created <= '$end_time'";
    $ticketArr = getRetArr($sql_cmd, $operationLink);
    $ticketNoteArr = getRetArr("select * from ost_ticket_note", $operationLink);
    $ticketResArr  = getRetArr("select * from ost_ticket_response", $operationLink);
    return array($ticketArr, $ticketNoteArr, $ticketResArr);
}

function deleteIssue($id, $type) {
    global $mongoDb;    
    $collection = $mongoDb->deletedIssues;
    $collection->insert(array("id" => $id, "type" => $type));
}

function commentIssue($id, $type, $content) {
    global $mongoDb;    
    $collection = $mongoDb->issueComments;
    $collection->update(
            array("id" => $id, "type" => $type),
            array(
                '$set' => array("content" => $content)
            ),
            array('upsert' => true)
        );
}

function dbUtil() {
    global $operationLink, $maintainLink, $mongoDb;

    $args_arr = func_get_args();
    $type = $args_arr[0];
    
    try {

        if ($type == ReadOpIssues) {
            $operationLink = mysql_connect("tb004:3306", "ead", "ea89,d24");
            mysql_select_db("eadticket", $operationLink);

            list($start_time, $end_time) = array_slice($args_arr, 1);
            return readOpIssues($start_time, $end_time);

        } else if ($type == ReadMaintainIssues) {
            $maintainLink = mysql_connect("tb004:3306", "mlogs", "mlogs123outfox");
            mysql_select_db("sandbox", $maintainLink);

            list($start_time, $end_time) = array_slice($args_arr, 1);
            return readMaintainIssues($start_time, $end_time);

        } else if ($type == DeleteIssue || $type == CommentIssue) {
            $mongo = new MongoClient('mongodb://nb403:27127/admin:admin');
            $mongoDb = $mongo->issueList;

            if ($type == DeleteIssue) {
                list($id, $type) = array_slice($args_arr, 1);
                return deleteIssue($id, $type);

            } elseif ($type == CommentIssue) {
                list($id, $type, $content) = array_slice($args_arr, 1);
                return commentIssue($id, $type, $content);
            }

        } else {
            throw new Exception("Invalid type: $type");
        }

    } catch (Exception $e) {
        set_db_status(1, $e->getMessage());
        return false;
    }
}

/*
$retArr = dbUtil(ReadOpIssues, "2011-01-17 09:49:52", "2011-02-17 09:49:52");
$retArr = dbUtil(ReadMaintainIssues, 1250851454611, 1299545507897);
*/
