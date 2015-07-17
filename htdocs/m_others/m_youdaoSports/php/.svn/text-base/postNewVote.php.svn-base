<?php

$title   = $_POST["title"];
$content = $_POST["content"];
$author  = $_POST["author"];
$passwd  = $_POST["password"];

try{
    $mongo = new MongoClient('mongodb://nb403:27127/admin:admin');
    $db = $mongo->youdaoSports;
    $collection = $db->votes;
    $collection->insert(array("time" => time(), "title" => $title, "content" => $content, "author" => $author, "passwd" => md5($passwd), "users" => array(), "deleted" => false, "finish" => false));
    $mongo->close();
} catch(MongoConnectionException $e) {
    //handle connection error
    die($e->getMessage());
}
