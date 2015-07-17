// ==== variables ======
var _urls = null;

// triggered when document ready
$(function() {
    // load machine info for selection
    var php_file = "./php/machine_info_selection.php";
    $("table#machine_info_table").load(php_file);

    // make machine info table hidden
    $("table#machine_info_table").hide();

    // make log_info div hidden
    $("div#log_info").hide();

    // load log file
    $("tbody#log_info_tbody").load("./php/query_log.php");

    // toggle on user_input_area
    $("a#input_a").toggle(function() {
        $(this).html("&gt;&gt;&gt;");
        $(this).attr("title", "点击我显示输入部分");
        $("div#user_input_area").hide();
        
    }, function() {
        $(this).html("&lt;&lt;&lt;");
        $(this).attr("title", "点击我隐藏输入部分");
        $("div#user_input_area").show();
    });

    // toggle on result show area
    $("a#output_a").toggle(function() {
        $(this).html("&gt;&gt;&gt;");
        $(this).attr("title", "点击我显示结果部分");
        $("div#result_show_area").hide();
        
    }, function() {
        $(this).html("&lt;&lt;&lt;");
        $(this).attr("title", "点击我隐藏结果部分");
        $("div#result_show_area").show();
    });

    // toggle on result show area
    $("a#view_log").toggle(function() {
        $(this).html("隐藏日志");
        $("div#log_info").show();

    }, function() {
        $(this).html("查看日志");
        $("div#log_info").hide();
    });

    // toggle on machine info table
    $("a#view_extra_info").toggle(function() {
        $(this).html("隐藏额外信息");
        $("table#machine_info_table").show();

    }, function() {
        $(this).html("显示额外信息");
        $("table#machine_info_table").hide();
    });
});

// 添加行
$(document).on("click", "a.add_row", function(e) {
    // var url = "/disk2/zhangpei/antifrauder/new-framework/anti_analyzer"
    var row_str = get_row_html_str("", "", "auto", "auto", "auto");

    if ($(this).hasClass("head_add")) {
        $("tbody#analyzer_input").prepend(row_str);

    } else {
        $(this).parent().parent().after(row_str);
    }

    e.preventDefault();
});

// 删除行
$(document).on("click", "a.del_row", function(e) {
    $(this).parent().parent().remove();
    e.preventDefault();
});

// 显示结果
$(document).on("click", "a#show_results", function(e) {
    // get request obj
    var req_str = get_request_info_str(); 
    if (req_str == false) {
        return req_str;
    }

    // make hidden
    if ($("table#machine_info_table").is(":visible")) {
        $("a#view_extra_info").trigger("click");
    }
    
    if ($("div#log_info").is(":visible")) {
        $("a#view_log").trigger("click");
    }

    var $output_div = $("div#result_show_area");
    $output_div.html("<b><font color='red'>请稍等一会儿...</font></b>");

    // query results
    var php_file = "./php/query_result.php";
    $.getJSON(php_file, {"req_str": req_str}, function(data) {
        $output_div.html("");

        var ret = data.ret;
        if (ret != 0) {
            alert(data.msg);
            return false;
        }

        _urls = data.results;
        show_images();
    });

    e.preventDefault();
});

function get_image_title(url) {
    var prod = null;
    var name = null;
    var cube = null;
    // http://vaquero.corp.youdao.com//image?type=img&product=eadu&name=nc111&drawname=eadu.resin.elapse.response&cubtype=resin&period=month
    var list = url.split(/&/);
    $.each(list, function(idx, kv) {
        var kvlist = kv.split(/=/);
        if (kvlist[0] == "product") {
            prod = kvlist[1];

        } else if (kvlist[0] == "cubtype") {
            cube = kvlist[1];

        } else if (kvlist[0] == "name") {
            name = kvlist[1];
        }
    });

    return prod + "-" + cube + "-" + name;
}

function show_images() {
    var count_select = $("select#count_select").val();
    var $table = $("<table border='1' class='output_table'></table>");
    var $tr = null;
    $.each(_urls, function(idx, url) {
        if (idx % count_select == 0) {
            $tr = $("<tr></tr>");
            $table.append($tr);
        }
        var title = get_image_title(url);
        $table.children().last().append("<td><p class='img_title'>" + title + "</p><img src='" + url + "' /></td>");
    });

    $("div#result_show_area").html($table);
}

// select on change
$(document).on("change", "select#count_select", function(e) {
    if (_urls != null) {
        show_images();
    }
});

// triggered when time type changed
$(document).on("change", "select#time_type", function(e) {
    if ($(this).val() == "Manual") {
        var oneHourAgoTime = get_time_str(-1);
        var nowTime = get_time_str(0);
        $("input#start_tm").val(oneHourAgoTime);
        $("input#end_tm").val(nowTime);
        $("input#start_tm").attr("disabled", false);
        $("input#end_tm").attr("disabled", false);

    } else {
        $("input#start_tm").val("");
        $("input#end_tm").val("");
        $("input#start_tm").attr("disabled", true);
        $("input#end_tm").attr("disabled", true);
    }
});

$(document).on("click", "a.apply", function(e) {
    var tds = $("td:not(.apply)", $(this).parent().parent());
    var new_row = get_row_html_str($(tds[0]).html(), $(tds[1]).html(), $(tds[2]).html(), $(tds[3]).html(), $(tds[4]).html());
     
    $("tbody#analyzer_input").append(new_row);
    e.preventDefault();
});

// for user input
function get_row_html_str(host, dir, product, serverType, cubId) {
    return "<tr>\n" +
        "<td class='hostname'><input type='text' value='" + host + "' /></td>\n" +
        "<td class='ana_path'><input type='text' value='" + dir + "' /></td>\n" +
        "<td class='product'><input type='text' value='" + product + "' /></td>\n" +
        "<td class='serv_type'><input type='text' value='" + serverType + "' /></td>\n" +
        "<td class='cub_id'><input type='text' value='" + cubId + "' /></td>\n" +
        "<td class='op'><a href='' class='add_row'>添加行</a> <a href='' class='del_row'>删除行</a></td>\n" +
        "</tr>\n";
}

// get time with format: yyyy-mm-dd hh:mm
function get_time_str(hours_delta) {
    var today = new Date();
    var ret_date = new Date(today.getFullYear(), today.getMonth(), today.getDate(), today.getHours() + hours_delta, today.getMinutes());
    return ret_date.getFullYear() + "-" + (ret_date.getMonth() + 1) + "-" + ret_date.getDate() + " " + ret_date.getHours() + ":" + ret_date.getMinutes();
}

// collect info for request perf images
function get_request_info_str() {
    var reqObj = {
        "inputList" : [],
        "machine_info": [], 
        "time_start": null,
        "time_end": null,
        "time_type": null,
    };

    // loop for each row in tbody#analyzer_input
    var trs = $("tbody#analyzer_input tr");
    if (trs.length == 0) {
        alert("Error: 请先输入相关信息!");
        return false;
    }

    var ret = true;
    $.each(trs, function(idx, tr) {
        var inputs = $("td input", $(tr));
        var hostname  = $(inputs[0]).val();
        var ana_path  = $(inputs[1]).val();
        var product   = $(inputs[2]).val();
        var serv_type = $(inputs[3]).val();
        var cube_id   = $(inputs[4]).val();

        if (hostname == "") {
            alert("Error: 机器名不能为空!");
            ret = false;
            return ret;

        } else if (ana_path == "") {
            alert("Error: Analyzer路径不能为空!");
            ret = false;
            return ret;
        }

        // request input list
        reqObj["inputList"].push([hostname, ana_path, product, serv_type, cube_id]);
    });

    if (ret == false) {
        return ret;
    }

    // loop for each row in tbody#analyzer_input
    var chkboxes = $("tbody#machine_info_tbody td input:checked");
    $.each(chkboxes, function(idx, input) {
        var key = $(input).attr("name");
        var name = $(input).attr("for").replace(/ /g, "_");
        reqObj["machine_info"].push(key + ":" + name);
    });

    // get time of begin and end
    var time_str = $("select#time_type").val();
    var begin_tm = 0;
    var end_tm = get_time_str(0);
    var time_type = null;

    if (time_str == "Hour") {
        begin_tm = get_time_str(-1);
        time_type = "hour";

    } else if (time_str == "8 Hours") {
        begin_tm = get_time_str(-8);
        time_type = "8hour";

    } else if (time_str == "Day") {
        begin_tm = get_time_str(-24);
        time_type = "day";

    } else if (time_str == "Week") {
        begin_tm = get_time_str(-24 * 7);
        time_type = "week";

    } else if (time_str == "Month") {
        begin_tm = get_time_str(-24 * 30);
        time_type = "month";

    } else if (time_str == "Manual") {
        begin_tm = $("input#start_tm").val();
        end_tm = $("input#end_tm").val();
        time_type = "manual";
    }
    
    reqObj["time_start"] = begin_tm;
    reqObj["time_end"] = end_tm;
    reqObj["time_type"] = time_type;

    return $.toJSON(reqObj);
}
