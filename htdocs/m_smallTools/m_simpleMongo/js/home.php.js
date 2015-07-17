$(function() {
    // === 变量定义区
    var _column_name_list = {};
    var _current_dbname = "";
    var _current_table  = "";
    var _select_html = "";
    var _cond_select = "<select name='cond_select'>\
        <option>:</option>\
        <option>$gt</option>\
        <option>$gte</option>\
        <option>$lt</option>\
        <option>$lte</option>\
        <option>$ne</option>\
        <option>$in</option>\
        <option>$nin</option></select>";

    $("#db_table_list").treeview({
        collapsed: true,
    });

    $(".query_it").hide();

    // === 载入表中的列名
    $("ul#db_table_list span a").click(function(e) {
        $("span a").removeClass("highlight");
        $(this).addClass("highlight");

        $("div#query_result").html("");
        $("table#query_table tr.new").remove();
        $(".query_it").hide();

        var list = $(this).attr("name").split(":", 2);
        var dbname = list[0];
        var table  = list[1];
        _current_dbname = dbname;
        _current_table = table;

        if (_column_name_list[dbname] == undefined) {
            _column_name_list[dbname] = {};
        }

        $("table#query_table caption").html("<h3>" + _current_dbname + " - " + _current_table + "</h3>");

        _select_html = "";
        if (_column_name_list[dbname][table] != undefined) {
            $.each(_column_name_list[dbname][table], function(idx, field) {
                _select_html += "<option>" + field + "</option>"; 
            });

            $(".query_it").show();
            $("tr:eq(0) a[name='add']").trigger("click");
            return false;
        }

        var php_file = "get_column_list.php";
        $.getJSON(php_file, {"host":_hostname, "port":_port, "user":_user, "password":_password, "dbname":dbname, "table":table}, function(data) {
            if (data.length == 0) {
                return false;
            }

            _column_name_list[dbname][table] = data;

            $.each(_column_name_list[dbname][table], function(idx, field) {
                _select_html += "<option>" + field + "</option>"; 
            });

            $(".query_it").show();
            $("tr:eq(0) a[name='add']").trigger("click");        
        });

        e.preventDefault();
    });

    // === 添加查询行
    $(document).on("click", "tr a[name='add']", function(e) {
        if (_current_dbname == "") {
            alert("错误：您还没有选择任何表!");
            return false;
        }

        $(this).parent().parent().after("<tr class='new'><td><select>" + _select_html + "</select></td><td>" + _cond_select + "</td><td name='input'><input type='text' name='value'/></td><td class='op'><a href='' name='add'>添加</a> <a href='' name='del'>删除</a> <a href='' name='user_input'>添加字段名</a></td></tr>");

        e.preventDefault();
    });

    // === 删除当前查询行
    $(document).on("click", "tr td a[name='del']", function(e) {
        if ($(this).hasClass("head_a")) {
            alert("Head行不能被删除!");
        } else {
            $(this).parent().parent().remove(); 
        }
        e.preventDefault();
    });


    // === 手动输入字段名
    $(document).on("click", "tr td a[name='user_input']", function(e) {
        var fieldName = prompt("Add field Name:");
        if (fieldName != null && fieldName != "") {
            _select_html += "<option>" + fieldName + "</option>";
        }

        e.preventDefault();
    });

    // === 请求数据
    $("input#query").click(function(e) {
        var query_cnt = $("input#limit").val();
        var query_cond_data = {};

        var input_valid = true;
        var trs = $("table#query_table tr");
        $.each(trs, function(idx, tr) {
            if (idx == 0) return true;

            var tds = $(tr).children();
            var field_name = $(tds[0]).children().first().val();
            var cond_str   = $(tds[1]).children().first().val();
            var field_val  = $(tds[2]).children().first().val();
            
            if (cond_str == ":" && query_cond_data[field_name] != undefined) {
                alert("有冲突的条件(code=1), 条件名:" + field_name);
                input_valid = false;
                return false;

            } else if (query_cond_data[field_name] != undefined && typeof query_cond_data[field_name] != "object") {
                alert("有冲突的条件(code=2), 条件名:" + field_name);
                input_valid = false;
                return false;
            } else if (query_cond_data[field_name] == undefined) {
                query_cond_data[field_name] = {};

            } else if (query_cond_data[field_name][cond_str] != undefined) {
                alert("有冲突的条件(code=3), 条件名:" + field_name);
                input_valid = false;
                return false;
            }

            if (cond_str != "$all" && cond_str != "$in" && cond_str != "$nin") {
                if (cond_str == ":") {
                    query_cond_data[field_name] = field_val;
                } else {
                    query_cond_data[field_name][cond_str] = field_val;
                }
            } else {
                query_cond_data[field_name][cond_str] = field_val.split(/\n/);
            }
        });

        $("div#show_json").html("");
        if ($("input#debug").attr("checked") == "checked") {
            $("div#show_json").html($.toJSON(query_cond_data));
        }

        if (input_valid == false) {
            return false;
        }

        var php_file = "./get_query_result.php";
        $("div#query_result").html("").load(php_file, {"host":_hostname, "port":_port, "user":_user, "password":_password, "dbname":_current_dbname, "table":_current_table, "query_str": $.toJSON(query_cond_data), "start_index":1, "count":query_cnt}, function(data){
            $("table#result_table tr:even").css("background-color", "#ff6");
            $("table#result_table tr:odd").css("background-color", "#ffc");
            $("table#result_table tr th").css("background-color", "#aabbcc");
        });
    });

    // === 条件选择
    $(document).on("change", "select[name='cond_select']", function(e) {
        var val = $(this).val();
        var $input_td = $(this).parent().next();
        if (val == "$in" || val == "$nin" || val == "$all") {
            $input_td.html("<textarea name='value' rows='10' placeholder='每个元素占一行'></textarea>");
        } else {
            $input_td.html("<input name='value /'>");
        }
    });

    $("input#show_result_only").click(function(e) {
        var checked = $(this).attr("checked");
        if (checked == "checked") {
            $("div#condition_set").hide();
        } else {
            $("div#condition_set").show();
        }
    });

    $("input#debug").click(function(e) {
        if ($(this).attr("checked") != "checked") {
            $("div#show_json").html("");
        }
    });
});
