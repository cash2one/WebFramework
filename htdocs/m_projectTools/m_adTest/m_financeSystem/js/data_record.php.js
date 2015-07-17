$(function() {
    $("div.rd_content").hide();
    $("div#svn_set").show();

    $("a.rd_link").click(function(e) {
        $("div.rd_content").hide();
        var self_id = $(this).attr("id");
        if (self_id == "set_senario") {
            $("div#svn_set").show();

        } else if (self_id == "arch_status") {
            $("div#rd_content_set").show();

        } else if (self_id == "apply_status") {
            $("div#rd_content_apply").show();
        }

        e.preventDefault();
    });

});
