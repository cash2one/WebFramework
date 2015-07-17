<html>
<head>
    <style>
        table {width: 100%}
        th {width: 90px}
        a {text-decoration: none}
        a:hover {text-decoration: underline}
        td input {width: 100%}
        td textarea {width: 100%}
        td {padding: 10px;}

        td pre {
            white-space: -moz-pre-wrap; /* Mozilla, supported since 1999 */
            white-space: -pre-wrap; /* Opera 4 - 6 */
            white-space: -o-pre-wrap; /* Opera 7 */
            white-space: pre-wrap; /* CSS3 - Text module (Candidate Recommendation) http://www.w3.org/TR/css3-text/#white-space */
            word-wrap: break-word; /* IE 5.5+ */
        }
    </style>
    <script src="../../../../js-base/jquery.min.js"></script>

    <?php
        include("dbUtil.php");
        $cateList = dbUtil(ReadCategory); 
        echo "<script>var cateListOpStr = \"<option>" . implode("</option><option>", $cateList)  . "</option>\";</script>";
    ?>
    
</head>

<body>
<a href="#" id="enlarge" title="放大正文字体">放大</a>
<a href="#" id="minify" title="缩小正文字体">缩小</a>
<?php

header("Content-type: text/html; charset=utf-8");

$id = $_GET["id"];
echo dbUtil(ReadSpecificContent, array($id));
?>

<script>
$(function() {
    var status = "";

    $("a[name='edit']").click(function(e) {
        if (status != "") {
            alert("警告：当前已经处于编辑状态!");
            return false;
        }
        
        status = "edit";
        var fieldName = $(this).parents("tr").attr('name');
        var content = "";
        if (fieldName == "desc") {
            content = $(this).parent().prev().children(1).html(); 
            $(this).parent().prev().html("<textarea rows='15'>" + content + "</textarea>");
            
        } else if(fieldName == "cateName") {
            content = $(this).parent().prev().html(); 
            $(this).parent().prev().html("<select>" + cateListOpStr + "</select>");

        } else {
            content = $(this).parent().prev().html(); 
            $(this).parent().prev().html("<input value='" + content + "'/>");
        }

        e.preventDefault();
    });

    $("a[name='save']").click(function(e) {
        if (status != "edit") {
            alert("警告：请先编辑!");
            return false;
        }

        var ret = confirm("确定保存？");
        if (ret != true) return false;
        
        status = "save";
        var id = $("table").attr("name");
        var fieldName = $(this).parents("tr").attr('name');
        var content = "";
        if (fieldName == "desc") {
            content = $(this).parent().prev().children(1).val(); 
            
        } else {
            content = $(this).parent().prev().children(1).val(); 
        }

        $.post("./updateInfo.php", {"id": id, "fieldName": fieldName, "value": content}, function(data) {
            location.reload(); 
        });

        e.preventDefault();
    });

    $("a#enlarge").click(function(e) {
        var content_td = $("table tr:eq(3) td:eq(0)");
        var cur_font_size = content_td.css("font-size");
        cur_font_size = parseInt(cur_font_size) + 2;
        content_td.css("font-size", cur_font_size + "px");

        var imgs = $("img");
        $.each(imgs, function(idx, img) {
            var width = $(img).css("width");
            width = parseInt(width) * 1.1;
            var height = $(img).css("height");
            height = parseInt(height) * 1.1;
            $(img).css({"width": width + "px", "height": height + "px"});
        });

        e.preventDefault();
    });

    $("a#minify").click(function(e) {
        var content_td = $("table tr:eq(3) td:eq(0)");
        var cur_font_size = content_td.css("font-size");
        cur_font_size = parseInt(cur_font_size) - 2;
        if (cur_font_size <= 1) cur_font_size = 1;
        content_td.css("font-size", cur_font_size + "px");

        var imgs = $("img");
        $.each(imgs, function(idx, img) {
            var width = $(img).css("width");
            width = parseInt(width) * 0.9;
            var height = $(img).css("height");
            height = parseInt(height) * 0.9;

            if (width > 100 && height > 100) {
                $(img).css({"width": width + "px", "height": height + "px"});
            }
        });

        e.preventDefault();
    });
});
</script>
</body>
</html>
