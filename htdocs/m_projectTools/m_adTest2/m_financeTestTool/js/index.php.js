$(function() {

    // 全局数据变量保存区
    var _conf_file = null;
    var _cur_state = "";

    // 变量定义区
    var $web_conf_td = $("div#web_config table tr td#conf_list");
    var $main_page = $("div#main_page");

    var $show_table_div = $("div#main_page div#show_table");
    var $del_table_div = $("div#main_page div#del_table");
    var $record_db_div = $("div#main_page div#record_db");
    var $show_db_div = $("div#main_page div#show_db");

    var $result_div = $("div#div_result");

    var $cur_state_span = $("span#cur_state");

    // 初始化区
    $web_conf_td.load("./php/web_config.php");
    $main_page.hide();
    $show_table_div.hide();
    $del_table_div.hide();
    $record_db_div.hide();
    $show_db_div.hide();

    // 事件处理区
    $(document).on("click", "td#conf_list input[name='conf_rd']", function(e) {
        _conf_file = "../conf/" + $(this).attr("for");
        var php_file = "./php/file_load.php";
        $("div#web_config table tr td#conf_content").html("").load(php_file, {"filename": _conf_file});
    });

    $(document).on("click", "input#set_conf_btn", function() {
        if (_conf_file == null) {
            alert("Error: you haven't select any conf file");
            return false;
        }

        $("div#web_config").hide();
        $main_page.show();
    });

    // === 查询数据库数据相关事件
    $(document).on("click", "a#show_table_t", function(e) {
        $("input#check_all_1").attr("checked", false);
        $result_div.html("");
        $("div#main_page div.content").hide(); 
        $show_table_div.show();

        var php_file = "./php/show_table.php";
        $("div#table_list_for_show", $show_table_div).load(php_file, {"conf_file": _conf_file});

        e.preventDefault();
    });

    $(document).on("click", "input#show_table_btn", function(e) {
        var $checkbox_list = $("div#table_list_for_show input[type='checkbox']");
        var schema_list = [];
        $.each($checkbox_list, function(idx, chkbox) {
            if ($(chkbox).attr("checked") == "checked") {
                schema_list.push($(chkbox).attr("name"));
            }
        });

        if (schema_list.length == 0) {
            alert("Error: You haven't select any table to show.");
            return false;
        }

        var schema_list_str = schema_list.join(",");
        var php_file = "./php/show_table_content.php";
        $result_div.load(php_file, {"conf_file": _conf_file, "schema_list_str": schema_list_str});
    });

    $("input#check_all_1").click(function(e) {
        var checked = $(this).attr("checked") == "checked" ? "checked": false;
        $("div#table_list_for_show input[type='checkbox']").attr("checked", checked);
    });

    // === 删除数据库数据相关事件
    $(document).on("click", "a#del_table_t", function(e) {
        $("input#check_all_2").attr("checked", false);
        $result_div.html("");
        $("div#main_page div.content").hide(); 
        $del_table_div.show();

        var php_file = "./php/del_table.php";
        $("div#table_list_for_del", $del_table_div).load(php_file, {"conf_file": _conf_file});

        e.preventDefault();
    });

    $(document).on("click", "input#del_table_btn", function(e) {
        var $checkbox_list = $("div#table_list_for_del input[type='checkbox']");
        var schema_list = [];
        $.each($checkbox_list, function(idx, chkbox) {
            if ($(chkbox).attr("checked") == "checked") {
                schema_list.push($(chkbox).attr("name"));
            }
        });

        if (schema_list.length == 0) {
            alert("Error: You haven't select any table to clean.");
            return false;
        }

        var ret = confirm("Confirm to delete data in table ?");
        if (ret != true) {
            return false;
        }

        var schema_list_str = schema_list.join(",");
        var php_file = "./php/del_table_content.php";
        $result_div.load(php_file, {"conf_file": _conf_file, "schema_list_str": schema_list_str});
    });

    $("input#check_all_2").click(function(e) {
        var checked = $(this).attr("checked") == "checked" ? "checked": false;
        $("div#table_list_for_del input[type='checkbox']").attr("checked", checked);
    });

    // === 录制数据库数据相关事件
    $(document).on("click", "a#record_db_t", function(e) {
        $result_div.html("");
        $("div#main_page div.content").hide(); 
        $record_db_div.show();

        var php_file = "./php/list_record_db.php";
        $("table#record_db_tbl tbody", $record_db_div).load(php_file, {"conf_file": _conf_file});

        e.preventDefault();
    });

    $(document).on("click", "a#bakup_state", function(e) {

        if (_cur_state == "") 
            var state_name = prompt("请输入状态名:");
        else
            var state_name = prompt("请输入状态名:", _cur_state + "|");
        if (state_name == "") { 
            alert("Error: Invalid state name!");
            return false;
        } else if (state_name == null) {
            return false;
        }
         
        var php_file = "./php/bakup_state.php";
        $.post(php_file, {"conf_file": _conf_file, "state_name": state_name}, function(msg) {
            var ret_list = msg.split(//);
            if (ret_list[0] != 0) {
                alert(ret_list[1]);
                return false;
            }
            _cur_state = state_name;
            $cur_state_span.html(_cur_state);

            var php_file = "./php/list_record_db.php";
            $("table#record_db_tbl tbody", $record_db_div).load(php_file, {"conf_file": _conf_file});
        });

        e.preventDefault();
    });

    $(document).on("click", "a#del_all_states", function(e) {
        var ret = confirm("确认删除所有保存的状态?");
        if (ret != true) {
            return false;
        }

        var php_file = "./php/del_all_states.php";
        $.post(php_file, {"conf_file": _conf_file}, function(msg) {
            var ret_list = msg.split(//);
            if (ret_list[0] != 0) {
                alert(ret_list[1]);
                return false;
            }

            var php_file = "./php/list_record_db.php";
            $("table#record_db_tbl tbody", $record_db_div).load(php_file, {"conf_file": _conf_file});
        });

        e.preventDefault();
    });

    $(document).on("click", "a[name='update_state']", function(e) {
        var php_file = "./php/update_record_db.php";
        var state_name = $(this).parent().prev().html();
        $.post(php_file, {"conf_file": _conf_file, "state_name": state_name}, function(msg) {
            var ret_list = msg.split(//);
            alert(ret_list[1]);
            if (ret_list[0] != 0) {
                return false;
            }

            _cur_state = state_name;
            $cur_state_span.html(_cur_state);
        });

        e.preventDefault();
    });

    $(document).on("click", "a[name='del_state']", function(e) {
        var php_file = "./php/del_state.php";
        var state_name = $(this).parent().prev().html();

        var ret = confirm("Confirm to delete the state(" + state_name + ")");
        if (ret != true) {
            return false;
        }

        $.post(php_file, {"conf_file": _conf_file, "state_name": state_name}, function(msg) {
            var ret_list = msg.split(//);
            if (ret_list[0] != 0) {
                alert(ret_list[1]);
                return false;
            }

            var php_file = "./php/list_record_db.php";
            $("table#record_db_tbl tbody", $record_db_div).load(php_file, {"conf_file": _conf_file});
        });

        e.preventDefault();
    });

    // === 查看数据库数据相关事件
    $(document).on("click", "a#show_db_t", function(e) {
        $result_div.html("");
        $("div#main_page div.content").hide(); 
        $show_db_div.show();

        var php_file = "./php/show_record_db.php";
        $("select#senario_1", $show_db_div).load(php_file, {"conf_file": _conf_file});
        $("select#senario_2", $show_db_div).load(php_file, {"conf_file": _conf_file});

        e.preventDefault();
    });

    $(document).on("change", "select#senario_2", function(e) {
        var s1_state = $("select#senario_1").val();
        var s2_state = $(this).val();

        var php_file = "./php/show_results.php";
        $result_div.html("").load(php_file, {"conf_file": _conf_file, "s1_state": s1_state, "s2_state": s2_state}, function() {
            $("table.table_results").hide();
        });
    });

    $(document).on("change", "select#senario_1", function(e) {
        var $select2 = $("select#senario_2");
        var s1_index = $(this)[0].selectedIndex;
        $select2[0].selectedIndex = s1_index >= $select2.children().length - 1 ? s1_index : s1_index + 1; // starts from 0
        $result_div.html("");
        $select2.trigger("change");
    });

    $(document).on("click", "table#index_table td a", function(e) {
        var table_name = $(this).html();
        $("table.table_results").hide();
        $("table[name=" + table_name + "]").show();
        e.preventDefault();
    });

});
