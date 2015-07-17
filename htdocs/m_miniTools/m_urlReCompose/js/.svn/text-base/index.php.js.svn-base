$(function() {
    /*
    $("textarea#input_text").mouseup(function(e) {
        show_result();
        e.stopPropagation();
    });
    $("textarea#input_text").keyup(function(e) {
        show_result();
        e.stopPropagation();
    });
    */

    $("textarea#input_text").focus(function(e) {
        var content = $(this).val();
        if (content == "输入URL") { 
            $(this).val("");
        }
    });

    $("textarea#input_text").blur(function(e) {
        var content = $(this).val();
        if (content == "") { 
            $(this).val("输入URL");
        }
    });

    $("input#convert_btn").click(function(e) {
        show_result();
    });

    function show_result() {
        var url = $("textarea#input_text").val(); 
        if (url == "") return false;

        var fields = url.split(/[\?&]/);
        $("table").html("<tr><th colspan='2'>url及参数</th><th>忽略该参数</th></tr>");
        
        for (var i = 0; i < fields.length; i++) {
            var equal_sign_index = fields[i].indexOf("=");
            if (equal_sign_index == -1) {
                $("table").append("<tr><td colspan='3'><input style='width:100%' type=text value='" + fields[i] + "' /></td></tr>");
            } else {
                var kv = fields[i].split(/=/, 2);
                $("table").append("<tr><td style='width:90px'><b>" + kv[0] + "</b></td><td><input style='width:100%' type=text value='" + kv[1] + "' /></td><td style='text-align:center; width:90px'><input type=checkbox></td></tr>");
            }
        }

        show_result2();
    }

    $(document).on("keyup mouseup click", "table tr td input", function(e) {
        show_result2();
    });

    function show_result2() {
        var url = ""
        var trs = $("table tr");
        var index = 0;

        $.each(trs, function(idx, tr) {
            var tds = $("td", tr);
            if (tds.length == 1) {
                index = 1;
                url += $("input", tds[0]).val();
            } else if(tds.length == 3) {
                var key = $("b", tds[0]).html();
                var value = $("input", tds[1]).val(); 
                var ignore = $("input", tds[2]).attr("checked");
                if (ignore == "checked") return true; // means continue

                if (index == 1) {
                    url += "?" + key + "=" + value;
                } else {
                    url += "&" + key + "=" + value;
                }

                index = 2;
            }
        });

        $("a#o_a").attr('href', url).html(url);
    }
});
