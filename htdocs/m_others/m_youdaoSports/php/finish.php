<html>
<head>
    <meta charset="utf8"/>
    <script src="../../../js-base/jquery.min.js" type="text/javascript"></script>
    <style>
        table tr th {width: 100px};
    </style>
</head>

<body>
<?php

$id     = $_GET["id"];
$passwd = $_GET["passwd"];
$md5pass = md5($passwd);

try {
    $mongo = new MongoClient('mongodb://nb403:27127/admin:admin');
    $db = $mongo->youdaoSports;
    $collection = $db->votes;
    $doc = $collection->update(array("_id" => new MongoId($id), "passwd" => $md5pass), array('$set' => array("finish" => true)));
    $mongo->close();
} catch(MongoConnectionException $e) {
    die($e->getMessage());
    return;
}
?>

<pre>
<p>
投票完成
<a href="../index.php">返回</a>
</p>
</pre>
