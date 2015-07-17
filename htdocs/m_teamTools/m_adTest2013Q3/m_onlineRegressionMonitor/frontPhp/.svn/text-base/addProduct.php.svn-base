<html>
<head>
    <meta charset="utf-8"/>
    <style>
        a {text-decoration: none};
        a:hover {text-decoration: underline};
    </style>

    <?php
        $newProdName = "";
        if (array_key_exists("product", $_POST)) {
            $newProdName = $_POST["product"];
        }

        if ($newProdName != "") {
            try {
                $mongo = new MongoClient('mongodb://nb403:27127/admin:admin');
                $db = $mongo->onlineRegressionMonitor;
                $collection = $db->prodVersionDetail;
                $doc = $collection->findOne(array("prodName" => $newProdName));
                if ($doc != null) {
                    echo "错误：产品名($newProdName)已经存在! ";

                } else {
                    $collection->insert(array("time" => time(), "prodName" => $newProdName, "versions" => array(), "deleted" => false));
                    echo "产品名($newProdName)添加成功! ";
                }
                $mongo->close();
            } catch(MongoConnectionException $e) {
                die($e->getMessage());
                return; 
            }

            echo "<a href='../index.php'>返回</a>";
            return;
        }
    ?>
</head>

<body>
    <form method="post" action="./frontPhp/addProduct.php">
        <fieldset>
            <legend>添加新服务</legend>
            服务名称: <input type=text name="product" />
            <input type=submit id="submit" value="添加" />
        </fieldset>
    </form>

    <br>

    <table border='1'>
        <tr>
            <th>已添加服务列表</th>
        </tr>
        <?php
            try {
                $mongo = new MongoClient('mongodb://nb403:27127/admin:admin');
                $db = $mongo->onlineRegressionMonitor;
                $collection = $db->prodVersionDetail;
                $cursor = $collection->find();
                foreach ($cursor as $doc) {
                    echo "<tr><td>" . $doc["prodName"] . "</td></tr>", "\n";
                }
                $mongo->close();
            } catch(MongoConnectionException $e) {
                die($e->getMessage());
                return; 
            }
        ?>
    </table>

    <script>
        $("#submit").click(function(e) {
            var newProductName = $("input[name='product']").val();
            if (newProductName == "") {
                alert("错误：产品名不能为空");
                return false;
            }
        });
    </script>
</body>
</html>
