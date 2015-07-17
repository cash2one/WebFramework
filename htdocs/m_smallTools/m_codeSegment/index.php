<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./css/index.php.css" type="text/css" />
    <link rel="stylesheet" href="../m_webTechPages/js/jPages-master/css/jPages.css" type="text/css" />

    <script src="../../js-base/jquery.min.js" type="text/javascript"></script>
    <script src="../../js-base/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../js-base/json.min.js" type="text/javascript"></script>
    <script src="../m_webTechPages/js/jPages-master/js/jPages.min.js" type="text/javascript"></script>
</head>

<body> 
<h3>代码片段</h3>

选择语言:
<select id="lang_type"></select>
&nbsp;
<input type=text id="search_word" />
<input type=button id="search_btn" value=过滤 />
&nbsp;
<a href="" id="add_new">我也要分享</a>

<div class="holder"></div>
<ul id="show_items">
</ul>

<table id="add_table"> 
    <tr>
        <th>标题:</th>
        <td><input class='full_td' type=text id="title" /></td>
    </tr>
    <tr>
        <th>代码段:</th>
        <td><textarea class='full_td' id="code_param"></textarea></td>
    </tr>
    <tr>
        <th>语言:</th>
        <td id='language'></td>
    </tr>
    <tr>
        <th>添加语言:</th>
        <td><input class='full_td' type=text id="manual" /></td>
    </tr>
    <tr>
        <th></th>
        <td colspan='2'>
            <input class='full_td' type=button id="cont_btn" value="马上分享" />
        </td>
    </tr>
</table>

<script src="./js/index.php.js" type="text/javascript"></script>
</body>
</html>
