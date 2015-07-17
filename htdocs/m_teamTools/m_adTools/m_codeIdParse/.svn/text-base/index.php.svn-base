<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>
</head>

<body>
<h3>codeId解析工具</h3>
<form action="" method="POST" width = 100%>
    <p>codeId: <input type="text" name="codeId"></p>
    <input type="submit" value="Submit" />
</form>
<?php
if($_POST["codeId"]) {
   $codeId=$_POST["codeId"];
   $execStr="cd /disk2/qatest/lamp/appache/htdocs/m_teamTools/m_adTools/eadTestToolBox;sh /disk2/qatest/lamp/appache/htdocs/m_teamTools/m_adTools/eadTestToolBox/bin/run.sh CodeIdParser ".$codeId;
   system($execStr);
}
?>
</body>
</html>
