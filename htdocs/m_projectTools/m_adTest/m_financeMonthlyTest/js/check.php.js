$(function() {
    var $select1 = $("select#senario_1");
    var $select2 = $("select#senario_2");

    var $file_first = null;
    var $file_last  = null;
    
    $("a").addClass("gray");

    // load selected case files
    function load_case_files() {
        var filename1 = $("option:selected",  $select1).attr("name");
        var filename2 = $("option:selected",  $select2).attr("name");
        $("a").removeClass("highlight").addClass("gray");

        $(this).attr('disabled', true);
        var php_file = "./php/load_files.php";
        $.getJSON(php_file, {"filename1": filename1, "filename2": filename2}, function(data) {
            $file_first = $(data.table_first); 
            $file_last  = $(data.table_last);
            $("a").removeClass("gray").addClass("highlight");

            $('div#content table td').html("");
        });
    }

    var php_file = "./php/read_html_filenames.php";
    $.getJSON(php_file, {}, function(data) {
        $select1.html("");
        $select2.html("");

        var filenames = data.filenames;
        $.each(filenames, function(idx, pair) {
            state_name = pair[0];
            filename = pair[1];
            var id   = filename.substr(0, 15);
            $select1.append("<option id='" + id +"' name='" + filename + "'>" + state_name + "</option>");
            $select2.append("<option id='" + id +"' name='" + filename + "'>" + state_name + "</option>");
        })

        load_case_files();
    });

    $('a.table_link').click(function(e) {
        if ($file_first == null || $file_last == null) {
            alert("Error: NOT loaded, 请点击载入");
            return false;
        }

        $('div#content table td').html("");

        var table_name = $(this).html();
        var table_first = $("." + table_name, $file_first).clone();
        var table_last  = $("." + table_name, $file_last).clone();

        // color diff cells
        var $tb_first = $(table_first);        
        var $tb_last =  $(table_last);

        var rows1 = $("tr", $tb_first);
        var rows2 = $("tr", $tb_last);
        var tr1_cnt = rows1.length;
        var tr2_cnt = rows2.length;
        var tr_cnt = tr1_cnt > tr2_cnt? tr2_cnt : tr1_cnt;
        for (i = 0; i < tr_cnt; i++) {
            var tds1 = $("td", rows1[i]);
            var tds2 = $("td", rows2[i]);
            var td1_cnt = tds1.length;
            var td2_cnt = tds2.length;
            var td_cnt = td1_cnt > td2_cnt ? td2_cnt : td1_cnt;
            for (j = 0; j < td_cnt; j++) {
                var $td1 = $(tds1[j]);
                var $td2 = $(tds2[j]);

                if ($td1.html() != $td2.html()) {
                    $td1.addClass("update");
                    $td2.addClass("update");
                }
            }

            if (td1_cnt > td2_cnt) {
                for (j = td2_cnt; j < td1_cnt; j++) {
                    $(tds1[j]).addClass("delete");
                }
            } else {
                for (j = td1_cnt; j < td2_cnt; j++) {
                    $(tds2[j]).addClass("add");
                }
            }
        }

        if (tr1_cnt > tr2_cnt) {
            for (i = tr2_cnt; i < tr1_cnt; i++) {
                $(rows1[i]).addClass("delete");
            }

        } else {
            for (i = tr1_cnt; i < tr2_cnt; i++) {
                $(rows2[i]).addClass("add");
            }
        }

        if (table_first.hasClass("vertical")) {
            $("table#vertical td.first").html(table_first);
            $("table#vertical td.last").html(table_last);

        } else {
            $("table#horizontal td.first").html(table_first);
            $("table#horizontal td.last").html(table_last);
        }

        e.preventDefault();
    });

    $("select").change(function(e) {
        load_case_files();
    });
});
