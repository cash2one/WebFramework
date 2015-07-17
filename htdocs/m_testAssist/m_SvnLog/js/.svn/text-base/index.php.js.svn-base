// triggered when document ready
$(function() {
    // just for debug
    // $("input#svn_url_input").val("https://dev.corp.youdao.com/svn/outfox/products/ad/adstat/branches/mgen-2.0.1"); 

    var log_file = "./php/readLog.php";
    $("div#log_info tbody").load(log_file);
});

function trim(str) {
    return str.replace(/(^\s*)|(\s*$)/g, "");
}

// called afer query-log button clicked
function show_result() {
    var query_log_php = "./php/query_log.php";
    var svn_url = $("input#svn_url_input").val();
    svn_url = trim(svn_url);
    if (svn_url == "") {
        alert("Warn: Svn Url Empty");
        return false;
    }

    $("input#query_log").attr("disabled", true);
    $("tbody#log-list").html("<tr><td colspan='5'><b><font color='red'>请稍等一会儿...</font></b></td></tr>");

    // clean the log
    $("div#log_info").html("");

    $.post(query_log_php, {"svn_url": svn_url, "ldap": _ldap, "passwd": _passwd}, function(data) {
        $("tbody#log-list").html(data);
        $("input#query_log").attr("disabled", false);
    });
}

// triggered by click on "查询Svn Log" 按钮
$(document).on("click", "input#query_log", function(e) {
    var ldap_login_check_php = "../../php-base/ldap_login_check.php";

    if (_ldap == null || _passwd == null) { 
        ldap_login(ldap_login_check_php, show_result);

    } else {
        show_result();
    }
});

// triggered when checkboxes are checked
$(document).on("click", "input.choice", function(e) {
    var checked_count = $("input.choice:checked").length;

    if (checked_count < 2) {
        $("input#query_diff").attr("disabled", true);

    } else if (checked_count == 2) {
        $("input#query_diff").attr("disabled", false);
        $("input#query_log").attr("disabled", "disabled");

    } else {
        $(this).attr("checked", false);
        alert("Warn: You could ONLY select TWO versions to view diff");
    }
});

// triggered by click on "查询Svn Diff" 按钮
$(document).on("click", "input#query_diff", function(e) {
    var url = $("input#svn_url_input").val();  
    // trim heading and tailing spaces
    url = trim(url);
    if (url == "") {
        alert("Warn: Svn Url Empty");
        return false;
    }

    // hide user input area
    $("a#user_input_a").trigger("click");
    $("div#output_div").html("<b><font color='red'>请稍等一会儿...</font></b>")

    // 新的在前面
    var version1 = $("input.choice:checked:last").parent().next().html();
    var version2 = $("input.choice:checked:first").parent().next().html();

    $(this).attr("disabled", true);
    var request_file = "./php/showDiff.php"; 
    // _ldap & _passwd set in ldap_login.js
    $("div#output_div").load(request_file, {"url": url, "version1": version1, "version2": version2, "ldap": _ldap, "passwd": _passwd, "skip_test_files": 0}, function(data){
        // "this" NOT mean the above button any more
        $("input#query_diff").attr("disabled", false);
    });
});

$("a#user_input_a").click(function(e) {
    var open_tag = "&gt;&gt;&gt;";
    var close_tag = "&lt;&lt;&lt;";
    var text = $(this).html();

    if (text == open_tag) {
        $(this).html(close_tag);
        $(this).attr("title", "点击我来显示表格");
        $("table#log_tbl").hide();

    } else {
        $(this).html(open_tag);
        $(this).attr("title", "点击我来隐藏表格");
        $("table#log_tbl").show();
    }

    e.preventDefault();
});

$("a#show_result_a").click(function(e) {
    var open_tag = "&gt;&gt;&gt;";
    var close_tag = "&lt;&lt;&lt;";
    var text = $(this).html();

    if (text == open_tag) {
        $(this).html(close_tag);
        $(this).attr("title", "点击我来显示Diff结果");
        $("div#output_div").hide();

    } else {
        $(this).html(open_tag);
        $(this).attr("title", "点击我来隐藏Diff结果");
        $("div#output_div").show();
    }

    e.preventDefault();
});
