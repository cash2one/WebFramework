<html>

<head>
    <meta charset="utf-8"/>

    <style>
        a {color: blue; text-decoration: none}
        a:hover {text-decoration: underline}
    </style>

    <?php
        include("../../util/dbUtil.php");

        $set_type = $_POST["set_type"];
        if ($set_type == "type_name_map") {
            set_name_map();
        } 

        function set_name_map() {
            global $_POST;
            foreach ($_POST as $prodName => $tableName) {
                $tableName = trim($tableName);
                if ($prodName == "set_type" || $prodName == "submit") continue;

                echo "<br>";
                if ($tableName == "") {
                    echo "提示：产品($prodName)的表名为空";
                    continue;
                }

                if (preg_match("/$\w+$/", $tableName) === false) {
                    echo "错误：产品($prodName)的表名($tableName)只能由字符组成！";
                    break;
                }

                if (strlen($tableName) == 20) {
                    echo "错误：产品($prodName)的表名($tableName)长度不能超过20！";
                    break;
                }

                dbUtil(SetProdNameMap, $prodName, $tableName);
                $retArray = get_db_result();
                if ($retArray[0] == 1) {
                    echo $retArray[1];
                    break;
                } else {
                    echo $retArray[1];
                }
            }

            echo " <a href='../../../index.php'>返回</a>";
        }
    ?>
</head>

<body>
</body>

</html>
