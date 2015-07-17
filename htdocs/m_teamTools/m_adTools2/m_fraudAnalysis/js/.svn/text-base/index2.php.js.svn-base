$(function() {
    var phpFile = "./php/loadAll.php";
    $("div#log_table").load(phpFile);

    $("select#date_select").change(function(e) {

        if ($(this).val() == "数据汇总") {
            var phpFile = "./php/loadAll.php";
            $("div#log_table").load(phpFile);

        } else {
            var log_file = $(this).find("option:selected").attr("id");
            $("div#log_table").html("").load(log_file);
        }
    });    

    $(document).on("click", "a", function(e) {
        //refer to http://developer.51cto.com/art/201009/226675.htm
        var href = $(this).attr("href");
        $("table").hide();
        var $table = $("table#" + href);
        $table.show();
        
        // if ($("select#date_select").val() != "数据汇总" && ! $table.hasClass("added")) {
        if (! $table.hasClass("added") && href != "Exception" && href != "ClickException" && href != "ClickInvalid" && href != "click404") {
            $table.addClass("added");

            var trs = $("tr", $table);
            var colspan = $("th", $(trs[0])).attr("colspan");
            colspan = parseInt(colspan);
            $("th", $(trs[0])).attr("colspan", 1 + colspan);
            
            $(trs[1]).append("<th>过滤比率</th>");

            $.each(trs, function(idx, tr) {
                if (idx <= 1) return true;
                var td_filtered = $("td", tr)[colspan - 2];
                var td_valid    = $("td", tr)[colspan - 1];
                var filtered_all = parseInt($(td_filtered).html());
                var valid_all    = parseInt($(td_valid).html());

                var rate = filtered_all / (filtered_all + valid_all);
                var rate2 = "" + rate
                if (rate2.length <= 2) {
                    rate2 = rate2.substr(2, 2);
                } else {
                    rate2 = rate2.substr(2, 2) + "." + rate2.substr(4, 2);
                }
        
                $(tr).append("<td>" + rate2 + "%</td>");
            });
        }

        e.preventDefault();
    });
});
