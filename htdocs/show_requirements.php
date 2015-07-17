<html>

<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="./css/show_requirements.php.css" />
<link rel="stylesheet" href="./css-base/jquery-ui.min.css" type="text/css" media="all" />
<link rel="stylesheet" href="./js-base/easyui/themes/default/easyui.css" type="text/css" media="all" />
<link rel="stylesheet" href="./js-base/easyui/themes/icon.css" type="text/css" media="all" />
<link rel="stylesheet" href="./js-base/easyui/demo/demo.css" type="text/css" media="all" />

<script src="./js-base/jquery.min.js"></script>
<script src="./js-base/jquery-ui.min.js"></script>
<script src="./js-base/json.min.js"></script>
<script src="./js-base/easyui/jquery.easyui.min.js"></script>
<script src="./js-base/easyui/datagrid-detailview.js"></script>
</head>

<body>
<h2>TTS需求列表页</h2>

<table id="dg" class="easyui-datagrid" title="工具需求列表" style="width:1600px;height:800px"
        data-options="singleSelect:true, url:'./php/load_data.php', method:'get', sortName:'create_time', sortOrder:'desc'">
    <thead>
        <tr>
            <th data-options="field:'title',width:550,sortable:true">标题</th>
            <th data-options="field:'create_time',width:120,sortable:true">创建时间</th>
            <th data-options="field:'wikipage',width:80">Wiki地址</th>
            <th data-options="field:'accurl',width:80">项目地址</th>
            <th data-options="field:'svn',width:80">svn地址</th>
            <th data-options="field:'status',width:80,sortable:true">项目状态</th>
            <th data-options="field:'username',width:120">提出者</th>
            <th data-options="field:'members',width:400">项目成员</th>
        </tr>
    </thead>
</table>

<script src="./js/show_requirements.php.js"></script>
</body>

</html>
