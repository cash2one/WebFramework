$(function() {
    // === 变量定义区
    var _start_row_idx = 1;
    var _items_count_per_page = 50;
    var _items_count = -1;

    // === 初始化区域
    $("input#start_time").datepicker({"dateFormat": "yy-mm-dd" });
    $("input#end_time").datepicker({"dateFormat": "yy-mm-dd" });

    // === 函数定义区
    function log_it(type, msg) {
        $("div#infoBar").html("<span class='" + type + "'>" + msg + "</span>");
    }
    function error(msg) {
        log_it("error", msg);
    }
    function info(msg) {
        log_it("info", msg);
    }
    function warn(msg) {
        log_it("warn", msg);
    }

    function requestData() {
        var startTime = $("input#start_time").val();
        var endTime   = $("input#end_time").val();
        var issueType = $("select#type").val();
        _items_count_per_page = $("input#itemsInPage").val();

        $.getJSON("./php/resultList.php", {"startRowIdx": _start_row_idx, "itemsInPage": _items_count_per_page, "startTime": startTime, "endTime": endTime, "issueType": issueType}, function(data) {
            if (data["ret"] == 1) {
                alert("Error: " + data["msg"]);
                return false;
            }
            $("div#content tbody").html(data["content"]);
            _items_count = data["count"];
        });
    }

    // === 事件处理区
    $("input#query").click(function(e) {

    });
});
