$(function() {
    var _datasets = null;
    var choiceContainer = $("#choices");

    $("input[type=radio]").click(function(e) {
        $("input#select_all").attr("checked", "checked");
        show_graphs();
    });

    $("a.plat").click(function(e) {
        $("input#select_all").attr("checked", "checked");
        $(this).siblings().removeClass("highlight");
        $(this).addClass("highlight");
        show_graphs();
        e.preventDefault();
    });
    $("a.plat#DSP").trigger("click");

    $("input#select_all").click(function(e) {
       if ($(this).attr("checked") == "checked") {
           $("p#choices input:not(:checked)").trigger("click");
       } else {        
           $("p#choices input:checked").trigger("click");
       }   
    });

    function show_graphs() {
        var plat_name = $("a.highlight").attr('id');
        if (! plat_name) return false;
        var type = $("input[type=radio]:checked").attr('id');
        var php_file = "./php/load_dataset.php";
        $.getJSON(php_file, {"plat_name": plat_name, "type": type}, function(data) {
            _datasets = data;
            draw_graphs();
        });
    }

    function draw_graphs() {
        // hard-code color indices to prevent them from shifting as
        // countries are turned on/off
        var i = 0;
        $.each(_datasets, function(key, val) {
            val.color = i;
            ++i;    
        });     

        // alert($.toJSON(_datasets));

        choiceContainer.html("");
        // insert checkboxes 
        $.each(_datasets, function(key, val) {
            choiceContainer.append("<br/><input type='checkbox' name='" + key + 
                "' checked='checked' id='id" + key + "'></input>" +
                "<label for='id" + key + "'>"
                + val.label + "</label>");
        });     
        choiceContainer.find("input").click(function(e) {
            plotAccordingToChoices();
        });     
        plotAccordingToChoices();
    }

    function plotAccordingToChoices() {
        var data = [];
        choiceContainer.find("input:checked").each(function () {
            var key = $(this).attr("name");
            if (key && _datasets[key]) {
                data.push(_datasets[key]);
            }       
        });       

        if (data.length > 0) {
            var type = $("input[type=radio]:checked").attr('id');
            if (type == "hourly") {
                format = "%m-%d %H:00";
            } else {
                format = "%m-%d";
            }

            $.plot("#placeholder", data, { 
                yaxis: {
                    min: 0, 
                },      
                xaxis: {    
                    mode: "time", 
                    timeformat: format,
                    timezone: "browser",
                },      
            });     
        }       
    }

});
