<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="./css/index.php.css" />
<link rel="stylesheet" type="text/css" href="./js/jqplot/jquery.jqplot.css" />
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="../../../css-base/grid.css" type="text/css" media="all" />
<link rel="stylesheet" href="./js/DataTables/media/css/demo_table.css" type="text/css" media="all" />
<link rel="stylesheet" href="./js/DataTables/media/css/demo_page.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>
<script src="./js/DataTables/media/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="./js/jqplot/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="./js/jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="./js/jqplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="./js/jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js"></script>
<script type="text/javascript" src="./js/jqplot/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="./js/jqplot/plugins/jqplot.barRenderer.min.js"></script>

</head>

<body>
<h3>Selenium兴趣组论坛</h3> -- Selenium知识、经验的交流和分享，让测试组的Web测试自动化茁壮成长。 <a target=_blank href="https://dev.corp.youdao.com/outfoxwiki/Test/selenium">Wiki首页</a>

<br>
<br>

<div id="tabs">
  <ul>
    <li><a href="#tables-title-list">分享列表</a></li>
    <li><a href="#tabs-rank-list">排行榜</a></li>
    <li><a href="#tabs-trash">回收站</a></li>
  </ul>
  <div id="tables-title-list">
    <div id="dt_example">
    <table cellpadding="0" cellspacing="0" border="0" class="display" id="example">
      <thead>
        <tr>
            <th width="55%">主题</th>
            <th width="10%">作者</th>
            <th width="15%">创建时间</th>
            <th width="20%">操作</th>
        </tr>
      </thead>
        
      <tbody>
      </tbody>

      <tfoot>
        <tr>
            <th>主题</th>
            <th>作者</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
      </tfoot>
    </table>
    </div>

  </div>

  <div id="tabs-rank-list" style="height:70%;">
    <div id="rank-chart" style="height:1000%; width:95%;"></div>
  </div>

  <div id="tabs-trash">
    <table id="recycleTable">
      <thead> 
        <tr style="background-color:Gainsboro">
           <th style="display:none;">id</th>
           <th style="width:82%">主题</th> 
           <th>作者</th> 
           <th>创建时间</th> 
           <th>操作</th> 
        </tr>   
      </thead>
      <tbody> 
        <tr style="background-color:Gainsboro;display:none" class="titleRow">
           <td style="display:none">sample</td>
           <td></td>
           <td></td>
           <td></td>
           <td><a class="restore">恢复</a></td>
        </tr>   
        <tr class="contentRow" style="display:none">
           <td colspan="5">正文</td> 
        </tr>   
     </tbody>
   </table>
  </div>
</div>

<div id="dialog-form" title="编辑分享">
  <p class="validateTips">所有域都需要填写。</p>
 
  <form>
  <fieldset>
    <label for="title">标题</label>
    <input type="text" name="title" id="title" class="text ui-widget-content ui-corner-all" />
    <label for="content">内容</label>
    <textarea rows=13 name="content" id="content" value="" class="text ui-widget-content ui-corner-all"></textarea>
    <label name='for_edit' for="versions">选择历史版本</label>
    <select name='for_edit' name="versions" id="versions" class="text ui-widget-content ui-corner-all"></select>
    <label for="author">作者</label>
    <input type="text" name="author" id="author" class="text ui-widget-content ui-corner-all" />
    <div name="for_new">
        <input type="checkbox" name="email" id="email" class="ui-widget-content ui-corner-all" checked /> 给兴趣组发邮件
    </div>
  </fieldset>
  </form>
</div>

<script src="./js/index.php.js"></script>
</body>
</html>
