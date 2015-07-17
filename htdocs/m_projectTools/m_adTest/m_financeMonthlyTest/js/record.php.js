// global vers
var _cur_state_name = "";

// update cur state name
function update_cur_state_name(status_name) {
    if (_cur_state_name == "") {
        _cur_state_name = status_name;
    } else {
        _cur_state_name = _cur_state_name + "|" + status_name;
    }
}

// set cur state name
function set_cur_state_name(status_name) {
    _cur_state_name = status_name;
}
// triggered when document ready
$(function() {
    function load_all() {
        var php_file = "./php/load_status.php";
        $.getJSON(php_file, function(data) {
            var table1_str = data.table1;
            var table2_str = data.table2;
        
            $("tbody#input_tbody1").html(table1_str);
            $("tbody#input_tbody2").html(table2_str);
        });
    }

    // init stage
    load_all();
    $(".right").hide();

    $("a#show_right").click(function(e) {
        $(".left").hide();
        $(".right").show();

        e.preventDefault();
    });

    $("a#show_left").click(function(e) {
        $(".right").hide();
        $(".left").show();

        e.preventDefault();
    });

    // 备份数据库状态
    $(document).on("click", "table#input_table1 a.archive_status", function(e) {
        var new_status_name = prompt("新状态名:");
        if (new_status_name == "") {
            alert("状态名不能为空!");
            return false;
        }

        if (new_status_name == null) {
            return false;
        }

        var td_names = $("td.status_name");
        var is_dup = false;
        $.each(td_names, function(idx, td) {
            name = $(td).html();
            if (name == new_status_name) {
                is_dup = true; 
                alert("该状态名已经存在!");
                return false;
            }
        });
        if (is_dup == true) {
            return false;
        }

        update_cur_state_name(new_status_name)
        var php_file = "./php/add_new_status.php";
        $.post(php_file, {"status_name": _cur_state_name}, function(message) {
            load_all();
            if (message != "") {
                 alert(message);
            } else {
                $("span#cur_state").html(_cur_state_name);
            }
        });

        e.preventDefault();
    });

    // 设定数据库状态
    $(document).on("click", "a.set_status", function(e) {
        var $link = $(this);
        var php_file = "./php/set_status.php";
        set_cur_state_name($(this).parent().parent().children().first().html());
        $.post(php_file, {"name": _cur_state_name}, function(message) {
            if (message.indexOf("") != -1) {
                $("span#cur_state").html(_cur_state_name);
            }
            alert(message);
        });

        e.preventDefault();
    });

    // 删除本地的备份状态
    $(document).on("click", "a.del_status", function(e) {
        var php_file = "./php/del_status.php";
        var name     = $(this).parent().parent().children().first().html();
        var ret = confirm("确定删除备份状态 " + name);
        if (ret != true) {
            return false;
        }

        $.post(php_file, {"name": name}, function(message) {
            load_all();
            if (message != "") {
                 alert(message);
            }
        });

        e.preventDefault();
    });

    // 删除状态名
    $(document).on("click", "a.del_name", function(e) {
        var php_file = "./php/del_name.php";
        var name     = $(this).parent().parent().children().first().html();
        $.post(php_file, {"name": name}, function(message) {
            load_all();
            if (message != "") {
                 alert(message);
            }
        });

        e.preventDefault();
    });

    // 根据状态名设置状态
    $(document).on("click", "table#input_table2 a.archive_status", function(e) {
        var new_status_name = $(this).parent().parent().children().first().html();
        set_cur_state_name(new_status_name);
        var php_file = "./php/add_new_status.php";
        $.post(php_file, {"status_name": _cur_state_name}, function(message) {
            load_all();
            if (message != "") {
                 alert(message);
            }
        });

        e.preventDefault();
    });
})
