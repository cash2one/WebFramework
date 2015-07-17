<html>

<head>
    <meta charset="utf-8" />

    <script src="../../../js-base/jquery.min.js"></script>
    <script src="../../../js-base/jquery-ui.min.js"></script>
    <script src="../../../js-base/easyui/jquery.easyui.min.js"></script>
    <script src="../../../js-base/json.min.js"></script>

    <link rel="stylesheet" type="text/css" href="../../../js-base/easyui/themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="../../../js-base/easyui/themes/icon.css" />
    <link rel="stylesheet" type="text/css" href="../../../css-base/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="../css/result_pages/search.php.css" />

    <?php
        include(dirname(__FILE__) . "/../php/dbLib.php");

        function getValue($keyName, $defaultValue) {
            global $_GET;

            if (array_key_exists($keyName, $_GET))
                return $_GET[$keyName];

            return $defaultValue;
        }

        $searchStr = getValue("searchStr", "");
        $prodName = getValue("prodName", "");
        $version  = getValue("version", "");
        $commentType = getValue("type", "");

        $type_dict = array(
            "file_desc" => "文件备注",
            "test" => "测试点",
            "trap" => "坑",
            "study" => "学习点",
            "function" => "函数备注"
        );
    ?>
</head>

<body>

    <div>
        <?php
            if ($prodName == "" || $version == "" || $commentType == "") {
                echo "<h3>产品代码搜索结果</h3>", "\n";
                echo "Error: Request params error !";
                return;
            }

            echo "<h3>产品代码搜索结果 - $prodName</h3>", "\n";

            $codeCommentArr = dbUtil(LoadCodeCommentInfoTable, $prodName);

            $retArr = dbUtil(GetStatus);
            if ($retArr["ret"] == 1) {
                echo "无结果";
                return;
            }

            $search_words = preg_split("/[\s,]+/", trim($searchStr));
        ?>

        <?php
            foreach ($codeCommentArr as $subArr) {
                if ($version != "*" && $subArr["version"] != $version)
                    continue; 
                
                if ($commentType != "*" && $subArr["type"] != $commentType)
                    continue;

                $type = $type_dict[ $subArr["type"] ];
                $detailType = $subArr["detailType"];

                $content = htmlspecialchars($subArr["content"]);

                echo "<table width='80%'>", "\n";
                if ($searchStr == "") {
                    printf("<tr><td class='title'>%s - %s - %s - %s</td></tr>\n", $subArr["version"], $type, $detailType, implode(",", $subArr["commentors"]));
                    printf("<tr><td class='content'><pre>%s</pre></td></tr>\n", $content);
                    printf("<tr><td class='path'>文件路径: %s</td></tr>\n", $subArr["filePath"]);
                } else {
                    foreach ($search_words as $searchWord) {
                        if (stristr($subArr["content"], $searchWord) !== false) {
                            printf("<tr><td class='title'>%s - %s - %s - %s</td></tr>\n", $subArr["version"], $type, $detailType, implode(",", $subArr["commentors"]));
                            printf("<tr><td class='content'><pre>%s</pre></td></tr>\n", $content);
                            printf("<tr><td class='path'>文件路径: %s</td></tr>\n", $subArr["filePath"]);
                            break;
                        }
                    }
                }
                echo "</table>", "\n";
            }
        ?>
    </div>

    <script src="../js/result_pages/search.php.js"></script>
</body>
</html>
