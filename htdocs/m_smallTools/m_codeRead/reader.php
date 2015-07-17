<html>
<!-- refer to http://alexgorbatchev.com/SyntaxHighlighter/manual/installation.html -->
<head>
    <meta charset="utf-8">

    <link rel="stylesheet" href="./syntaxhighlighter_3.0.83/styles/shCore.css" type="text/css" />
    <link rel="stylesheet" href="./syntaxhighlighter_3.0.83/styles/shThemeDefault.css" type="text/css" />
    <link rel="stylesheet" href="../../css-base/grid.css" type="text/css" />
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />

    <link rel="stylesheet" href="./css/reader.php.css" type="text/css" />

    <script src="./syntaxhighlighter_3.0.83/scripts/shCore.js" type="text/javascript"></script>
    <script src="./syntaxhighlighter_3.0.83/scripts/shBrushJScript.js" type="text/javascript"></script>
    <script src="./syntaxhighlighter_3.0.83/scripts/shBrushJava.js" type="text/javascript"></script>
    <script src="./syntaxhighlighter_3.0.83/scripts/shBrushBash.js" type="text/javascript"></script>
    <script src="./syntaxhighlighter_3.0.83/scripts/shBrushCpp.js" type="text/javascript"></script>
    <script src="./syntaxhighlighter_3.0.83/scripts/shBrushPython.js" type="text/javascript"></script>
    <script src="./syntaxhighlighter_3.0.83/scripts/shBrushPhp.js" type="text/javascript"></script>
    <script src="./syntaxhighlighter_3.0.83/scripts/shBrushXml.js" type="text/javascript"></script>

    <script src="../../js-base/jquery.min.js" type="text/javascript"></script>
    <script src="../../js-base/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../js-base/json.min.js" type="text/javascript"></script>

    <style>
        #filenames { list-style-type: none; margin: 0; padding: 0; width: 90%; }
        #filenames li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 0.5em; font-size: 0.8em; height: 11px; }
        #filenames li a { float: right; }
    </style>
</head>

<body>
<div style="background:#c5ffc4">
    <table id="myTable">
    <tr>
        <td id="root_td">
        </td>
    </tr>
    </table>

    <a href="" id="remember_me">remember</a>
</div>

<hr>

<div class="yui3-g">
    <div class="yui3-u-1-5">
        <h4>选中的文件:</h4>
        <ul id="filenames"></ul>
    </div>

    <div id="pei" class="yui3-u-4-5" />
    </div>

</div>

<ul id="dir_list">
</ul>

<?php
    $dir_name = $_GET["dir"];
    echo "<script>_dir_name = '$dir_name'; </script>";
?>

<script charset="utf-8" src="./js/reader.php.js" type="text/javascript"></script>
</body>

</html>
