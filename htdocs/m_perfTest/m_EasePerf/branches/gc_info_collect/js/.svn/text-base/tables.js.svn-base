/**
 *draw tables
 * data: data to be drawed
 * data formate: [ { type:type,value:value},...]  value formate the same with TABLEHEADS.*.values
 *
 */
function drawTables( data ){
    clearAllTable();
    var closetip = false;
    for(var productname in data){
        var productdata = data[productname];
        var ret = false;
        //process service info
        if(g_machineInfoName != productname) {
            ret = drawProductTable(productdata);
        } else {
            ret = drawMachineTable(productdata);
        }
        closetip = closetip || ret;
    }
    if(closetip){
        $("#tip").hide();
    } else {
        $("#tip").show();
    }
}

function drawProductTable( data ){

    drawNumberTable( data["number"] );
    drawStatusTable( data["string"] )
    drawProcessTable( data["process"] );
    return data["number"].length + data["string"].length + data["process"].length > 0;
}

function clearAllTable(){
    $("#number").html("");
    $("#system").html("");
    $("#status").html("");
    $("#process").html("");
}

function getValue(data,subkey,subsubkeys){
    var ret = "";
    if(undefined == data){
        return ret;
    }

    var subvalues = data[subkey];
    if( undefined == subvalues){
        return ret;
    }
    if(undefined != subsubkeys){
        if( "string" == (typeof subsubkeys) .toLocaleLowerCase()){
            var subsubvalue = subvalues[subsubkeys];
            if(undefined == subsubvalue){
                subsubvalue = "";
            } else {
                subsubvalue = JSON.stringify( subsubvalue );
            }
            ret = subsubvalue;
        } else {
            var append = "";
            for(var index = 0,length = subsubkeys.length;index < length; ++index){
                var subsubkey = subsubkeys[index];
                var subsubvalue = subvalues[subsubkey];
                if(undefined == subsubvalue){
                    subsubvalue = "";
                } else {
                    subsubvalue = JSON.stringify( subsubvalue );
                }
                if("" == ret){
                    ret = subsubvalue;
                } else {
                    append += (subsubkey + ": " + subsubvalue + " ");
                }
            }
            ret += append;
        }
    } else{
        ret = JSON.stringify(subvalues);
    }

    return ret;
}

function drawNumberTable(items){
    $("#number").html("");
    if(!items.length){
        return ;
    }
    $("#number").append("<thead></theah>").children().last().append("<tr></tr>").children().last().append("<th>监控项</th>").append("<th>平均响应时间(ms)</th>").append("<th>99响应时间(ms)</th>").append("<th>最小响应时间(ms)</th>").append("<th>最大响应时间(ms)</th>").append("<th>吞吐(qps)(ms)</th>");
    $("#number").append("<tbody></tbody>");
    for(var index = 0,length = items.length; index < length; ++index ){
        var item = items[index];
        var itemname = escapeHtml(item["name"]);
        var itemvalue = item["value"];
        var res = getValue(itemvalue,"avg","avg");
        var res99 = getValue(itemvalue,"99","avg");
        var resmin = getValue(itemvalue,"min","avg");
        var resmax = getValue(itemvalue,"max","avg");
        var qps = getValue(itemvalue,"qps","avg");
        $("#number tbody").first().append("<tr></tr>").children().last().append("<td>" + itemname + "</td>").append("<td>" + res + "</td>").append("<td>" + res99 + "</td>").append("<td>" + resmin + "</td>").append("<td>" + resmax + "</td>").append("<td>" + qps + "</td>");
    }
}

function drawProcessTable(items){
    $("#process").html("");
    if(!items.length){
        return;
    }
    $("#process").append("<thead></theah>").children().last().append("<tr></tr>").children().last().append("<th>进程名</th>").append("<th>内存(M)</th>").append("<th>CPU</th>");
    $("#process").append("<tbody></tbody>");
    for(var index = 0,length = items.length; index < length; ++index ){
        var item = items[index];
        var itemname = escapeHtml(item["name"]);
        var itemvalue = item["value"];
        var mem = getValue(itemvalue,"pmem","avg");
        var cpu = getValue(itemvalue,"pcpu","avg");
        $("#process tbody").first().append("<tr></tr>").children().last().append("<td>" + itemname + "</td>").append("<td>" + mem + "</td>").append("<td>" + cpu + "</td>");
    }
}
function drawStatusTable(items){
    $("#status").html("");
    if(!items.length){
        return;
    }
    $("#status").append("<thead></theah>").children().last().append("<tr></tr>").children().last().append("<th>监控项(string)</th>").append("<th>吞吐(qps)</th>").append("<th>状态比例</th>");
    $("#status").append("<tbody></tbody>");
    for(var index = 0,length = items.length; index < length; ++index ){
        var item = items[index];
        var itemname = escapeHtml(item["name"]);
        var itemvalue = item["value"];
        var qps = getValue(itemvalue,"qps","avg");
        var status = [];
        for(var key in itemvalue){
            if( "qps" != key ){
                var newkey = key.substr(1,key.length);
                status.push( escapeHtml(newkey + " : " + getValue(itemvalue,key,"avg")) );
            }
        }
        $("#status tbody").first().append("<tr></tr>").children().last().append("<td>" + itemname + "</td>").append("<td>" + qps + "</td>").append("<td>" + status.join("<br>") + "</td>");
    }
}

function drawMachineTable(items){
    $("#system").html("");
    if(!items.length){
        return false;
    }
    $("#system").append("<thead></theah>").children().last().append("<tr></tr>").children().last().append("<th>机器名称</th>").append("<th>内存(M)</th>").append("<th>CPU</th>").append("<th>LOAD</th>").append("<th>SYS_IN</th>").append("<th>SYS_CS</th>");
    $("#system").append("<tbody></tbody>");
    for(var index = 0,length = items.length; index < length; ++index ){
        var item = items[index];
        var itemname = item["name"];
        var itemvalue = item["value"]
	var mem_used = getValue(itemvalue["mem"],"mem_used","avg");
        try{
            mem_used = Number( getValue(itemvalue["mem"],"mem_total","avg") ) - Number( getValue(itemvalue["mem"],"mem_buffer","avg") ) - Number( getValue(itemvalue["mem"],"mem_cache","avg") );
            mem_used = mem_used.toFixed(2);
        }catch(e){
        }
        var mem = escapeHtml( mem_used  + "(total:" + getValue(itemvalue["mem"],"mem_total","avg") + " buffer:" + getValue(itemvalue["mem"],"mem_buffer","avg") + " cache:" + getValue(itemvalue["mem"],"mem_cache","avg") + ")" );
        var cpuused = 100 - Number( getValue(itemvalue["cpu"],"cpu_id","avg") );
        var cpu = escapeHtml( cpuused.toFixed(2) + "(us:" + getValue(itemvalue["cpu"],"cpu_us","avg") + " sy:" + getValue(itemvalue["cpu"],"cpu_sy","avg") + " wa:" + getValue(itemvalue["cpu"],"cpu_wa","avg") + ")" );
        var load = getValue(itemvalue["sys_load"],"sys_load","avg");
        var sys_in = getValue(itemvalue["sys_in"],"sys_in","avg");
        var sys_cs = getValue(itemvalue["sys_cs"],"sys_cs","avg");
        $("#system tbody").first().append("<tr></tr>").children().last().append("<td>" + itemname + "</td>").append("<td>" + mem + "</td>").append("<td>" + cpu + "</td>").append("<td>" + load + "</td>").append("<td>" + sys_in + "</td>").append("<td>" + sys_cs + "</td>");
    }
    return items.length > 0;
}

function dataTransfer(dataObj){
    var outdatas = {};
    for(var productname in dataObj){
        var productdata = dataObj[productname];
        //process service info
        if(g_machineInfoName != productname) {
            var numitems = new Array();
            var stritems = new Array();
            var pinfoitems = new Array();
            for (var type_cube in productdata) {
                var list = type_cube.split(//);
                var type_name = list[0];
                var cube_name = list[1];
                var graphObj = productdata[type_cube];
                for (var graph_name in graphObj) {
                    var val_dict = {};
                    var graph_values = graphObj[graph_name];
                    var graphtype = graph_values["type"];
                    var itemname = cube_name + " : " + graph_name;
                    var itemvalue = { "name" : itemname, "value":graph_values["_results"] };
                    if ("number" == graphtype ) {
                        numitems.push( itemvalue );
                    } else if ("string" == graphtype ) {
                        stritems.push( itemvalue );
                    } else if("process" == graphtype) {
                        if(0 == graph_name.indexOf("pinfo_otherusers")){
                            continue;
                        }
                        pinfoitems.push( itemvalue );
                    }
                }
            }
            outdatas[productname] = { "number":numitems , "string":stritems , "process": pinfoitems} ;
        } else {
            var machineitems = new Array();
            for (var machinename in productdata) {
                var machinedata = productdata[machinename];
                var outmachinedata = {};
                for(var itemname in machinedata){
                    if("uses_cpu" == itemname || "users_mem" == itemname){
                        continue;
                    }
                    outmachinedata[itemname] = machinedata[itemname]["_results"];
                }
                var itemvalue = { "name" : machinename, "value": outmachinedata };
                machineitems.push( itemvalue );
            }
            outdatas[productname] = machineitems;
        }
    }
    return outdatas;
}


function showTables(data){
    drawTables( dataTransfer(data) );
}
//init functions
(function(){
    if( (undefined == show_table_function) || ("function" != typeof show_table_function) ){
        show_table_function = showTables;
    }
})();
