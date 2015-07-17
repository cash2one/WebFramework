$(function() {
    $.each($("div#tabs div"), function(idx, div) {
        var id = $(div).attr("id");
        var php_file = "./php/pages/" + id.replace("tabs-", "") + ".php";
        $(div).load(php_file);
    });

    $("a#toOld").click(function(e) {
        var ret = confirm("老版本有问题，且无人维护，确定使用?");
        if (ret != true) {
            e.preventDefault();
        }
    });
});
