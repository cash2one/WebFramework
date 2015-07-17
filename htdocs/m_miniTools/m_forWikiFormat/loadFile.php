<?php

$filename = $_POST["filename"];
echo file_get_contents("./data/" . $filename);
