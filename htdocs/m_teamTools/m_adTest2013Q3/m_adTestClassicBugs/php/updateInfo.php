<?php

$id = $_POST["id"];
$fieldName = $_POST["fieldName"];
$value     = $_POST["value"];

include("dbUtil.php");
dbUtil(UpdateSpecificContent, array($id, $fieldName, $value));
echo json_encode($retArray);
