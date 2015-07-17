$(function() {
    $("textarea#k_value").mouseup(function(e) {
        show_result();
        e.stopPropagation();
    });

    $("textarea#k_value").keyup(function(e) {
        show_result();
        e.stopPropagation();
    });

    function show_result() {
        var url = $("textarea#k_value").val();
        if (url == "") return false;

        var php_file = "./php/get_info.php";
        $("div#result").html("请稍等...");
        $.get(php_file, {"url": url}, function(ret) {
            $("div#result").html(ret);
        });
    }

    $("textarea#k_value").focus(function(e) {
        var content = $(this).val();
        if (content == "输入点击URL") {
            $(this).val("");
        }
    });

    $("textarea#k_value").blur(function(e) {
        var content = $(this).val();
        if (content == "") {
            $(this).val("输入点击URL");
        }
    });
});
