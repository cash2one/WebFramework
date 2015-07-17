$(function() {
    var php_file = "./php/read_content.php";
    load_content();

    $("select").change(function(e) {
        $("div#content").html("<center><bold><font color='red'>loading...</font></bold></center>");
        load_content();
    });

    function load_content() {
        var $selected_item = $("select").find("option:selected");
        if ($selected_item.html() == undefined) return false;
        $.get(php_file, {"html_file": "../python/output/" + $selected_item.attr("id")}, function(data) {
            $("div#content").html(data);
        });
    }

    $("input#acc_check_btn").click(function(e) {
        $(this).attr("disabled", true);
        var month_str = $("input#acc_check").val();
        var php_file = "./php/create_result.php";
        $.get(php_file, {"month_str": month_str}, function(data) {
            location.reload();
        });

        $("label#info").html("<font color='red'>请稍等一会儿!对账脚本运行结束之后，页面会自动刷新...</font>");
    });

    $("a#manual_acc_check").click(function(e) {
        var yearMonth = $("input#acc_check").val();
        var manual_trigger_url = 'http://nc111x.corp.youdao.com:49991/finance/qas/revenueSummaryController.do?yearMonth=' + yearMonth + '＆＆operateType=recalculate';
        var ret = confirm("提示：确认手动运行对账程序(对账月份：" + yearMonth + ")");
        if (!ret) return false;
        window.open(manual_trigger_url);
        e.preventDefault();
    });
});
