<?php

include("./sqlite_lib2.php");

$id = $_GET["id"];

$row = load_project($id);
echo "<pre>" . $row[0][2] . "</pre>";
