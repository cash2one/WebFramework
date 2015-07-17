<?php

include("./sqlite_lib2.php");

$id = $_GET["id"];
$row = remove_project($id);
