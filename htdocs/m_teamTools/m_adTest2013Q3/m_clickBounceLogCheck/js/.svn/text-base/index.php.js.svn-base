$(function() {

    $("#tabs").tabs();

    $("input#check").click(function(e) {
        var php_file = "./php/getResult.php";
        var cb_log = $("textarea#cb_log").val();
        var click_log = $("textarea#click_log").val();
        $("div#tabs-2").html("").load(php_file, {"cb": cb_log, "click": click_log}, function(data) {
            $("#tabs").tabs("option", "active", 1);
        });
    });

    $("a#try_it").click(function(e) {
        var ret = confirm("确定载入?");
        if (ret != true) return false;

        $.getJSON("./php/load_data.php", function(data) {
            $("textarea#cb_log").val(data[0]);
            $("textarea#click_log").val(data[1]);
        });

        e.preventDefault();
    });
});
