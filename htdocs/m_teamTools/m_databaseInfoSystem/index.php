<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" type="text/css" href="./css/index.php.css" />
  <link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

  <script src="../../../js-base/jquery.min.js"></script>
  <script src="../../../js-base/jquery-ui.min.js"></script>
  <script src="../../../js-base/json.min.js"></script>
  <script>
    $(function() {
        $( "#tabs" ).tabs({
            event: "mouseover",
            active: 2,
        });
    });
  </script>
</head>

<body>
<h2>数据库表信息查询工具</h2>
<br>
<br>

<div id="tabs">
  <ul>
    <li><a href="#tabs-query">查询</a></li>
    <li><a href="#tabs-add-prod-line">添加产品线</a></li>
    <li><a href="#tabs-add-project">添加服务/项目</a></li>
    <li><a href="#tabs-add-db-conn">添加数据库服务</a></li>
    <li><a href="#tabs-add-table">添加表</a></li>
  </ul>
  <div id="tabs-query">
  </div>

  <div id="tabs-add-prod-line">
  </div>

  <div id="tabs-add-project">
  </div>

  <div id="tabs-add-db-conn">
  </div>

  <div id="tabs-add-table">
  </div>
</div>
 
<script src="./js/index.php.js"></script>
</body>
</html>
