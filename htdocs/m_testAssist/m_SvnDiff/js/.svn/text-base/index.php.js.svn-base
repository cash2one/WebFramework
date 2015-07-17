//triggered when document ready
$(function() {
    //just for debug
    // var code_v1 = $("input#svn_v1").val("https://dev.corp.youdao.com/svn/outfox/products/ad/ead/tags/click-unification-pre0");
    // var code_v2 = $("input#svn_v2").val("https://dev.corp.youdao.com/svn/outfox/products/ad/ead/tags/click-unification-pre2");

    $("input[type=text]").attr("title", "输入格式：https://dev.corp.youdao.com/svn/outfox/products/ad/adstat/branches/mgen-2.0.1@368231");
    $("input#skip_test_files[type=checkbox]").attr("title", "忽略java单元测试文件");

    var log_file = "./php/readLog.php";
    $("div#log_info tbody").load(log_file);
});

// triggered after user login
function after_login() {
    // read user input
    var code_v1 = $("input#svn_v1").val();
    var code_v2 = $("input#svn_v2").val();
    var skip_test_files = $("input#skip_test_files").attr("checked") == "checked" ? 1 : 0;
    var show_diff_detail = $("input#show_diff_detail").attr("checked") == "checked" ? 1 : 0;

    if (code_v1 == "" || code_v2 == "") {
        alert("Error: Input NOT complete !");
        return false;
    }

    $("input#request_btn").attr("disabled", true);
    $("div#log_info").html("");
    $("div#diff_result").html("<b><font color='red'>请稍等一会儿...</font></b>");

    var request_file = "./php/showDiff.php"; 
    // _ldap & _passwd set in ldap_login.js
    $("div#diff_result").load(request_file, {"code1": code_v1, "code2": code_v2, "ldap": _ldap, "passwd": _passwd, "skip_test_files": skip_test_files, "show_diff_detail": show_diff_detail}, function(data){
        $("input#request_btn").attr("disabled", false);
    });
}

//triggered by click on "查看" 按钮
$(document).on("click", "input#request_btn", function(e) {
    var ldap_login_check_php = "../../php-base/ldap_login_check.php";

    if (_ldap == null || _passwd == null) {
        ldap_login(ldap_login_check_php, after_login);

    } else {
        after_login();
    }
});

$(document).on("click", "tr td a", function(e) {
    var svn_td = $(this).parent().prev();
    var url1 = $("div[name='old_ver']", svn_td).html();
    var url2 = $("div[name='new_ver']", svn_td).html();
    $("input#svn_v1").val(url1);
    $("input#svn_v2").val(url2);
    e.preventDefault();
});
