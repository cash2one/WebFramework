$(function() {
    // SyntaxHighlighter.all();
    var $_root_td  = $("td#root_td");
    var _sep_str   = " &gt; ";
    var _old_value = "select...";
    var $_ul_obj = $("ul#dir_list");
    $_ul_obj.hide();

    var _cur_filename = "";
    var _cur_file_path = "";

    var _old_name = "";
    var _old_html = "";

    $("a#remember_me").hide();

    var phpFile = "./php/read_dir_name.php";
    $.get(phpFile, {"dir_name": _dir_name}, function(data) {
        var dataList = data.split(":");
        $_root_td.data("value_list", dataList);
        $_root_td.data("value", dataList[0]);
        $_root_td.data("dir", "../code_dir/" + _dir_name);
        $_root_td.html("路径：<a class='td_a' href=''>" + dataList[0] + '</a>');
    });

    $(document).on("click", "table#myTable tr td a", function(e) {
        if ($(this).attr("class") != "td_a") {
            return false;
        }

        var $cur_td = $(this).parent();
        _old_name = $(this).html();
        _old_html = $cur_td.html();

        $_ul_obj.html("");
        $.each($cur_td.data("value_list"), function(idx, val) {
            $_ul_obj.append("<li><a class='li_a' href=''>" + val + "</a></li>");
        });

        $_ul_obj.show();
        $cur_td.html($_ul_obj);

        e.preventDefault();
    });

    $(document).on("click", "ul#dir_list li a", function(e) {
        $("a#remember_me").hide();

        var val = $(this).html();

        var $cur_td = $_ul_obj.parent();
        $_ul_obj.hide();

        if (val == _old_name) {
            $cur_td.html(_old_html);
            return false;
        }

        $("div#pei").html("");
        $cur_td.nextAll().remove();

        if ($cur_td.attr("id") == "root_td") {
            var tdHtml = "路径：<a class='td_a' href=''>" + val + "</a>";
            var dir = "../code_dir/" + _dir_name + "/" + val;
            $cur_td.data("dir", dir).data("value", val).html(tdHtml);
        } else {
            var pre_dir = $cur_td.prev().data("dir");
            var dir     = pre_dir + "/" + val;
            var tdHtml  = " " + _sep_str + " <a class='td_a' href=''>" + val + "</a>";
            $cur_td.data("dir", dir).data("value", val).html(tdHtml);
        }

        if (val == "select...") {
            return false;
        }

        load_file_content($cur_td.data("dir"));
        e.preventDefault();
    });

    function load_file_content(file_path) {
        var phpFile = "./php/read_dir_name2.php";
        $.getJSON(phpFile, {"cur_path": file_path}, function(obj) {
            var type      = obj.type;
            var data      = obj.data;
            var content   = obj.content;
            var class_str = obj.class_str;

            if (type == "dir") {
                var $new_td  = $("<td></td>");
                var dataList = data.split(":");
                $new_td.data("value_list", dataList);
                $new_td.data("value", dataList[0]);
                $new_td.html( " " + _sep_str + " <a class='td_a' href=''>" + dataList[0] + "</a>");
                $("table#myTable tr").append($new_td);

                // $("a", $cur_td.nextSibling).trigger("click");

            } else if (type == "file") {
                // refer to http://stackoverflow.com/questions/1370738/jquery-load-issue/1374067#1374067
                $("div#pei").html("");
                $("<pre></pre>").addClass(class_str).html(content).appendTo("div#pei");
                SyntaxHighlighter.highlight();
                // $("div#pei").css("background-color", "#c5ffc4");

                _cur_file_path = file_path;
                _cur_filename  = _cur_file_path.split("/").pop();
                $("a#remember_me").show();
            }
        });
    }


    $("a#remember_me").click(function(e) {
        $("ul#filenames").append($("<li class='ui-state-default'>" + _cur_filename + "<a href=''>x</a></li>").data("file_path", _cur_file_path));
        e.preventDefault();
    });

    $(document).on("click", "ul#filenames li", function(e) {
        var file_path = $(this).data("file_path");
        load_file_content(file_path);
    });

    $(document).on("click", "ul#filenames li a", function(e) {
        e.preventDefault();
        $(this).parent().hide();
    });
});

window.onbeforeunload = function(){
    return '提示：离开前请确保数据已经保存完毕!';
}
