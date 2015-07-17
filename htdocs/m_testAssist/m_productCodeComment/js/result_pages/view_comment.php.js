window.onbeforeunload = function(){
    return '确认离开?';
}

$(function() {
    // == 变量定义区
    // _prodName, _version, _userName defined in view_comment.php
    var _file_path = "";
    var _func_names = {};
    var _delay_time = 200;
    var _add_note_mode_checked = "";
    var _tree_scroll_top = 0;
    var _mouse_in_tree_div = false;
    var _top_position_dict = {};
    var _retNoteArray = {};
    var _selected_note_id = -1;

    // == 元素定义区
    var _type_selector   = $("div#note_type select");
    var _input_title_box = $("div#title input");
    var _history_ul = $("div#history_items ul");
    var _input_note_box = $("div#user_input textarea");

    // 初始化区
    $("#tt").tree({
        onClick: function(node) {
            var file = node.attributes["file_path"];
            if (file == '') {
                return false;
            }
            
            _file_path = file.substr(125);
            $("div#filePath span").html(_file_path);
            $.getJSON("../php/tools/get_file_content.php", {"file_path":file}, function(data) {
                _func_names = data["funcNames"];
                _top_position_dict = {};

                var $file_content = $("div#file_content");
                $file_content.html(data["content"]);
                $file_content[0].scrollTop = 0;

                var $funcSelect = $("div#note_func_list select");
                $funcSelect.html("");
                $.each(_func_names, function(funcName, cnt) {
                    _top_position_dict[funcName] = [];
                });
                $.each(_func_names, function(funcName, cnt) {
                    _top_position_dict[funcName].push($("div[name='func_" + funcName + "']").position().top - 70);

                    if (cnt == 1) 
                        $funcSelect.append("<option value='" + funcName + "'>" + funcName + "</option>");
                    else
                        $funcSelect.append("<option value='" + funcName + "'>" + funcName + "(" + cnt + ")" + "</option>");
                });
            });

            load_note();
        },
    });

    // 事件定义区
    $("div#tree_navbar").mouseover(function(e) {
        var $tree_content = $("div#tree_content");
        $tree_content.show("slide", {}, _delay_time)
            .animate({scrollTop:_tree_scroll_top}, 100);
        
        _mouse_in_tree_div = true;
    });

    $("div#file_content").mouseover(function(e) {
        var $tree_content = $("div#tree_content");
        if (_mouse_in_tree_div == true) {
            _tree_scroll_top = $tree_content.scrollTop();
            _mouse_in_tree_div = false;
        }
        $tree_content.hide("slide", {}, _delay_time);
    });

    $("div#title2 input#note_add_mode").click(function(e) {
        _add_note_mode_checked = $(this).attr("checked");
        $("div#note_content div.n_sub").hide();

        if (_add_note_mode_checked == "checked") {
            $("div#note_content div#n_add").show();
        } else {
            $("div#note_content div#n_view").show();
        }
    });

    _type_selector.change(function(e) {
        _selected_note_id = -1;

        var type = $(this).val();
        $("div#file_content").animate({scrollTop: 0}, 100);
        $("div.note_func").hide();
        $("div.note_tts").hide();

        if (type == "file_desc") {

        } else if (type == "func_note") {
            $("div.note_func").show();
            locate_function();

        } else if (type == "trap_note") {
            $("div.note_tts").show();

        } else if (type == "test_note") {
            $("div.note_tts").show();

        } else if (type == "study_note") {
            $("div.note_tts").show();
        } 

        load_note_detail();
    });

    $("div#note_func_list select").change(function(e) {
        _selected_note_id = -1;

        load_note_detail();
        locate_function();
    });

    $("div#note_trap_list select").change(function(e) {
        load_note_detail();
    });

    $("div#note_content input#save_note, div#note_content input#update_note").click(function(e) {
        var user     = _userName;
        var prodName = _prodName;
        var version  = _version;
        var filePath = _file_path;
        var type = _type_selector.find(":selected").data("type");
        var detail_type = "";
        if (type == "function") {
            detail_type = $("div#note_func_list select").val();

        } else if (type == "trap" || type == "test" || type == "study") {
            detail_type = $("div#title input").val();
        }
        var content = _input_note_box.val();

        if (filePath == "") {
            error("Error: file path NOT set!");
            return false;
        }
        if (detail_type == undefined || detail_type == "") {
            if (type == "function") {
                error("Error: No function selected!");
                return false;
            } else if (type == "trap" || type == "test" || type == "study") {
                error("Error: No title given !");
                return false;
            }
        }
        if (content == "") {
            error("Error: Input Content Empty!");
            return false;
        }
        
        var ret =  confirm("确定保存?");
        if (ret != true) {
            warn("You selected cancel save!");
            return false;
        }
         

        var selected_note_id = _selected_note_id;
        var btn_id = $(this).attr('id');
        if (btn_id == "update_note" && _selected_note_id == -1) {
            error("你没有选择任何已有注释！");
            return false;
        } else if (btn_id == "save_note") {
            selected_note_id = -1;
        }

        $.post("../php/tools/save_user_note.php", {"userName": user, "prodName": prodName, "version": version, "filePath": filePath, "type": type, "detail_type": detail_type, "content": content, "selected_id": selected_note_id}, function(data) {
            if (data[0] != 0)
                error(data.substr(2));
            else {
                load_note();
                info(data.substr(2));
            }
        });
    });

    $("div#note_content input#clear").click(function(e) {
        _input_title_box.val("");
        _input_note_box.val("");
    });

    $(document).on("click", "div#history_items ul li a", function(e) {
        $("div#history_items ul li a").removeClass("selected");
        $(this).addClass("selected");

        _selected_note_id = $(this).data("id");
        var type = _type_selector.find(":selected").data("type");
        if (type == "test" || type == "trap" || type == "study") {
            _input_title_box.val($(this).data("title"));
        }
        _input_note_box.val($(this).data("content"));
        e.preventDefault();
    });

    // 函数定义区
    function locate_function() {
        var func_name = $("div#note_func_list select").val();
        if (! func_name) return false;
        var position = _top_position_dict[func_name];
        $("div#file_content").animate({scrollTop: position}, 100);
    }

    function log_it(msg, color) {
        var date_str = getTime();
        log_msg = $("div#info").html();
        msg = "状态: <span style='font-size:0.8em; color:" + color + "'>[" + date_str + "] " + msg + "</span>";
        $("div#info").html("").html(msg + "<br>" + log_msg);
    }

    function error(msg) {
        log_it(msg, "#800");
    }
    function warn(msg) {
        log_it(msg, "yellow");
    }
    function info(msg) {
        log_it(msg, "black");
    }

    function getTime() {
        var d = new Date();
        return d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
    }

    function load_history_items(subArr, selected) {
        var version = subArr["version"];
        var title   = subArr["detailType"];
        var content = subArr["content"];
        var type    = subArr["type"];

        if (title != "") title = " - " + title;

        if (version == _version && selected == false) {
            _history_ul.append("<li><a class='selected' href='#' data-id='" + subArr["_id"]["$id"] + "' data-title='" + subArr["detailType"] + "' data-content='" + content + "'>" + version + title + "</a></li>\n");
            if (type == "trap" || type == "test" || type == "study") {
                _input_title_box.val(subArr["detailType"]);
            }
            _input_note_box.val(content);
            _selected_note_id = subArr["_id"]["$id"];
            return true;

        } else {
            _history_ul.append("<li><a href='#' data-id='" + subArr["_id"]["$id"] + "' data-title='" + subArr["detailType"] + "' data-content='" + content + "'>" + version + title + "</a></li>\n");
            return selected;
        }
    }

    function load_note_detail() {
        var note_type = _type_selector.find(":selected").data("type");

        _history_ul.html("");
        _input_title_box.val("");
        _input_note_box.val("");

        var selected = false;

        $.each(_retNoteArray, function(idx, subArr) {
            var type = subArr["type"];

            if (type != note_type) return true;

            if (note_type == "file_desc" || note_type == "trap" || note_type == "test" || note_type == "study") {
                selected = load_history_items(subArr, selected);

            } else if (note_type == "function") {
                var note_funcName = $("div#note_func_list select").val();
                var funcName = subArr["detailType"];
                if (funcName == note_funcName) {
                    selected = load_history_items(subArr, selected);
                }
            }
        });
    }

    function load_note_detail_for_view() {
        $("div.easyui-accordion div.note3").html("");
        var color = "#0099FF";

        $.each(_retNoteArray, function(idx, subArr) {
            var color = "#0099FF";

            var type = subArr["type"];
            var version = subArr["version"];
            var content = "<pre>" + subArr["content"] + "</pre>";
            var detailType = subArr["detailType"];

            if (version == _version) color = "#FF9900";

            var element = null;
            if (type == "file_desc" || type == "test" || type == "study") {
                if (type == "file_desc")
                    element = $("div#view_file_desc");
                else if (type == "test")
                    element = $("div#view_test_desc");
                else if (type == "study")
                    element = $("div#view_study_desc");

                element.append("<h3 style='color:" + color + ";'>" + version + "</h3>")
                                .append("<p>" + content + "</p>");

            } else if (type == "function" || type == "trap") {
                if (type == "function")
                    element = $("div#view_func_desc");
                else if (type == "trap")
                    element = $("div#view_trap_desc");
                element.append("<h3 style='color:" + color + ";'>" + version + " - " + detailType + "</h3>")
                                .append("<p>" + content + "</p>");
            }
        });
    }

    function load_note() {
        _selected_note_id = -1;

        $.getJSON("../php/tools/load_user_note.php", {"prodName": _prodName, "filePath" : _file_path}, function(data) {
            if (data["ret"] == 1) {
                error(data["msg"]);
            } else {
                _retNoteArray = data["list"];
                load_note_detail();
                load_note_detail_for_view();
            }
        });
    }

});
