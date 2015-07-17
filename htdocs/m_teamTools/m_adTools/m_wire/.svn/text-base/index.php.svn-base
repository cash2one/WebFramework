<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>
</head>

<body>
<h3>bp``````bd```````bs```````impr</h3>
<form action="" method="POST" width = 100%>
    <table border="1">
    <tr>
    <td>
    <h5>bp(默认地址：qt104x.corp.youdao.com:8070)</h5>服务地址:<input type="text" name="bpPath" value="qt104:/disk1/test-dsp/bp/exec-nginx/"/> 
    <p>竞价请求连的bd: <input type="text" name="bdHost" value="qt104:33459"><input type="submit" name="bdforbp" value="连接"></p>
    <p>展示请求连的bd: <input type="text" name="imprHost" value="qt104:33459"><input type="submit" name="imprforbp" value="连接"></p>
    </td>
    <td>
    <h5>bd</h5>服务地址:<input type="text" name="bdPath" value="qt104:/disk1/test-dsp/bd/m1-2-4/"/> 
    <p>配置bs: <input type="text" name="bsHost" value="hs030:4444"><input type="submit" name="bsforbd" value="连接"></p>
    </td>
    </tr>
    </table>
</form>
<?php
if(!empty($_POST['bdforbp'])){
    if($_POST["bpPath"] && $_POST["bdHost"]) {
        $bp = $_POST["bpPath"];
        $bpArr = explode(":",$bp);
        $bpHost = $bpArr[0];
        $bpPath = $bpArr[1];
        $bdHost = $_POST["bdHost"];
        $execStr = "ssh ".$bpHost." ' sed -i -e \"N;N;/upstream dsp_cm/s/\\n.*\\n.*/\\n#modify server\\nserver ".$bdHost.";/\" ".$bpPath."conf/nginx.conf; sed -i -e \"N;N;/upstream.*_bd/s/\\n.*\\n.*/\\n#modify server\\nserver ".$bdHost.";/\" ".$bpPath."conf/nginx.conf;".$bpPath."/sbin/nginx -s reload'";
        echo $execStr;
        exec($execStr,$output);
        echo $output;
    }
}
if(!empty($_POST['bsforbd'])){
    if($_POST["bdPath"] && $_POST["bsHost"]) {
        $bd = $_POST["bdPath"];
        $bdArr = explode(":",$bd);
        $bdHost = $bdArr[0];
        $bdPath = $bdArr[1];
        $bsHost = $_POST["bsHost"];
        // sed -i -e "s/<hosts>.*<\/hosts>/<hosts>hs030:5555<\/hosts>/" conf/dispatchConfig.xml
        $execStr = "ssh ".$bdHost." ' sed -i -e \"s/<hosts>.*<\/hosts>/<hosts>".$bsHost."<\/hosts>/\" ".$bdPath."conf/dispatchConfig.xml; cd ".$bdPath." ;sh ./bin/reload.sh'";
        echo $execStr;
        exec($execStr,$output);
    }
}
if(!empty($_POST['imprforbp'])){
    if($_POST["bpPath"] && $_POST["imprHost"]) {
        $bp = $_POST["bpPath"];
        $bpArr = explode(":",$bp);
        $bpHost = $bpArr[0];
        $bpPath = $bpArr[1];
        $imprHost = $_POST["imprHost"];
        $execStr = "ssh ".$bpHost." ' sed -i -e \"N;N;/upstream.*_impr/s/\\n.*\\n.*/\\n#modify server\\nserver ".$imprHost.";/\" ".$bpPath."conf/nginx.conf; ".$bpPath."/sbin/nginx -s reload'";
        echo $execStr;
        exec($execStr,$output);
    }
}
?>
</body>
</html>
