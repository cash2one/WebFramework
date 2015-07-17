<?php

include("./myUtil.php");
echo json_encode(getFileContent($_GET["file_path"]));
