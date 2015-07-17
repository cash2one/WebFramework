<html>
<head>
    <meta charset="utf-8"/>
<?php
    $val = "";
    if (array_key_exists("input", $_POST))
        $val = $_POST["input"]; 
?>
</head>
<body>
    <form method="POST">
        <?php
            echo "<input type=text name=input value='$val' /> <input type=submit value='获取' />";
        ?>
        <div>
        <?php
            if ($val != "") {
                echo "<b>'$val'的MD5值是：</b>", md5($val);
            }
        ?>
        </div>
    </form>
</body>
</html>
