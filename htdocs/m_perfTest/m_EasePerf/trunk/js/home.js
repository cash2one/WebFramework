// ============================= Function Area =================================
// load graph list
function load_graph_data() {
    $("#graph_list").html("");
    var currentuser = g_currentUser; //$("#user_list").val();
    if(undefined == currentuser || "" == currentuser){
        return;
    }

    var isuserinfo = true;
    var productname = g_currentProduct; //$("#product_list").val()
    var currenttype  = g_currentSelectedType; //$("#type_cube_list").val().replace(":", "^A");
    var graph_name_list = undefined;
    if(0 == productname.indexOf(g_machineHeader)){
        isuserinfo = false;
    }
    if(isuserinfo){
        var userdata = g_idxObj["userinfo"];
        if(userdata && userdata[currentuser] &&  userdata[currentuser][productname]){
            graph_name_list = userdata[currentuser][productname][currenttype];
        }
    } else {
        graph_name_list = g_idxObj["machineinfo"]["keys"];
    }
    var selectall = true;
    if(graph_name_list){
        graph_name_list.sort();
        for (var i = 0,length = graph_name_list.length; i < length; ++i) {
            var graph_name = graph_name_list[i];
            graph_name = graph_name.trim();
            if("" == graph_name){
                continue;
            }
            $("#graph_list").append("<li>" + graph_name + "</li>");
        }
    }
    setGraphSelectionStatus();
    load_selected_items();
}

//set graph select statu
function setGraphSelectionStatus(){
    $("#select_all").attr("checked", true);
    $("#graph_list li").each( function(){
        var graph_name = $(this).html();
        if(isGraphSelected(graph_name)){
            $(this).addClass("ui-selected");
        } else {
            $(this).removeClass("ui-selected");
            $("#select_all").attr("checked", false);
        }
    });
}

// load selected items
function load_selected_items() {
    $("#selected_item_list").html("");
    for (var productname in g_userSelectObj) {
        var productdatas = g_userSelectObj[productname];
        for( var typecube in productdatas){
            var graphs = productdatas[typecube];
            var type2 = typecube.replace("", " : ");
            graphs.sort();
            for (var idx = 0,length = graphs.length; idx < length; idx++) {
                var graph_name = graphs[idx];
                $("#selected_item_list").append("<li>" + productname + " : " + typecube + " : " + graph_name + "</li>");
            }
        }
    }
    return;
}


function isGraphSelected(graph_name){
    var productname = g_currentProduct;
    var currenttype = g_currentSelectedType;
    var selecttypes = undefined;
    if(undefined != g_userSelectObj[productname]){
        selecttypes = g_userSelectObj[productname][currenttype];
    }

    if(undefined != selecttypes && null != selecttypes &&
         in_array(selecttypes , graph_name) ){
        return true;
    } else {
        return false;
    }
    return false;
}
function selectGraph(graphname,shouldcheck){
    var productname = g_currentProduct;
    var currenttype = g_currentSelectedType;
    if(undefined == g_userSelectObj[productname]){
        g_userSelectObj[productname] = {};
    }
    if(undefined == g_userSelectObj[productname][currenttype]){
        g_userSelectObj[productname][currenttype] = [];
    }
    if( shouldcheck ){
        if( !isGraphSelected(graphname) ){
            g_userSelectObj[productname][currenttype].push(graphname);
        }
    } else {
        g_userSelectObj[productname][currenttype].push(graphname);
    }
    
}
function unSelectGraph(graphname){
    var productname = g_currentProduct;
    var currenttype = g_currentSelectedType;
    var selecttypes = g_userSelectObj[productname][currenttype];
    if(undefined != selecttypes){
        array_remove( g_userSelectObj[productname][currenttype] , graphname);
    }
}
function isSelectedAll(){
    var userdata = g_idxObj["userinfo"];
    var currentuser = g_currentUser; //$("#user_list").val();
    var productname = g_currentProduct; //$("#product_list").val();
    var currenttype  = g_currentSelectedType; //$("#type_cube_list").val().replace(":", "^A");
    if(undefined == userdata[currentuser] ||  undefined == userdata[currentuser][productname] || undefined == userdata[currentuser][productname][currenttype] ){
        return false;
    }
    var graph_name_list = userdata[currentuser][productname][currenttype];
    var selectall = true;
    for (var graph_name in graph_name_list) {
        if(!isGraphSelected(graph_name)){
            selectall = false;
            break;
        }
    }
    return selectall;
}


// ============================= Event Handler Area =================================
// click on graph_list
$(document).on("click", "#graph_list li", function(e) {
    var graph_name = $(this).html();

    var isselected = $(this).hasClass("ui-selected");
    if(isselected){
        $(this).removeClass("ui-selected");
        unSelectGraph( graph_name );
    }else {
        $(this).addClass("ui-selected");
        selectGraph(graph_name);
    }

    setGraphSelectionStatus();
    load_selected_items();
});

$(document).on("click", "#selected_item_list li", function(e) {
    var product_typecube_graph = $(this).html();
    var valuelist = product_typecube_graph.split(":");
    var product = valuelist[0].trim();
    var typecube = valuelist[1].trim();
    var graph = valuelist[2].trim();
    array_remove( g_userSelectObj[product][typecube] , graph);
    $(this).remove();
    load_graph_data();
});


$("#select_all").click(function() {
    if (this.checked)
        $("#graph_list :not(.ui-selected)").trigger("click");
    else
        $("#graph_list .ui-selected").trigger("click");
});

// ============================= Main Logic Area =================================
//set globel functiond
if( (undefined == load_graph_function) || ("function" != typeof load_graph_function) ){
    load_graph_function = load_graph_data;
}
$(function(){
    //load graph
    load_graph_data();
});

