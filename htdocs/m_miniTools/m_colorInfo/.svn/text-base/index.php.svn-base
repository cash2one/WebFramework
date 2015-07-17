<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>

<style>
    td.selected {border: solid 5px};
</style>
</head>

<body>
<h3 style="display:inline">颜色查看器</h3>
背景色：<span id="back"></span>
前景色：<span id="front"></span>
<a style='text-decoration: none' href="" id="set">设置</a>
<br>
<br>

<table border='1'>
<?php
    include("./color.php");
    $temp_arr = array();
    for ($i = 0; $i < count($color_list); $i++) {
        list($color, $cName, $eName) = $color_list[$i];
        array_push($temp_arr, "<td data-color='$color'><div name='head' style='background:$color;height:60px'></div><div name='content'>$cName($eName)<br>$color</div></td>");
    }

    $colNumInRow = 7;
    $row_id = 0;
    while (true) {
        echo "<tr>" . implode("", array_slice($temp_arr, $row_id * $colNumInRow, $colNumInRow)) . "</tr>", "\n";
        if ($row_id * $colNumInRow > count($color_list)) break;
        $row_id ++;
    }
?>
</table>

<script>
$(function() {
    var _back_color = null; 
    var _front_color = null;

    $("td div[name='head']").click(function(e) {
        _back_color = $(this).parent().data("color");
        set_backcolor();
    });

    $("td div[name='content']").click(function(e) {
        _front_color = $(this).parent().data("color");
        set_frontcolor();
    });

    function set_backcolor() {
        $("span#back").html(_back_color);
        $("td div").css("background", _back_color);
        $("td").each(function() {
            $("div", this).css("color", $(this).data('color'));
        });
    }

    function set_frontcolor() {
        $("span#front").html(_front_color);
        $("td div").css("color", _front_color);
        $("td").each(function() {
            $("div", this).css("background", $(this).data('color'));
        });
    }

    $("a#set").click(function(e) {
        if (_back_color == null || _front_color == null) return false;
        $("table td div").css("background", $("span#back").html()).css("color", $("span#front").html()); 
        e.preventDefault();
    });
});
</script>

</body>
</html>
