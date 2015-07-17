<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>
</head>

<body>
<h3>点击链接生成</h3>
<form action="" method="POST" width = 100%>
    <p>AdItem:</p>
    <p>广告商ID: <input type="text" name="sid" /> </p>
    <p>广告商landing page: <input type="text" name="landurl"></p>
    <input type="submit" value="Submit" />
</form>
<?php
if($_POST["sid"] && $_POST["landurl"]) {
   $sid=$_POST["sid"];
   $landUrl=$_POST["landurl"];
   echo "<p>结果：</p>";
   echo "<a href='http://"; 
   $execStr="cd /disk2/qatest/lamp/appache/htdocs/m_teamTools/m_adTools/eadTestToolBox;sh /disk2/qatest/lamp/appache/htdocs/m_teamTools/m_adTools/eadTestToolBox/bin/run.sh CreateClickUrl ".$sid." ".$landUrl;
   $ans=system($execStr);
   echo "' target=_blank >http://".$ans."</a>";
}
?>
</body>
</html>
