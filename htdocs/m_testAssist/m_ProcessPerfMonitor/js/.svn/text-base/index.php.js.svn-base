$(function() {
    var _datasets = null;
    var _min = -1;
    var _max = -1;

    $('span#filter').hide();

    $("select#machine_list").change(function(e) {
        fill_date_select($(this).val());
    });

    $("select#date_list").change(function(e) {
        var hostname = $("select#machine_list").val();
        var date = $(this).val();
        var vaquero_checked = $("input#ignore_vaquero").attr("checked") == "checked" ? 1 : 0;
        fill_proc_select(hostname, date, vaquero_checked);
    });

    $("input#ignore_vaquero").click(function(e) {
        var hostname = $("select#machine_list").val();
        var date = $("select#date_list").val();
        var vaquero_checked = $("input#ignore_vaquero").attr("checked") == "checked" ? 1 : 0;
        fill_proc_select(hostname, date, vaquero_checked);
    });

    $("a#refresh").click(function(e) {
        show_graphs(); 
        e.preventDefault();
    });

    $("select#process_list").change(function(e) {
        show_graphs(); 
    });

    var hostname = $("select#machine_list").val();
    fill_date_select(hostname);

    function fill_date_select(hostname) {
        var php_file = "./php/load_dates.php";
        $.get(php_file, {"hostname": hostname}, function(data) {
            $("select#date_list").html(data);

            var date_str = $("select#date_list").val();
            var vaquero_checked = $("input#ignore_vaquero").attr("checked") == "checked" ? 1 : 0;
            fill_proc_select(hostname, date_str, vaquero_checked);
        });
    }

    function fill_proc_select(hostname, date_str, ignore_vaquero) {
        var proc_name = $("select#process_list").val();

        var php_file = "./php/load_procs.php";
        $.get(php_file, {"hostname": hostname, "date": date_str, "ignore_vaquero": ignore_vaquero}, function(data) {
            $("select#process_list").html(data);

            $.each($("select#process_list option"), function(idx, opt) {
                if (proc_name == $(opt).html()) {
                    $(opt).attr("selected", "selected");
                    return false;
                }
            }); 

            show_graphs();
        });
    }

    function show_graphs() {
        var hostname = $("select#machine_list").val();
        var date = $("select#date_list").val();
        var proc_name = $("select#process_list").val();
        var php_file = "./php/load_dataset.php";
        $.getJSON(php_file, {"hostname": hostname, "date": date, "proc_name":proc_name}, function(data) {
            // alert($.toJSON(data));
            _datasets = data[1];
            draw_graphs();
            $("div#content p#cmd").html(data[0]);
        });
    }

    // ======================================================================================
    // 画图相关逻辑
    // ======================================================================================
    var choiceContainer = $("#choices");

    function draw_graphs() {
        // hard-code color indices to prevent them from shifting as
        // countries are turned on/off
        var i = 0;
        $.each(_datasets, function(key, val) {
            val.color = i;
            ++i;
        });

        choiceContainer.html("");

        // insert checkboxes 
        $.each(_datasets, function(key, val) {
            choiceContainer.append("<br/><input type='checkbox' name='" + key +
                "' checked='checked' id='id" + key + "'></input>" +
                "<label for='id" + key + "'>"
                + val.label + "</label>");
        });

        choiceContainer.find("input").click(function(e) {
            plotAccordingToChoices(-1, -1);
        });

        plotAccordingToChoices(-1, -1);
    }

    $("input#select_all").click(function(e) {
        if ($(this).attr("checked") == "checked") {
            $("p#choices input:not(:checked)").trigger("click");
        } else {
            $("p#choices input:checked").trigger("click");
        }
    });

    $("a#flot-filter").click(function(e) {
        $('span#filter').toggle();
        e.preventDefault();
    });

    $("a#apply").click(function(e) {
        var min_value = $("input#min_value").val();
        _min = min_value == "" ? -1 : parseFloat(min_value);
        var max_value = $("input#max_value").val();
        _max = max_value == "" ? -1 : parseFloat(max_value);

        plotAccordingToChoices();
        e.preventDefault();
    });

    function plotAccordingToChoices() {
        var data = [];
        choiceContainer.find("input:checked").each(function () {
            var key = $(this).attr("name");
            if (key && _datasets[key]) {
                if (_min == -1 && _max == -1) {
                    data.push(_datasets[key]);

                } else {
                    var temp_datasets = {};
                    temp_datasets["label"] = _datasets[key]["label"];
                    temp_datasets["data"] = [];

                    for (var i = 0; i < _datasets[key]["data"].length; i++) {
                        var ts = _datasets[key]["data"][i][0];
                        var val = _datasets[key]["data"][i][1];;

                        if (_min != -1) {
                            if (val < _min) continue;
                        } 
                        if (_max != -1) {
                            if (val > _max) continue;
                        }

                        temp_datasets["data"].push([ts, val]);
                    }
                    data.push(temp_datasets);
                }
            }
        });

        if (data.length > 0) {
            $.plot("#placeholder", data, {
                yaxis: {
                    min: 0,
                },
                xaxis: {    
                    mode: "time",
                    timeformat: "%H:%M",
                    timezone: "browser",
                },
            });
        }
    }
})
