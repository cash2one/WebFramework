$(function() {

    // ====================================================
    //      打开页面时， 进行初始化
    // ====================================================
    // 初始化index.php page
    var $case_index_div        = $("div#case_index");
    var $new_case_index_div    = $("div#new_case_index");
    var $edit_case_index_div   = $("div#edit_case_index");
    var $case_list_div         = $("div#case_list");
    var $new_case_div          = $("div#new_case");
    var $show_as_wiki_div      = $("div#show_as_wiki");

    var _edit_index_id     = null;
    var _view_case_list_id = null;
    var _op_str = "<a href='' id='case_add_btn'>添加</a> <a href='' id='case_edit_btn'>编辑</a> <a href='' id='case_del_btn'>删除</a> <a href='' id='case_copy_btn'>复制</a>";
    var _edit_case_tr = null;
    var _case_op_type = null;
    var _case_op_tr   = null;

    // 初始状态，隐藏一些div
    $new_case_index_div.hide();
    $edit_case_index_div.hide();
    $case_list_div.hide();
    $new_case_div.hide();
    $show_as_wiki_div.hide();

    // ====================================================
    //  实例化页面元素，从而提高效率
    // ====================================================
    var $case_index_tbody = $("tbody#case_index_tbody");
    var $case_list_tbody  = $("tbody#case_list_tbody");
    var $new_case_index_btn = $("a#new_case_index_btn");

    // 载入case index列表
    $case_index_tbody.load("./php/list.php");

    // 当离开页面时，弹窗
    window.onbeforeunload = function(){
        return '提示：离开前请确保数据已经保存完毕!';
    }

    // ====================================================
    //  用例集合上的事件处理函数
    // ====================================================
    // 创建新的case index 行
    $new_case_index_btn.click(function(e) {
        $case_index_div.hide();        
        $("input[type=text]", $new_case_index_div).val("");
        $new_case_index_div.show();
        $("input#case_index_title").focus();
        e.preventDefault();
    });

    // 编辑当前case index 行
    $(document).on("click", "a#edit_case_index_btn", function(e) {
        var $tr = $(this).parent().parent();
        _edit_index_id = $tr.attr("id"); //设置全局变量的id值
        var $tds = $tr.children();
        var title        = $tds.eq(1).html();
        var service_name = $tds.eq(2).html();
        var user         = $tds.eq(3).html();
        var ticket_addr  = $("a", $tds.eq(4)).html();
        var comment      = $tds.eq(5).html();
        
        $case_index_div.hide();        
        $("input#case_index_title2").val(title);
        $("input#service_name2").val(service_name);
        $("input#user2").val(user);
        $("input#ticket_addr2").val(ticket_addr);
        $("input#comment2").val(comment);
        $edit_case_index_div.show();
        $("input#case_index_title2").focus();
        e.preventDefault();
    });

    // 查看用例列表
    $(document).on("click", "a#view_case_list_btn", function(e) {
        var case_name = $(this).parent().parent().children().eq(1).html();
        $("h2").html(case_name)
        _view_case_list_id = $(this).parent().parent().attr("id");
        $case_index_div.hide();
        var php_file = "./php/load_case_list.php";
        $case_list_tbody.load(php_file, {"id": _view_case_list_id}); 
        $case_list_div.show();
        e.preventDefault();
    });

    // 新建一个case index行
    $(document).on("click", "input#new_index_btn", function(e) {
        var title        = $("input#case_index_title").val();
        var service_name = $("input#service_name").val();
        var user         = $("input#user").val();
        var ticket_addr  = $("input#ticket_addr").val();
        var comment      = $("input#comment").val();

        if (title == ""){ 
            alert('用例集合标题不能为空');
            return false;
        }
        if (service_name == ""){ 
            alert('服务名称不能为空');
            return false;
        }
        if (user == ""){ 
            alert('创建者不能为空');
            return false;
        }
        if (ticket_addr == ""){
            alert('提测ticket地址不能为空');
            return false;
        }

        var php_file = "./php/create.php";
        $.getJSON(php_file, {"title": title, "service_name": service_name, "user": user, "ticket_addr": ticket_addr, "comment": comment}, function(ret_obj) {
            if (ret_obj.ret == 0) {
                $new_case_index_div.hide();
                $case_index_div.show();
                $case_index_tbody.load("./php/list.php");
            } else {
                alert(ret_obj.message);
            }
        });
    });

    $(document).on("click", "input#new_index_return_btn", function(e) {
        $new_case_index_div.hide();
        $case_index_div.show();
        $case_index_tbody.load("./php/list.php");
    });

    // 编辑一个case index行
    $(document).on("click", "input#update_index_btn", function(e) {
        var title        = $("input#case_index_title2").val();
        var service_name = $("input#service_name2").val();
        var user         = $("input#user2").val();
        var ticket_addr  = $("input#ticket_addr2").val();
        var comment      = $("input#comment2").val();


        if (title == ""){ 
            alert('用例集合标题不能为空');
            return false;
        }
        if (service_name == ""){ 
            alert('服务名称不能为空');
            return false;
        }
        if (user == ""){ 
            alert('创建者不能为空');
            return false;
        }
        if (ticket_addr == ""){
            alert('提测ticket地址不能为空');
            return false;
        }

        var php_file = "./php/update.php";
        $.getJSON(php_file, {"id": _edit_index_id, "title": title, "service_name": service_name, "user": user, "ticket_addr": ticket_addr, "comment": comment}, function(ret_obj) {
            if (ret_obj.ret == 0) {
                $edit_case_index_div.hide();
                $case_index_div.show();
                $case_index_tbody.load("./php/list.php");
            } else {
                alert(ret_obj.message);
            }
        });
    });

    $(document).on("click", "input#update_index_return_btn", function(e) {
        $edit_case_index_div.hide();
        $case_index_div.show();
        $case_index_tbody.load("./php/list.php");
    });

    // ====================================================
    //  用例上的事件处理函数
    // ====================================================
    $(document).on("click", "a#new_case_btn", function(e) {
        _case_op_type = "new";
        $case_list_div.hide();
        $("input[type=text]", $new_case_div).val("");
        $("textarea", $new_case_div).val("");
        $new_case_div.show();
        $("input#case_cate").focus();
        e.preventDefault();
    });

    $(document).on("click", "a#case_add_btn", function(e) {
        _case_op_type = "add";
        _case_op_tr = $(this).parent().parent();
        var case_cate = $(this).parent().parent().children().eq(0).html();

        $case_list_div.hide();
        $("input[type=text]", $new_case_div).val("");
        $("textarea", $new_case_div).val("");
        $("input#case_cate").val(case_cate);
        $new_case_div.show();
        $("input#case_cate").focus();
        e.preventDefault();
    });

    $(document).on("click", "a#case_del_btn", function(e) {
        // $(this).parent().parent().remove();
        $(this).parent().parent().removeClass().addClass("case_deleted");
        e.preventDefault();
    });

    $(document).on("click", "a#case_copy_btn", function(e) {
        _case_op_type = "copy";
        _case_op_tr = $(this).parent().parent();
        var tds = $(this).parent().parent().children();
        var case_cate = tds.eq(0).html();
        var case_title = tds.eq(1).html();
        var case_comment = tds.eq(2).html();
            case_comment = case_comment.replace(/<br>/g, "\n");
        var case_status  = tds.eq(3).html();

        $case_list_div.hide();
        $("input#case_cate").val(case_cate);
        $("input#case_title").val(case_title);
        $("textarea#case_comment").val(case_comment);
        $("input#case_status").val(case_status);
        $new_case_div.show();
        $("input#case_cate").focus();

        e.preventDefault();
    });

    $(document).on("click", "a#case_edit_btn", function(e) {
        _case_op_type = "edit";
        _case_op_tr = $(this).parent().parent();
        var tds = _case_op_tr.children();
        var case_cate = tds.eq(0).html();
        var case_title = tds.eq(1).html();
        var case_comment = tds.eq(2).html();
            case_comment = case_comment.replace(/<br>/g, "\n");
        var case_status  = tds.eq(3).html();

        $case_list_div.hide();
        $("input#case_cate").val(case_cate);
        $("input#case_title").val(case_title);
        $("textarea#case_comment").val(case_comment);
        $("input#case_status").val(case_status);
        $new_case_div.show();
        $("input#case_cate").focus();
        e.preventDefault();
    });

    $(document).on("click", "a#move_up", function(e) {
        var cur_tr = $(this).parents("tr");
        if (cur_tr.html() != $("#case_list_table tr:eq(1)").html()) {
            cur_tr.insertBefore(cur_tr.prev());
        }
        e.preventDefault();
    });

    $(document).on("click", "a#move_down", function(e) {
        var cur_tr = $(this).parents("tr");
        if (cur_tr.html() != $("#case_list_table tr:last-child").html()) {
            cur_tr.insertAfter(cur_tr.next());
        }
        e.preventDefault();
    });

    $(document).on("click", "input#keep_status_btn", function(e) {
        var case_cate  = $("input#case_cate").val();
        var case_title = $("input#case_title").val();
        var comment    = $("textarea#case_comment").val();
            comment    = comment.replace(/\n/g, "<br>");
        var status     = $("input#case_status").val();

        if (_case_op_type == "edit") {
            _case_op_tr.children().eq(0).html(case_cate);
            _case_op_tr.children().eq(1).html(case_title);
            _case_op_tr.children().eq(2).html(comment);
            _case_op_tr.children().eq(3).html(status);
            $(_case_op_tr).removeClass().addClass("case_unsaved");

        } else  {
            var $tr = $("<tr><td>" + case_cate + "</td><td>" + case_title + "</td><td>" + comment + "</td><td>" + status + "</td><td>" + _op_str + "</td></tr>");
            $tr.removeClass().addClass("case_unsaved");
            if (_case_op_type == "new") {
                $case_list_tbody.append($tr);
            } else if (_case_op_type == "add") {
                _case_op_tr.after($tr);
            } else if (_case_op_type == "copy") {
                _case_op_tr.after($tr);
            }
        }

        _case_op_tr = null;
        $new_case_div.hide();
        $case_list_div.show();
    });

    $(document).on("click", "input#new_case_return_btn", function(e) {
        $new_case_div.hide();
        $case_list_div.show();
        $case_index_tbody.load("./php/list.php");
    });

    $(document).on("click", "a#save_case_list_btn", function(e) {
        var ret = confirm("提示: 保存数据会更新服务器上的文件，确认保存?");
        if (ret != true) {
            return false;
        }
        
        var rows = new Array();
        $.each($("tr", $case_list_tbody), function(idx, row) {
            if (! $(row).hasClass("case_deleted")) {
                var tds = $(row).children();
                var row2 = [tds.eq(0).html(), tds.eq(1).html(), tds.eq(2).html(), tds.eq(3).html()];
                rows.push(row2);
            }
        });

        var param_str = $.toJSON(rows);
        var php_file = "./php/write_case_list.php";
        $.post(php_file, {"id": _view_case_list_id, "param_str": param_str}, function() {
            $("tr.case_unsaved", $case_list_tbody).removeClass("case_unsaved");
            var php_file = "./php/load_case_list.php";
            $case_list_tbody.load(php_file, {"id": _view_case_list_id}); 
        });

        e.preventDefault();
    });

    $(document).on("click", "a#case_list_return_btn", function(e) {
        var unsaved_list = $.makeArray($("tr.case_unsaved", $case_list_tbody));
        var deleted_list = $.makeArray($("tr.case_deleted", $case_list_tbody));
        if (unsaved_list.length != 0 || deleted_list.length != 0) {
            var ret = confirm("警告: 有用例没有保存，返回会导致数据丢失，确认返回?");
            if (ret != true) {
                return false;
            }
        }

        $case_list_div.hide();
        $case_index_div.show();
        $case_index_tbody.load("./php/list.php");

        $("h2").html("用例编写工具");

        e.preventDefault();
    });

    $(document).on("click", "a#show_case_list_as_wiki", function(e) {
        if ($show_as_wiki_div.is(':hidden')) {
            $show_as_wiki_div.html("");
            $show_as_wiki_div.append("||'''用例分类'''||'''用例标题'''||'''备注'''||'''状态'''||<br>");
            $.each($("tr", $case_list_tbody), function(idx, row) {
                var tds = $(row).children();
                $show_as_wiki_div.append("|| " + tds.eq(0).html() + " || " +  tds.eq(1).html() + " || " + tds.eq(2).html().replace(/<br>/g, " &lt;&lt;BR&gt;&gt;") + " || " + tds.eq(3).html() + " ||<br>");
            });

            $show_as_wiki_div.show();
        } else {
            $show_as_wiki_div.hide();
        }
        e.preventDefault();
    });

});
