$(function() {
    load_test_machines();
    load_online_machines();
    load_date();

    function load_test_machines() {
        var test_hosts = ["hs014", "nb092", "nb093", "nb292", "nb293", "nc024", "nc044", "nc069", "nc070", "nc107", "nc108", "nc109", "nc111", "qt101", "qt102", "qt103", "qt104", "qt105", "qt106"];
        var option_str = "<option>" + test_hosts.join("x.corp.youdao.com</option><option>") + "x.corp.youdao.com</option>";
        $("select#test_host").html(option_str);
    }

    function load_online_machines() {
        var online_hosts = ["d", "m", "u", "s", "p"];
        var option_str = "<option>" + online_hosts.join(".clkservice.youdao.com</option><option>") + ".clkservice.youdao.com</option>";
        $("select#online_host").html(option_str);
    }

    function load_date() {
        var php_file = "./php/load_date.php";
        $("select#service_select").html("");
        $.get(php_file, function(data) {
            $("select#date_select").html(data);
            load_service();
        });
    }

    function load_service() {
        var php_file = "./php/load_service.php";
        var date_str = $("select#date_select").val();
        $("table#click_table").html("");
        $.get(php_file, {"date": date_str}, function(data) {
            $("select#service_select").html(data);
            load_data();
        });
    }

    function load_data() {
        var php_file = "./php/load_data.php";
        var date_str = $("select#date_select").val();
        var serv_str = $("select#service_select").val();
        $.get(php_file, {"date": date_str, "service": serv_str}, function(data) {
            $("table#click_table").html(data);
        });
    }

    $(document).on("click", "a", function(e) {
        $(this).css({"color": "green"});
        var $radio_checked = $("input[type=radio]:checked");
        var radio_id = $radio_checked.attr("id"); 

        var host = "";
        if (radio_id == "select_test") {
            host = $("select#test_host").val(); 

        } else if (radio_id == "select_online") {
            host = $("select#online_host").val(); 

        } else if (radio_id == "user_input") {
            host = $("input#host2").val(); 
        }
        var port = $("#port").val();

        var link = "";
        if (port != "") {
            var link = "http://" + host + ":" + port + $(this).attr("href");
        } else {
            var link = "http://" + host + $(this).attr("href");
        }
        $("label#url").html(link);
        var checked = $("input#open_new").attr("checked");
        if (checked == "checked") {
            window.open(link, "ad_page");
        } else {
            $("iframe").attr("src", link);
        }

        e.preventDefault();
    });

    $("select#date_select").change(function(e) {
        load_service();
    });

    $("select#service_select").change(function(e) {
        load_data();
    });

    $("input#select_online").click(function(e) {
        $("input#port").val(""); 
    });
});
