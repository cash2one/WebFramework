<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="./css/index.php.css" />
<link rel="stylesheet" type="text/css" href="../../js-base/jqplot/jquery.jqplot.css" />
<link rel="stylesheet" href="../../css-base/jquery-ui.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="./js/plupload-2.0.0/js/jquery.plupload.queue/css/jquery.plupload.queue.css" type="text/css" media="all" />
<link rel="stylesheet" type="text/css" href="../../js-base/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="../../js-base/easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="../../js-base/easyui/demo/demo.css">

<script src="../../js-base/jquery.min.js"></script>
<script src="../../js-base/easyui/jquery.easyui.min.js"></script>
<script src="../../js-base/jquery-ui.min.js"></script>
<script src="../../js-base/json.min.js"></script>
<script src="./js/plupload-2.1.1/js/plupload.full.min.js"></script>
<script src="./js/plupload-2.1.1/js/jquery.plupload.queue/jquery.plupload.queue.min.js"></script>

<script type="text/javascript" src="../../js-base/jqplot/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="../../js-base/jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="../../js-base/jqplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="../../js-base/jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="../../js-base/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="../../js-base/jqplot/plugins/jqplot.barRenderer.min.js"></script>
</head>

<body> 
<h3>测试组电子书平台</h3>

<div id="tabs">
  <ul>
    <li><a href="#book-list">图书列表</a></li>
    <li><a href="#upload">上传</a></li>
    <li><a href="#rank-list">排行榜</a></li>
  </ul>

  <div id="book-list" style="height:80%;">
    <p>
        Pagination on 
        <select id="pagination"> 
            <option>bottom</option>
            <option>top</option>
            <option>both</option>
        </select>

        &nbsp;
        查找: 
        <input id="filter_name" />
    </p>
    
    <table id="tt" class="easyui-datagrid" style="width:1060px;height:590px"
            url="./php/read_books.php"
            title="Load Data" iconCls="icon-save"
            sortName="book_ctime" sortOrder="desc"
            data-options="singleSelect:true" pagesize=20
            rownumbers="true" pagination="true">
        <thead>
            <tr>
                <th field="book_name" width="500" sortable="true">书名</th>
                <th field="book_size" width="80">大小</th>
                <th field="book_owner" width="80" align="center" sortable="true">上传者</th>
                <th field="book_ctime" width="170" align="center" sortable="true">上传时间</th>
                <th field="douban" width="80" align="center">豆瓣书评</th>
                <th field="operate" width="100" align="center">操作</th>
            </tr>
        </thead>
    </table>

    <br>
    <b>书名: </b><label id="book_name"></label>
  </div>

  <div id="upload" style="height:80%;">
    <form method="post" action="./js/plupload-2.0.0/examples/dump.php">
        <div id="uploader">
            <p>You browser doesn't have Flash, Silverlight, Gears, BrowserPlus or HTML5 support.</p>
        </div>
    </form>
  </div>

  <div id="rank-list">
    <div id="rank-chart" style="height:800px; width:95%;"></div>
  </div>
</div>
  <div id="book-list" style="height:80%;">

<script src="./js/index.php.js"></script>
</body>
</html>
