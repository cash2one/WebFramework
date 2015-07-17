//==============================fucntions ========================================
// read date from db and init the global data
function load_idx_info() {
    $("#loadingtip").show();
    g_load |= 1;
    $.getJSON("./php/readIdxDb.php", function(data) {
        g_load &= ( ~1 );
        if(!g_load){
            $("#loadingtip").hide();
        }
        g_idxObj = data;

        //fill user to userlist
        $("#user_list").html("");
        var userdata = g_idxObj["userinfo"];
        $.each(userdata, function(user) {
            $("#user_list").append("<option>" + user + "</option>");
        });
        $("#user_list").append("<option>Machine</option>");

        //set current user
        $("#user_list").val(g_currentUser);
        g_currentUser = $("#user_list").val();
        //reset g_userSelectObj
        //g_userSelectObj = {};


        //set data to sub items:product
        load_product();
    });
}

// load product into product_list
function load_product() {
    $("#product_list").html("");

    //load userinfos
    var userdata = g_idxObj["userinfo"];
    var currentuser = g_currentUser; //$("#user_list").val();
    for (var product in userdata[currentuser]) {
        $("#product_list").append("<option>" + product + "</option>");
    }
    //load machine infos
    var machinetypes = g_idxObj["machineinfo"]["machines"];
    for (var machinetype in machinetypes) {
        $("#product_list").append("<option>" + g_machineHeader  + machinetype + "</option>");
    }
 
    //set selected product
    $("#product_list").val(g_currentProduct);
    g_currentProduct = $("#product_list").val();

    //file type cube
    load_type_cube();
}

// load type-cube list
function load_type_cube() {
    $("#type_cube_list").html("");

    var isuserinfo = true;
    var productname = g_currentProduct; //$("#product_list").val();
    if(0 == productname.indexOf(g_machineHeader)){
        isuserinfo = false;
    }

    if(isuserinfo){
        var userdata = g_idxObj["userinfo"];
        var currentuser = g_currentUser; //$("#user_list").val();
        for (var type_cube in userdata[currentuser][productname]) {
            type_cube = type_cube.replace("", ":");
            $("#type_cube_list").append("<option>" + type_cube + "</option>");
        }
    } else {
        var machinetype = productname.substr(g_machineHeader.length);
        var machines = g_idxObj["machineinfo"]["machines"][machinetype];
        if(undefined != machines){
            for(var i = 0, length = machines.length; i < length; ++i){
                $("#type_cube_list").append("<option>" + machines[i] + "</option>");
            }
        }
    }

    //set current typecube
    $("#type_cube_list").val(g_currentSelectedType.replace("",":"));
    g_currentSelectedType = $("#type_cube_list").val().replace(":", "");
    if(undefined != load_graph_function){
        load_graph_function();
    }
}


//query and show datas in html
function queryAndShowData( obj ,notip){
    if( ! obj){
        return;
    }
    if("string" == (typeof obj).toLowerCase()){
        obj = JSON.parse( obj );
    }
    if(!notip){
        //tip data is loading
        $("#loadingtip").show();
        g_load |= 2;
    }
    var from_ts = obj.from_ts, end_ts = obj.end_ts;
    // query log and parse the result
    $.getJSON("./php/readLog.php", {"param":  JSON.stringify(obj) }, function(data){
        if(!notip){
            g_load &= ( ~2 );
            if(!g_load){
                $("#loadingtip").hide();
            }
        }
        if( data ) {
            if(undefined != show_table_function){
                show_table_function(data);
            }
            if(undefined != show_graph_function){
                show_graph_function(data, from_ts, end_ts);
            }
        } else {
            alert("Sorry. No data.");
        }
        if("Real" == $("#time_list").val().trim() ){
            window.setTimeout("queryAndShowData(g_queryobj,true)",g_realtime);
        }
    });
}


// getnerate query obj 
function generateQueryObj( selectobj,username,from_ts,end_ts){
    var queryobj = {};
    queryobj["from_ts"] = from_ts;
    queryobj["end_ts"] = end_ts;
    if(undefined == selectobj){
        selectobj = g_userSelectObj;
    }

    var queryobj = {};
    queryobj["userinfo"] = {};
    queryobj["userinfo"]["user"] = username;
    queryobj["userinfo"]["selection"] = {};
    queryobj["machineinfo"] = {};
    queryobj["from_ts"] = from_ts;
    queryobj["end_ts"] = end_ts;
    var isempty = true;
    for(var productname in selectobj){
        var productdatas = selectobj[productname];
        var isuserinfo = true;
        if(0 == productname.indexOf(g_machineHeader)){
            isuserinfo = false;
        }
        if(isuserinfo){
            queryobj["userinfo"]["selection"][productname] = productdatas;
        } else {
            queryobj["machineinfo"][productname] = productdatas;
        }
        //judge is empty
        if(isempty){
            for(var typecube in productdatas){
                if(productdatas[typecube].length > 0){
                    isempty = false;
                }
            }
        }            
    }
    if(isempty){
        queryobj = undefined;
    }
    return queryobj;
}

// parse query obj
function parseQueryObj(queryobj){
    var selectobj = {};
    var selectproductname = undefined;
    var selecttype = undefined;
    var productselections =  queryobj["userinfo"]["selection"];
    for(var productname in productselections){
        selectobj[productname] = productselections[productname];
        selectproductname = productname;
        for(var cubetype in productselections[productname] ){
            if( !selecttype ){
                selecttype = cubetype;
            }
            break;
        }
    }
    var machineselections = queryobj["machineinfo"];
    for(var machinename in machineselections){
        selectobj[machinename] = machineselections[machinename];
        if( !selectproductname ){
            selectproductname = machinename;
        }
        for(var cubetype in machineselections[machinename] ){
            if( !selecttype ) {
                 selecttype = cubetype;
            }
        }   
    }

    //set time
    var selecttime = "Hour";
    var from_ts = "";
    var end_ts = "";
    var begin_ts = parseInt( queryobj["from_ts"] );
    if(begin_ts > 0){
        var end_ts2 = parseInt( queryobj["end_ts"] );
        selecttime = "Manual";
        from_ts = get_time_str( new Date(begin_ts * 1000) );
        end_ts = get_time_str( new Date(end_ts2 * 1000) );
    } else {
        if (begin_ts == -3600){
            selecttime = "Hour";
        } else if (begin_ts == -3600 * 8){
            selecttime = "8Hour";
        } else if (begin_ts == -3600 * 24){
            selecttime = "Day";
        } else if (begin_ts == -3600 * 24 * 7){
            selecttime = "Week";
        } else {
            selecttime = "Month";
        }
    }

    var outobj = {};
    outobj["username"] = queryobj["userinfo"]["user"];
    outobj["productname"] = selectproductname;
    outobj["selecttype"] = selecttype;
    outobj["selectobj"] = selectobj;
    outobj["selecttime"] = selecttime;
    outobj["from_ts"] = from_ts;
    outobj["end_ts"] = end_ts;

    return outobj;
}

function loadDataFromQuery(obj) {
    var outobj = parseQueryObj( obj["param"] );
    g_currentUser = outobj["username"];
    g_currentProduct = outobj["productname"];
    g_currentSelectedType = outobj["selecttype"];
    g_userSelectObj = outobj["selectobj"];

    g_currentSelectedTime = outobj["selecttime"];
    if("Real" == outobj["selecttime"] || "Manual" == outobj["selecttime"]){
        g_currentSelectedTime = "Manual";
        $("#from_ts").val(outobj["from_ts"]);
        $("#end_ts").val(outobj["end_ts"]);
        $(".manual").show();
    }
    $("#time_list").val( outobj["selecttime"] );

    var newpage = obj["newpage"];
    $("#newpage").attr("checked",newpage);

    $("#count_in_row").val(obj["countinrow"]);

    if(undefined != load_graph_function){
        load_graph_function();
    }
}

function parseURL(url){
    var phpparam = getQueryString( "param" );
    if ( !phpparam ){
        return null;
    }
    phpparam = JSON.parse( phpparam );
    var newpage = getQueryString( "newpage" );
    if(newpage){
        newpage = JSON.parse( newpage );
    }else{
        newpage = false;
    }
    var countinrow = getQueryString( "countinrow" );
    if(countinrow){
        countinrow = parseInt(countinrow);
    } else {
        countinrow = 3;
    }
    var obj = {"param": phpparam, "newpage": newpage, "countinrow":countinrow };
    return obj;
} 
//==============================attach event=======================================
// change on user_list drop-down list
$("#user_list").change(function(e) {
    g_currentUser = $(this).val();
    g_currentProduct = "";
    g_currentSelectedType = "";
    g_userSelectObj = {};

    load_product();
});

// change on product_list drop-down list
$("#product_list").change(function(e) {
    g_currentProduct = $(this).val();
    load_type_cube();
});

// change on type_cube_list drop-down list
$("#type_cube_list").change(function(e) {
    var currentselectedtype = $("#type_cube_list").val().replace(":", "");;

    //update glob selecttype and ui
    g_currentSelectedType = currentselectedtype;

    if(undefined != load_graph_function){
        load_graph_function();
    }
}); 

// deal with click on time list
$("#time_list").change(function(e) {
    var currentSelectedTime = $("#time_list").val().trim();
    if (currentSelectedTime == "Manual") {
        var dateObj = new Date();
        var from_ts = format_time(dateObj, -60);
        var end_ts = format_time(dateObj, 0);

        $("#from_ts").val(from_ts);
        $("#end_ts").val(end_ts);
        $(".manual").show();
        $("#realtime").hide();
    } else {
        $(".manual").hide();
        if("Real" == currentSelectedTime){
            $("#realtime").show();
        } else {
            $("#realtime").hide();
        }
    }
    window.clearTimeout();
});

// click on query button
$("#query").click( function(e) {
    window.clearTimeout();
    var currentSelectedTime = $("#time_list").val().trim();
    var from_ts = 0 ,end_ts = 0;
    if (currentSelectedTime == "Manual") {
        from_ts = get_timestamp( $("#from_ts").val() );
        var end_ts_str = $("#end_ts").val();
        if( end_ts_str ){ 
            end_ts = get_timestamp( end_ts_str );
        }
    } else if("Real" == currentSelectedTime) {
        var realtime = $("#realtime").val();
        switch(realtime){
            case "2S":
                realtime = 2 * 1000;
                break;
            case "10S":
                realtime = 10 * 1000;
                break;
            case "30S":
                realtime = 30 * 1000;
                break;
            case "1M":
                realtime = 60 * 1000;
                break;
            case "10M":
                realtime = 10 * 60 * 1000;
                break;
            default:
                realtime = 10 * 60 * 1000;
        }
        g_realtime = realtime;
        var from_ts = parseInt( (new Date()).getTime() / 1000  - 2 * 60 );
    } else {
        var meta = -3600;
        switch( currentSelectedTime ){
            case "Hour":
                meta = -3600;
                break;
            case "8Hour":
                meta = -3600 * 8;
                break;
            case "Day":
                meta = -3600 * 24;
                break;
            case "Week":
                meta = -3600 * 24 * 7;
                break;
            case "Month":
                meta = -3600 * 24 * 30;
                break;
            default:
                meta = -3600;
        }
        from_ts = meta;
    }

    var queryobj = generateQueryObj(g_userSelectObj,g_currentUser,from_ts,end_ts);
    if( !queryobj ){
        alert("No item selected!");
    }else {
        var newpage = $("#newpage").attr("checked") ? true :false;
        var countinrow = $("#count_in_row").val();
        var hosturl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        if(hosturl.length != hosturl.lastIndexOf("index.php") + "index.php".length){
            hosturl += "index.php";
        }
        var visturl = hosturl + "?param=" + encodeURIComponent( JSON.stringify( queryobj ) ) + "&newpage=" + newpage + "&countinrow=" + countinrow;
        $("#directvisiturl").val( visturl );
        if( newpage ){
            window.open(visturl);
        } else {
            //set global query obj
            g_queryobj = queryobj;
            // query log and parse the result
            queryAndShowData( queryobj );
        }
    }
});
//select visit url input
$("#directvisiturl").click( function (){
    this.select();
});
//modify css
$("#toolbar div").each( function(){
    $(this).addClass("yui3-hor");
});
// ============================= Main Logic Area =================================
// run after document.ready
$(function() {
    //hide manual
    $(".manual").hide();
    //hide real time
    $("#realtime").hide();

    //hide loading tip
    $("#loadingtip").css("top", ( $(window).height() - $("#loadingtip").height() ) / 2+$(window).scrollTop() + "px").css("left", ( $(window).width() - $("#loadingtip").width() ) / 2+$(window).scrollLeft() + "px").css("position","fixed");;
    $("#loadingtip").hide();

    //init user infos
    load_idx_info();

    //load all pages
    $("#tabs-1").load("./html/home.html");
    $("#tabs-2").load("./html/graphs.html");
    $("#tabs-3").load("./html/tables.html");
    // render tabs
    $("#tabs").tabs();


    // load data
    var urlparams = parseURL();
    if(undefined != urlparams && null != urlparams){
        //set globe variables
        loadDataFromQuery( urlparams );
        //query and show datas
        queryAndShowData( urlparams["param"] );
    }
});
