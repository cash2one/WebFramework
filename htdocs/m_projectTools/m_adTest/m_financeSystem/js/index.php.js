$(function() {
    // load pages
    $("div#query_delete_tbl_content").load("./php/query_delete_tbl.php");
    $("div#data_record_content").load("./php/data_record.php");
    $("div#result_check_content").load("./php/result_check.php");

    $("div.content").hide();
    $("div#query_delete_tbl_content").show();

    // click on nav links
    $("a.nav_link").click(function(e) {
        $("div.content").hide();

        self_id = $(this).attr("id");
        if (self_id == "query_tbl") {
            $("div#query_delete_tbl_content").show();
            $("span.query_table").show();
            $("span.delete_table").hide();
            $("a.delete_table").hide();
            $("a.query_table").show();

        } else if (self_id == "delete_tbl") {
            $("div#query_delete_tbl_content").show();
            $("span.query_table").hide();
            $("span.delete_table").show();
            $("a.delete_table").show();
            $("a.query_table").hide();

        } else if (self_id == "data_record") {
            $("div#data_record_content").show();

        } else if (self_id == "result_check") {
            $("div#result_check_content").show();

        } else if (self_id == "auto_settlement") {

        }
        
        e.preventDefault();
    });

    // click on auto_settlement
    $("a#auto_settlement").click(function(e) {
        alert('123');
        e.preventDefault();
    });

})
