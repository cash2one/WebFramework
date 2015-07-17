// ============================= Function Area =================================
// load index info from the first table
function load_idx_info() {
    $.getJSON("./php/readIdxDb.php", function(data) {
        g_idxObj = data;

        $.each(data, function(user) {
            $("#user_list").append("<option>" + user + "</option>");
        });

        // lead to product_list update
        g_currentUser = $("#user_list").val();
        load_product();
    });
}

// load product into product_list
function load_product() {
    $("#product_list").html("");

    for (product in g_idxObj[g_currentUser]) {
        $("#product_list").append("<option>" + product + "</option>");
    }

    // lead to type_cube_list update
    g_currentProduct = $("#product_list").val();
    load_type_cube();
}

// load type-cube list
function load_type_cube() {
    $("#type_cube_list").html("");
    for (type_cube in g_idxObj[g_currentUser][g_currentProduct]) {
        type_cube = type_cube.replace("", ":");
        $("#type_cube_list").append("<li>" + type_cube + "</li>");
    }

    $("#graph_list").html("");
    $("#select_all").attr("checked", false);
    $("#graph_header").html("Graph list");
    g_selectedTypeCubeObj = {};
    load_selected_items();
}

// load graph list
function load_graph() {
    $("#graph_list").html("");
    var graph_name_list = g_idxObj[g_currentUser][g_currentProduct][g_currentSelectedType];

    var selectall = true;
    // for (var index = 0; index < graph_name_list.length; index ++) {
    for (graph_name in graph_name_list) {
        if(graph_name){
            if(undefined != g_selectedTypeCubeObj[g_currentSelectedType] &&
                 null != g_selectedTypeCubeObj[g_currentSelectedType] &&
                 in_array(g_selectedTypeCubeObj[g_currentSelectedType] , graph_name) ){
                $("#graph_list").append("<li>" + graph_name + "</li>").children().last().addClass("ui-selected");
            } else {
                $("#graph_list").append("<li>" + graph_name + "</li>");
                selectall = false;
            }
        }
    }

    $("#select_all").attr("checked", selectall);
}

// get item based on given name
function get_type_item(name) {
    var item = null;
    $("#type_cube_list li").each(function() {
        if ($(this).html().replace(":", "") == name) {
            item = this;
            return;
        }
    });
    return item;
}

// load selected items
function load_selected_items() {
    $("#selected_item_list").html("");
    for (var type in g_selectedTypeCubeObj) {
        var type2 = type.replace("", " : ");
        for (var idx = 0; idx < g_selectedTypeCubeObj[type].length; idx ++) {
            var graph_name = g_selectedTypeCubeObj[type][idx];
            $("#selected_item_list").append("<li>" + type2 + " : " + graph_name + "</li>");
        }
    }
}


// ============================= Event Handler Area =================================
// change on user_list drop-down list
$("#user_list").change(function(e) {
    g_currentUser = $(this).val();
    load_product();
});

// change on product_list drop-down list
$("#product_list").change(function(e) {
    g_currentProduct = $(this).val();
    load_type_cube();
});

// click on type_cube_list
$(document).on("click", "#type_cube_list li", function(e) {
    var currentselectedtype = $(this).html().replace(":","");
    if(currentselectedtype == g_currentSelectedType){
        //return;
    }

    //update glob selecttype and ui
    g_currentSelectedType = currentselectedtype;
    $("#type_cube_list li").each(function() {
        var name = $(this).html();
        if( name.replace(":","") == g_currentSelectedType){
            $(this).addClass("ui-selected");
        } else {
            $(this).removeClass("ui-selected");
        }
    });

    $("#graph_header").html("Graph list:" + g_currentSelectedType);
    load_graph();
});

// click on graph_list
$(document).on("click", "#graph_list li", function(e) {
    var clicked_graph_name = $(this).html();
    var found = true;

    if (g_selectedTypeCubeObj[g_currentSelectedType] == null) {
        g_selectedTypeCubeObj[g_currentSelectedType] = [];
        found = false;

    } else {
        found = in_array(g_selectedTypeCubeObj[g_currentSelectedType], clicked_graph_name);
    }

    if (found == true) {
        $(this).removeClass("ui-selected");

        array_remove(g_selectedTypeCubeObj[g_currentSelectedType], clicked_graph_name);
        /*if (g_selectedTypeCubeObj[g_currentSelectedType].length == 0) {
            array_remove(g_selectedTypeCubeObj, g_currentSelectedType);
            var type_li = get_type_item(g_currentSelectedType);
            $(type_li).removeClass("ui-selected");
        }*/
    } else {
        $(this).addClass("ui-selected");
        /*var type_li = get_type_item(g_currentSelectedType);
        $(type_li).addClass("ui-selected");*/

        g_selectedTypeCubeObj[g_currentSelectedType].push(clicked_graph_name);
    }

    load_selected_items();
});

$("#select_all").click(function() {
    if (this.checked)
        $("#graph_list :not(.ui-selected)").trigger("click");
    else
        $("#graph_list .ui-selected").trigger("click");
});

// deal with click on time list
$("#time_list li").click( function() {
    g_currentSelectedTime = $(this).html();
    if (g_currentSelectedTime == "Manual") {
        var dateObj = new Date();
        var from_ts = format_time(dateObj, -60);
        var end_ts = format_time(dateObj, 0);

        $("#from_ts").val(from_ts);
        $("#end_ts").val(end_ts);
        $("#manual").show();

    } else {
        $("#manual").hide();
    }

    $(this).addClass("ui-selected").siblings().removeClass("ui-selected");
});

// click on query button
$("#query").click( function(e) {
    var hasselecteditem = false;
    for(var cubename in g_selectedTypeCubeObj){
        var items = g_selectedTypeCubeObj[cubename];
        for(var item in items){
            hasselecteditem = true;
            break;
        }
    }
    if( !hasselecteditem){
        alert("No item selected!");
    }else {
        var from_ts = "" ,end_ts = "";
        if (g_currentSelectedTime == "Manual") {
            from_ts = $("#from_ts").val();
            end_ts = $("#end_ts").val();
        } else {
            if (g_currentSelectedTime == "Hour") meta = 60;
            else if (g_currentSelectedTime == "8Hour") meta = 60 * 8;
            else if (g_currentSelectedTime == "Day") meta = 60 * 24;
            else if (g_currentSelectedTime == "Week") meta = 60 * 24 * 7;
            else if (g_currentSelectedTime == "Month") meta = 60 * 24 * 30;
            else if (g_currentSelectedTime == "Manual") meta = 60 * 24 * 30;

            var dateObj = new Date();
            var from_ts = format_time(dateObj, meta * -1);
            var end_ts = format_time(dateObj, 0);
        }

        var obj = {
            "user" : g_currentUser,
            "product" : g_currentProduct,
            "type_obj": g_selectedTypeCubeObj,
            "from_ts": get_timestamp(from_ts),
            "end_ts": get_timestamp(end_ts),
        };

        // query log and parse the result
        $.getJSON("./php/readLog.php", {"param": JSON.stringify(obj)}, function(data){
            if(undefined != show_table_function){
                show_table_function(data);
            }
            if(undefined != show_graph_function){
                 //data = mockdatas();
                 show_graph_function(data,get_timestamp(from_ts),get_timestamp(end_ts));
            }
        });
    }
});

// ============================= Main Logic Area =================================
// run after document.ready
$(function() {
    // init time_list style
    $("#time_list li:first").addClass("ui-selected");
    //hide manual
    $("#manual").hide();
    // load db and fill data
    load_idx_info();
});

