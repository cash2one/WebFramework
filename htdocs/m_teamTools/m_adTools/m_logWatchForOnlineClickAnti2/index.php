<html>
<head>
  <meta charset="utf-8" />
  <!-- link rel="stylesheet" type="text/css" href="./css/index.php.css" /-->
  <link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

  <script src="../../../js-base/jquery.min.js"></script>
  <script src="../../../js-base/jquery-ui.min.js"></script>
  <script src="../../../js-base/json.min.js"></script>

  <script>
  $(function() {
    $( "#tabs" ).tabs({
        // event: "mouseover",
    });
  });
  </script>
</head>

<body>
<h3>线上点击反作弊测试日志查看 (<a id="toOld" href='../m_logWatchForOnlineClickAnti/index.php'>老版本</a>)</h3>

<div id="tabs">
  <ul>
    <li><a href="#tabs-user-input">用户输入</a></li>
    <li><a href="#tabs-click-log">点击日志</a></li>
    <li><a href="#tabs-anti-log">反作弊日志</a></li>
    <li><a href="#tabs-user-log">用户日志</a></li>
  </ul>
  <div id="tabs-user-input">
  </div>
  <div id="tabs-click-log">
  </div>
  <div id="tabs-anti-log">
  </div>
  <div id="tabs-user-log">
  </div>
</div>
 
<script src="./js/index.php.js"></script>
</body>
</html>
