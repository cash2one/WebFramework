$(function() {
    function get_time_str(hours_delta) {
        var today = new Date();
        var ret_date = new Date(today.getFullYear(), today.getMonth(), today.getDate(), today.getHours() + hours_delta, today.getMinutes());
        return ret_date.getFullYear() + "-" + (ret_date.getMonth() + 1) + "-" + ret_date.getDate() + " " + ret_date.getHours() + ":" + ret_date.getMinutes();
    } 

    $("span#manual_detail").hide();
    $( "input#start_date" ).datepicker({'dateFormat': "mm/dd/yy"});
    $( "input#end_date" ).datepicker({'dateFormat': "mm/dd/yy"});

    function init() {
        $("table#image_area").html("");
        $("table#output tbody input").attr("checked", false);
    }

    $("select#period").change(function(e) {
        init();
        var val = $(this).val();
        if (val == "manual") {
            $("span#manual_detail").show();
        } else {
            $("span#manual_detail").hide();
        }
    });

    $("select#users").change(function(e) {
        init();
        load_perf_info();
    });
    load_perf_info();

    $("select#count_in_row").change(function(e) {
        init();
    });

    $(document).on("click", "table#output tbody input", function(e) {
        $("table#image_area").html("");
        load_images();
    });

    $(document).on("click", "table#image_area input", function(e) {
        $(this).parent().parent().toggleClass("img-hide");
    });

    $("a#download").click(function(e) {
        var tds = $("table#image_area td:not([class='img-hide'])");
        var img_urls = [];

        $.each(tds, function(idx, td) {
            img_urls.push($("img ", td).attr('src'));
        });

        var url_list_str = img_urls.join(";");
        var php_file = "./php/download.php";
        var cnt_in_row = $("select#count_in_row").val();
        $.get(php_file, {"url": url_list_str, "cnt_in_row": cnt_in_row}, function(status_msg) {
            var fields = status_msg.split(/:/, 2);
            if (fields[0] == "0") {
                alert(fields[1]);
            } else {
                window.open(fields[1], "_self");
            }
        });

        e.preventDefault();
    });
    
    function load_perf_info() {
        $("table#output tbody").html("");

        var user = $("select#users").val();
        var php_file = "./php/load_perf_info.php";
        $.get(php_file, {"user": user}, function(data) {
            $("table#output tbody").html(data);
        });
    }

    function load_images() {
        var user = $("select#users").val();
        var cnt_in_row = $("select#count_in_row").val();
        var period = $("select#period").val();
        var start_ts = $("input#start_date").val() + "," + $("input#start_ts").val().replace(/:/, ",");
        var end_ts = $("input#end_date").val() + "," + $("input#end_ts").val().replace(/:/, ",");

        var filenames = [];
        $.each($("table#output tr td input:checked"), function(idx, input) {
            var tds = $("td", $(input).parent().parent());
            var filename = $(tds[0]).html() + ":" + $(tds[1]).html() + ":" + $(tds[2]).html();
            filenames.push(filename);
        });

        var php_file = "./php/load_images.php";
        $.get(php_file, {"user": user, "info": filenames.join(";"), "count_in_row": cnt_in_row, "period": period, "start_ts": start_ts, "end_ts": end_ts}, function(data) {
            $("table#image_area").html(data); 
        });
    }
});
