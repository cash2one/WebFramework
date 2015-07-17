//triggered when document ready
$(function() {

    var _left1 = 0 , _top1 = 0;
    var _left2 = 0, _top2 = 0;

    //just for debug
    // var code_v1 = $("input#svn_v1").val("https://dev.corp.youdao.com/svn/outfox/products/ad/ead/tags/click-unification-pre0");
    // var code_v2 = $("input#svn_v2").val("https://dev.corp.youdao.com/svn/outfox/products/ad/ead/tags/click-unification-pre2");
    $("input#svn_src").focus();

    $("div#diff_result2").hide();

    $("input[type=text]").attr("title", "输入格式：https://dev.corp.youdao.com/svn/outfox/products/ad/adstat/branches/mgen-2.0.1@368231");
    $("input#skip_test_files[type=checkbox]").attr("title", "忽略java单元测试文件");

    var log_file = "./php/readLog.php";
    $("div#log_info tbody").load(log_file);

    $(document).keydown(function(event){ 
        if (event.keyCode == 37) {       // <- key
            if ($("div#diff_result").is(":visible")) return false;

            save_scollbars_status(1);
            $("div#diff_result2").hide();
            $("div#diff_result").show();
            set_scrollbars_status(1);
        } else if(event.keyCode == 39) { // -> key
            if ($("div#diff_result2").is(":visible")) return false;

            save_scollbars_status(2);
            $("div#diff_result").hide();
            $("div#diff_result2").show();
            set_scrollbars_status(2);
        }
    }); 

    function save_scollbars_status(type) {
        var top = window.pageYOffset || document.documentElement.scrollTop;
        var left = window.pageXOffset || document.documentElement.scrollLeft;

        if (type == 1) {
            // show left 
            _top2 = top;
            _left2 = left;

        } else if (type == 2) {
            // show right
            _top1 = top;
            _left2 = left;
        }
    }

    function set_scrollbars_status(type) {
        if (type == 1) {
            window.scrollTo(_left1, _top1);
        } else if(type == 2) {
            window.scrollTo(_left2, _top2);
        }
    }

});

// triggered after user login
function after_login() {
    // read user input
    var code_src = $("input#svn_src").val();
    var code_v1 = $("input#svn_v1").val();
    var code_v2 = $("input#svn_v2").val();
    var skip_test_files = $("input#skip_test_files").attr("checked") == "checked" ? 1 : 0;
    var simple_view = $("input#simple_view").attr("checked") == "checked" ? 1 : 0;
    var same_update_view = $("input#same_update_view").attr("checked") == "checked" ? 1 : 0;

    if (code_v1 == "" || code_v2 == "" || code_src == "") {
        alert("Error: Input NOT complete !");
        return false;
    }

    $("input#request_btn").attr("disabled", true);
    $("div#log_info").html("");
    $("div#diff_result").html("<b><font color='red'>请稍等一会儿...</font></b>");

    var request_file = "./php/showDiff.php"; 
    // _ldap & _passwd set in ldap_login.js
    // $("div#diff_result").load(request_file, {"code0": code_src, "code1": code_v1, "code2": code_v2, "ldap": _ldap, "passwd": _passwd, "skip_test_files": skip_test_files, "simple_view": simple_view, "same_update_view": same_update_view}, function(data){
    $.post(request_file, {"code0": code_src, "code1": code_v1, "code2": code_v2, "ldap": _ldap, "passwd": _passwd, "skip_test_files": skip_test_files, "simple_view": simple_view, "same_update_view": same_update_view}, function(data){
        if (simple_view == true) {
            $("div#diff_result").html(data);
        } else {
            var fields = data.split(//);
            $("div#diff_result").html(fields[0]);
            $("div#diff_result2").html(fields[1]);
        }
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
    var url0 = $("div[name='src_ver']", svn_td).html();
    var url1 = $("div[name='old_ver']", svn_td).html();
    var url2 = $("div[name='new_ver']", svn_td).html();
    $("input#svn_src").val(url0);
    $("input#svn_v1").val(url1);
    $("input#svn_v2").val(url2);
    e.preventDefault();
});
