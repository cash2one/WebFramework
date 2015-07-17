<?php

header("Content-type: text/html; charset=utf-8");

//refer to http://www.w3schools.com/php/php_file_upload.asp

$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);

if ((($_FILES["file"]["type"] == "image/gif")
    || ($_FILES["file"]["type"] == "image/jpeg")
    || ($_FILES["file"]["type"] == "image/jpg")
    || ($_FILES["file"]["type"] == "image/pjpeg")
    || ($_FILES["file"]["type"] == "image/x-png")
    || ($_FILES["file"]["type"] == "image/png"))
    && ($_FILES["file"]["size"] < 20000000)
    && in_array(strtolower($extension), $allowedExts)) {
    if ($_FILES["file"]["error"] > 0)
    {
        echo "Return Code: " . $_FILES["file"]["error"] . "<br>";

    } else {
        echo "Upload: " . $_FILES["file"]["name"] . "<br>";
        echo "Type: " . $_FILES["file"]["type"] . "<br>";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
        $targetDir = "../attachments/" . time();
        $targetPath = $targetDir . "/" . $_FILES["file"]["name"];

        if (file_exists($targetPath)) {
            echo $targetPath . " already exists. ";

        } else {
            mkdir ($targetDir);
            move_uploaded_file($_FILES["file"]["tmp_name"], $targetPath);
            echo "Stored in: " . $targetPath;
        }
    }

} else {
    echo "Invalid file";
}

echo "<br>";
echo "<a href='../index.php'>返回</a>";

?>
