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

$id = $_GET["id"];

$nameList = array();
$lines = file("../data/members.txt");
$userCount = count($lines);
foreach ($lines as $line) {
    $line = trim($line);
    list($name, $mail) = explode(":", $line); 
    $nameList[$name] = 0;
}

try {
    $mongo = new MongoClient('mongodb://nb403:27127/admin:admin');
    $db = $mongo->youdaoSports;
    $collection = $db->votes;
    $doc = $collection->findOne(array("_id" => new MongoId($id)));
    foreach ($doc["users"] as $user) {
        $nameList[$user] = 1;
    }
    $mongo->close();
} catch(MongoConnectionException $e) {
    die($e->getMessage());
    return;
}
?>

<table border='1'>
<tr>
    <th>成员名</th>
    <th>支持</th>
    <th>反对</th>
</tr>
<?php
    foreach($nameList as $name => $val) {
        if ($val == 0) {
            echo "<tr><td>$name</td><td></td><td>y</td></tr>";
        } else {
            echo "<tr><td>$name</td><td>y</td><td></td></tr>";
        }        
    }
?>
</table>
