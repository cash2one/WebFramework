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

$user = $_GET["user"];
$id   = $_GET["id"];

$users = Array();
$lines = file("../data/members.txt");
$userCount = count($lines);
foreach ($lines as $line) {
    $line = trim($line);
    list($name, $mail) = explode(":", $line); 
    array_push($users, $name);
}

if (! in_array($user, $users)) {
    echo "无效的用户($user)";
    return;
}

try {
    $mongo = new MongoClient('mongodb://nb403:27127/admin:admin');
    $db = $mongo->youdaoSports;
    $collection = $db->votes;
    $doc = $collection->update(array("_id" => new MongoId($id)), array('$addToSet' => array("users" => $user)));
    $mongo->close();
} catch(MongoConnectionException $e) {
    die($e->getMessage());
    return;
}
?>

<pre>
<p>
你的投票成功，谢谢你的支持!
<a href="../index.php">返回</a>
</p>
</pre>
