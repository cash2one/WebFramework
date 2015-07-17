$(function() {
    // =============================== 变量定义区
    var _set_file = "";
    var _interval_handler = null;
    var _qry_interval = 1000; // one sec
    var _last_data_returned = true;
    var _cur_lines_cnt = null;
    var _max_lines_cnt = null;
    var _ldap = null;
    var _passwd = null;
    var _query_cnt = 0;

    $("input#ldap").focus();
    
    // =============================== 事件处理区
    $("input#query_btn").click(function() {
        var val = $(this).val();
        if (val == "请求日志") {
            _cur_lines_cnt = 0;
            var ret = start_query_log();
            if (ret == false) {
                return false;
            }
            $("label#tip").html("<font color='red'>注意：请求开始需要计算日志行数，请耐心等待</font>");
            $(this).val("停止请求");
        } else {
            clearInterval(_interval_handler);
            _interval_handler = null;
            $(this).val("请求日志");
            alert("请求已停止!");
        }
        _query_cnt = 0;
    });

    $("#tabs").on( "tabsactivate", function( event, ui ) {
        var tab_idx = $(this).tabs("option", "active");
        var $click_console = $("div#click_console");
        var $anti_console = $("div#anti_console");
        if (tab_idx == 1) {
            click_console.scrollTop = click_console.scrollHeight;
        } else {
            anti_console.scrollTop = anti_console.scrollHeight;
        }
    });

    // =============================== 函数处理区
    function start_query_log() {
        var click_log_selected = $("input#click-service").attr("checked") == "checked" ? 1 : 0;
        var anti_log_selected = $("input#anti-service").attr("checked") == "checked" ? 1 : 0;
        var filter_str = $("input#filter_str").val();
        _max_lines_cnt = parseInt($("input#max_lines_cnt").val());
        _ldap = $("input#ldap").val();
        _passwd = $("input#passwd").val();

        if (click_log_selected == 0 && anti_log_selected == 0) {
            alert("还没有选择服务");
            return false;
        }
        if (filter_str == "") {
            alert("过滤条件不能为空");
            return false;
        }
        if (max_lines_cnt <= 0) {
            alert("最大读取行数必须大于0");
            return false;
        }
        if (_ldap == "" || _passwd == "") {
            alert("ldap或者集群密码不能为空");
            return false;
        }
        
        var php_file = "./php/set_file.php";
        $.getJSON(php_file, {"click": click_log_selected, "anti": anti_log_selected, "filter_str": filter_str, "username": _ldap}, function(data) {
            if (data[0] == 1) {
                alert(data[1]);
            } else {
                $("div#click_console").html("");
                $("div#anti_console").html("");

                _set_file = data[1];
                if (_interval_handler == null) {
                    _interval_handler = setInterval(do_query, _qry_interval);
                    do_query();
                }
            }
        });
    }

    function do_query() {
        if (_last_data_returned == false) {
            return false;
        }
        _last_data_returned = false;
        _query_cnt ++;

        var php_file = "./php/query_log.php";
        var $click_console = $("div#click_console");
        var $anti_console = $("div#anti_console");
        $.getJSON(php_file, {"set_file": _set_file, "username": _ldap, "password": _passwd}, function(data) {
            if (data[0] == 0) {
                $.each(data[1], function(idx, line) {
                    if (line == "") return false;
                    _cur_lines_cnt += 1;
                    $click_console.append("<span>" + line + "</span><br>");
                    $click_console.append("<hr>");
                    click_console.scrollTop = click_console.scrollHeight;
                });

                $.each(data[2], function(idx, line) {
                    if (line == "") return false;
                    _cur_lines_cnt += 1;
                    $anti_console.append("<span>" + line + "</span><br>");
                    $anti_console.append("<hr>");
                    anti_console.scrollTop = anti_console.scrollHeight;
                });

                $("label#tip").html("<font color='blue'>提示：日志已到达,有可能是空数据(请求次数:" + _query_cnt + ")</font>");
            }

            _last_data_returned = true;

            if (data[0] != 0 || _cur_lines_cnt > _max_lines_cnt) {
                data[0] = 20;
                clearInterval(_interval_handler);
                $("input#query_btn").val("请求日志");
            }

            if(data[0] == 1) {
                alert("读取日志行数失败！");
            } else if(data[0] == 2) {
                alert("读取日志失败");

            } else if(data[0] == 10) {
                alert("用户名或密码错误");
            } else if(data[0] == 20) {
                alert("日志读取已达到最大行数或密码错误");
            } else if(data[0] != 0) {
                alert("未知错误");
            }
        });
    }
});
