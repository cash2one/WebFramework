$(function() {
    var _cur_id = -1;
    var _refer_name = "";
    var service_url = "http://tb037x.corp.youdao.com:28081/m_teamTools/m_adTest2013Q3/m_dbQueryTool";
    $("div.container").hide();
    $("div#filter_set_area").hide();

    $("div#func_idx a.query_li").click(function(e) {
        _cur_id = $(this).data("id");
        _refer_name = $(this).data("refer_name");
        $("div.container").hide();
        $("div.container[name='" + _cur_id + "']").show();

        var cond_area = $("div.container[name='" + _cur_id + "'] div.cond_area");
        if (cond_area.hasClass("loaded") == false) {
            cond_area.html("").load("./php/show_cond.php", {"id":_cur_id, "refer_name":_refer_name});
            cond_area.addClass("loaded");
        }
        e.preventDefault();
    });

    $(document).on("click", "input[type='button']", function(e) {
        var table = $(this).parents('table');
        var trs = $("tr.input_val", table);

        var value_list_str = "";
        $.each(trs, function(idx, tr) {
            var cond_val = $("td input", $(tr)).val();
            if (idx == 0) {
                value_list_str = cond_val;
                return true; // work as continue
            }
            value_list_str += "" + cond_val;
        });

        $("div#func_area div.container[name='" + _cur_id + "'] div.result_area").html("").load("./php/query_result.php", {"id":_cur_id, "refer_name":_refer_name, "cond_values_str": value_list_str});
    });

    $("a#set_filter").toggle(
        function(e) {
            $("div#filter_set_area").show();
            e.preventDefault();
        }, 
        function(e){
            $("div#filter_set_area").hide();
            e.preventDefault();
        }
    );

    $("input#do_filter").click(function(e) {
        var user = $("select#filter_by_user").val();
        var host = $("select#filter_by_db").val();
        window.location.href = service_url + "?filter_user=" + user + "&filter_host=" + host;
    });
});
