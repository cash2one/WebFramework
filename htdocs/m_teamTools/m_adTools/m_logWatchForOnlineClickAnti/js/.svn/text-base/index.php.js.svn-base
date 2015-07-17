// ======================= Global Variables ================================
// 定时器 延时器相关：http://zhidao.baidu.com/question/304724849.html
var _query_dict = {
    "anti": [], // host:log_path:log_index
    "click": [],
    "filter_str": "",
    "username": "",
    "password": "",
    "rsa_password": "",
};

var _time_interval = 1000; //1 second
var _timer = null; // 定时器
var _data_returned = false;

var _line_count = 0;
var _max_line_count = null;

// ======================= Functions ================================
function check_user_input() {
    filter_str = $("input#log_filter_str").val();
    if (filter_str == "") {
        alert("请输入过滤提条件!");
        return false;
    }

    var ldap = $("input#ldap_text").val();
    if (ldap == "") {
        alert("请输入您的ldap！");
        return false;
    }

    var password = $("input#machine_text").val();
    if (password == "") {
        alert("请输入您的集群密码！");
        return false;
    }

    // 可选
    // var rsa_password = $("input#machine_rsa").val();
    var rsa_password = "";

    var click_list = [];
    var anti_list = [];
    $.each($("input.service_type:checked"), function(idx, type_checkbox) {
        var span_list = $("span", $(type_checkbox).parent().next());
        $.each(span_list, function(idx1, span) {
            var machine_log_info = $(span).html() + ":0";
            if ($(type_checkbox).is("#click_service")) {
                click_list.push(machine_log_info); 

            } else if ($(type_checkbox).is("#anti_service")) {
                anti_list.push(machine_log_info); 
            }
        });
    });
    if (click_list.length == 0 && anti_list.length == 0) {
        alert("请选择一种服务类型!");
        return false;
    } 

    // 装入字典中
    _query_dict = {
        "anti": anti_list,
        "click": click_list,
        "filter_str": filter_str,
        "username": ldap,
        "password": password,
        "rsa_password": rsa_password,
    };

    return true;
}

function update_console($console_div, lines) {
    $.each(lines, function(idx, line) {
        _line_count += 1;
        $console_div.append("<span>" + line + "</span><br>");
        $console_div[0].scrollTop = $console_div[0].scrollHeight;
    });
}

function stop_log_req() {
    if (_timer != null) {
        $("input#query_log").trigger("click");
    }
}

function request_log() {
    if (_line_count >= _max_line_count) {
        stop_log_req();
    }

    // 数据未返回，定时时间到也不请求日志
    if (_data_returned == false) {
        return false;
    }
    _data_returned = false;

    var req_str = $.toJSON(_query_dict);
    var php_file = "./php/get_log.php";
    $.getJSON(php_file, {"req_str": req_str}, function(data) {
        _data_returned = true;

        var status = data.status;
        if (status != 0) {
            alert("Error: " + data.message);
            $("a#show_machine_info").trigger("click");
            stop_log_req();
            return false;
        }

        // upate local value for next request
        _query_dict["anti"] = data.anti.machine_info;
        _query_dict["click"] = data.click.machine_info;

        // update click console
        var $click_log_console = $("div#click_log_output_div");
        update_console($click_log_console, data.click.lines);

        // update anti console
        var $anti_log_console = $("div#anti_log_output_div");
        update_console($anti_log_console, data.anti.lines);
    });
}


// ======================= Main Logic ================================
$(function() {
    // init period
    $("div#anti_log_output_div").hide();
    $("a#click_log_show").addClass("link_disabled");
    $("a#anti_log_show").addClass("link_disabled");

    $("div#output_area").hide();
    $("a#show_machine_info").addClass("link_disabled");
    $("a#show_log_info").addClass("link_enabled");

    $("tbody#log_tbody").load("./php/read_user_log.php");

    // ====== Handler for click on nav links to switch pages ======
    $("a#show_machine_info").click(function(e) {
        if ($(this).hasClass("link_disabled")) {
            return false;
        }
        $(this).removeClass("link_enabled").addClass("link_disabled");
        $("a#show_log_info").removeClass("link_disabled").addClass("link_enabled");
        $("div#input_area").show();
        $("div#output_area").hide();
        e.preventDefault();
    });

    $("a#show_log_info").click(function(e) {
        if ($(this).hasClass("link_disabled")) {
            return false;
        }
        $(this).removeClass("link_enabled").addClass("link_disabled");
        $("a#show_machine_info").removeClass("link_disabled").addClass("link_enabled");
        $("div#output_area").show();
        $("div#input_area").hide();
        e.preventDefault();
    });

    // ====== Handler for click on nav links to switch log types ======
    $("a#click_log_show").click(function(e) {
        if ($(this).hasClass("link_highlight")) {
            return false;
        }
        $(this).removeClass("link_disabled").addClass("link_highlight");
        $("a#anti_log_show").removeClass("link_highlight").addClass("link_disabled");
        $click_console = $("div#click_log_output_div");
        $("div#anti_log_output_div").hide();
        $click_console.show();
        $click_console[0].scrollTop = $click_console[0].scrollHeight;
        e.preventDefault();
    });

    $("a#anti_log_show").click(function(e) {
        if ($(this).hasClass("link_highlight")) {
            return false;
        }
        $(this).removeClass("link_disabled").addClass("link_highlight");
        $("a#click_log_show").removeClass("link_highlight").addClass("link_disabled");
        $anti_console = $("div#anti_log_output_div");
        $("div#click_log_output_div").hide();
        $anti_console.show();
        $anti_console[0].scrollTop = $anti_console[0].scrollHeight;
        e.preventDefault();
    });

    // ====== Handler for click on log (stop)request button ======
    $("input#query_log").click(function(e) {
        if (_timer == null) {
            var ret = check_user_input();
            if (ret == false) {
                return false;
            }

            var write_log_file = "./php/write_user_log.php";
            $.get(write_log_file, {"username": _query_dict["username"], "filter_str": _query_dict["filter_str"]});

            $("a#show_log_info").trigger("click");
            if (_query_dict["anti"].length != 0) {
                $("a#anti_log_show").trigger("click");

            } else {
                $("a#click_log_show").trigger("click");
            }

            // 做一些初始化的工作
            if ($("input#clearLogFlag").attr('checked') == "checked") {
                $("div#click_log_output_div").html("");
                $("div#anti_log_output_div").html("");
            }

            _line_count = 0;
            _max_line_count = parseInt($("input#max_log_count").val());

            // start timer
            _data_returned = true;
            _timer = setInterval(request_log, _time_interval);
            $(this).val("停止请求日志");

        } else {
            clearInterval(_timer);
            _timer = null;
            $(this).val("请求日志");
            alert("已停止");
        }
    });
});
