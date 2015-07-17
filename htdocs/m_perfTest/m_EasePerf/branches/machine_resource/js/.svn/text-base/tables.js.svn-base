

/**
 *draw tables
 * data: data to be drawed
 * data formate: [ { type:type,value:value},...]  value formate the same with TABLEHEADS.*.values
 *
 */
function drawTables( data ){
   clearAllTable();
   for(var i = 0,length = data.length; i < length; ++i){
      var item = data[i];
          if( DATATYPES.STRING == item.type){
              drawStatusTable(item.values);
          } else if ( DATATYPES.SYSTEM == item.type){
              drawSystemTable(item.values);
          } else if ( DATATYPES.NUMBER == item.type){
              drawNumberTable(item.values);
          }else if ( DATATYPES.PROCESS == item.type){
              drawProcessTable(item.values);
          }
   }
}

function buildTag(elements,tag){
    var targetstr = "";
    for( var i = 0,length = elements.length; i < length; ++i){
        var elevalue = String(elements[i]).replace(/ /g,"&nbsp;");
        targetstr += ( "<" + tag + ">" + elevalue  + "</" + tag+ ">");
    }
    return targetstr;
}
function clearAllTable(){
    $("#number thead").first().html("");
    $("#number tbody").first().html("");

    $("#system thead").first().html("");
    $("#system tbody").first().html("");

    $("#status thead").first().html("");
    $("#status tbody").first().html("");

    $("#process thead").first().html("");
    $("#process tbody").first().html("");
}
function drawNumberTable(value){
    var values = [value.type, value.res,value.res99,value.throughout];

    if($("#number th").length < 1){
        $("#number thead").first().append("<tr></tr>").children().last().html(buildTag(["监控项","平均响应时间(ms)","99响应时间(ms)","吞吐(qps)"],"th"));
    }
    $("#number tbody").first().append("<tr></tr>").children().last().html(buildTag(values,"td"));
}

function drawSystemTable(value){
    var values = [value.type, value.mem,value.cpu,value.load,value.sys_in,value.sys_cs];

    if($("#system th").length < 1){
        $("#system thead").first().append("<tr></tr>").children().last().html(buildTag(["机器名称","内存(M)","CPU","LOAD","SYS_IN","SYS_CS"],"th"));
    }
    $("#system tbody").first().append("<tr></tr>").children().last().html(buildTag(values,"td"));

}
function drawProcessTable(value){
    var values = [value.type, value.mem,value.cpu,value.iowait];

    if($("#process th").length < 1){
        $("#process thead").first().append("<tr></tr>").children().last().html(buildTag(["进程名","内存(M)","CPU","iowait"],"th"));
    }
    $("#process tbody").first().append("<tr></tr>").children().last().html(buildTag(values,"td"));

}
function drawStatusTable(value){
    if($("#status th").length < 1){
        $("#status thead").first().append("<tr></tr>").children().last().html(buildTag(["监控项","吞吐(qps)","状态比例"],"th"));
    }
    if( "object" == (typeof value.status).toLowerCase() ){
        var length = ojbect_propertycount(value.status);
        if(0 == length){
            $("#status tbody").first().children().last().append("<tr></tr>").html(buildTag([value.type,value.throughout,""],"td"));
        } else {
            var runonce = true;
            for(var key in value.status){
                if (runonce) {
                    $("#status tbody").first().append("<tr></tr>").children().last().append("<td></td>").children().last().html(value.type).attr("rowspan",length).parent().append("<td></td>").children().last().html(value.throughout).attr("rowspan",length).parent().append("<td></td>").children().last().html(key + " : " + value.status[key]);
                    runonce = false;
                } else {
                    $("#status tbody").first().append("<tr></tr>").children().last().append("<td></td>").children().last().html(key + " : " + value.status[key]);
                }
            }
        }
    } else {
        var values = [value.type,value.throughout,value.status];
        $("#status tbody").first().children().last().append("<tr></tr>").html(buildTag(values,"td"));
    }
}


//get object property count
function ojbect_propertycount(obj){
    var count = -1;
    if( "object" == (typeof obj).toLowerCase() ){
        if(undefined === obj.length){
            count = 0;
            for(var key in obj){
                ++count
            }
        } else {
            count = obj.length;
        }
    }
    return count;
}
function object_clone(obj) {
    if (null == obj || "object" != typeof obj) return obj;
    var copy = obj.constructor();
    for (var attr in obj) {
        if (obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
    }
    return copy;
}

function dataTransfer(dataObj){
    var outdatas = new Array();
    for (var type_cube in dataObj) {
        var list = type_cube.split(//);
        var type_name = list[0];
        var cube_name = list[1];
        var processdatas = {};
        var machinedatas = {};
 
        var graphObj = dataObj[type_cube];
        for (var graph_name in graphObj) {
            var val_dict = {};
            var graph_values = graphObj[graph_name];
            var graphtype = graph_values["type"];
            var results = graph_values["_results"];
            if("number" == graphtype){
                var data = {"type" : DATATYPES.NUMBER,"values":{"type":"type","res":"","res99":"","throughout":""}};
                data.values.type = cube_name + " : " + graph_name;
                for( var resultkey in results){
                    if ( -1 != resultkey.indexOf(".99", resultkey.length - ".99".length)){
                        data.values.res99 = results[resultkey]["avg"] || 0;
                    } else if ( -1 != resultkey.indexOf(".avg", resultkey.length - ".avg".length)){
                        data.values.res = results[resultkey]["avg"] || 0;
                    } else if ( -1 != resultkey.indexOf(".qps", resultkey.length - ".qps".length)){
                        data.values.throughout = results[resultkey]["avg"] || 0;
                    }
                }
                outdatas.push(data);
            } else if("string" == graphtype){
                var data = {"type" : DATATYPES.STRING,"values":{"type":"type","throughout":"","status":""}};
                data.values.type = cube_name + " : " + graph_name;
                data.values.status = {};
                for( var resultkey in results){
                    if ( -1 != resultkey.indexOf(".qps", resultkey.length - ".qps".length)){
                        data.values.throughout = results[resultkey]["avg"] || 0;
                    } else {
                        var curstat = resultkey.substr(resultkey.lastIndexOf("^") + 1);
                        data.values.status[curstat] = results[resultkey]["avg"];
                    }
                }
                outdatas.push(data);
            } else if("process" == graphtype){
                var processname = cube_name + " : " + graph_name.substr(0,graph_name.lastIndexOf(".p"));
                if(undefined == processdatas[processname]){
                    processdatas[processname] = {"type" :DATATYPES.PROCESS,"values":{"type":processname, "mem":"","cpu":"", "iowait":""}};
                }
                for( var resultkey in results){
                    if ( -1 != resultkey.indexOf(".pcpu", resultkey.length - ".pcpu".length)){
                        processdatas[processname].values.cpu = results[resultkey]["avg"];
                    } else if ( -1 != resultkey.indexOf(".pmem", resultkey.length - ".pmem".length)){
                        processdatas[processname].values.mem = results[resultkey]["avg"];
                    }
                }
            } else if("system" == graphtype){
                var machinename = cube_name + " : " + graph_name.substr(0,graph_name.lastIndexOf(".s"))
                if(undefined == machinedatas[machinename]){
                     machinedatas[machinename] =  {"type" :DATATYPES.SYSTEM,"values":{"type":machinename, "mem":{"total":"","used":"","cache":"","buffer":""},"cpu": {"used":"","sy":"","us":"","wa":""},"load":"","iowait":"","sys_in":"","sys_cs":""}};
                }
                for( var resultkey in results){
                    if ( -1 != resultkey.indexOf(".ssys_in", resultkey.length - ".ssys_in".length)){
                        machinedatas[machinename]["values"]["sys_in"] = results[resultkey]["avg"];
                    } else if ( -1 != resultkey.indexOf(".ssys_cs", resultkey.length - ".ssys_cs".length)){
                        machinedatas[machinename]["values"]["sys_cs"] = results[resultkey]["avg"];
                    } else if ( -1 != resultkey.indexOf(".ssys_load", resultkey.length - ".ssys_load".length)){
                        machinedatas[machinename]["values"]["load"] = results[resultkey]["avg"];
                    } else if ( -1 != resultkey.indexOf(".smem_total", resultkey.length - ".smem_total".length)){
                        machinedatas[machinename]["values"]["mem"]["total"] = results[resultkey]["avg"];
                    } else if ( -1 != resultkey.indexOf(".smem_used", resultkey.length - ".smem_used".length)){
                        machinedatas[machinename]["values"]["mem"]["used"] = results[resultkey]["avg"];
                    } else if ( -1 != resultkey.indexOf(".smem_buffer", resultkey.length - ".smem_buffer".length)){
                        machinedatas[machinename]["values"]["mem"]["buffer"] = results[resultkey]["avg"];
                    } else if ( -1 != resultkey.indexOf(".smem_cache", resultkey.length - ".smem_cache".length)){
                        machinedatas[machinename]["values"]["mem"]["cache"] = results[resultkey]["avg"];
                    } else if ( -1 != resultkey.indexOf(".scpu_id", resultkey.length - ".scpu_id".length)){
                        machinedatas[machinename]["values"]["cpu"]["used"] = String(100-Number(results[resultkey]["avg"]));
                    } else if ( -1 != resultkey.indexOf(".scpu_sy", resultkey.length - ".scpu_sy".length)){
                        machinedatas[machinename]["values"]["cpu"]["sy"] = results[resultkey]["avg"];
                    } else if ( -1 != resultkey.indexOf(".scpu_us", resultkey.length - ".scpu_us".length)){
                        machinedatas[machinename]["values"]["cpu"]["us"] = results[resultkey]["avg"];
                    } else if ( -1 != resultkey.indexOf(".scpu_wa", resultkey.length - ".scpu_wa".length)){
                        machinedatas[machinename]["values"]["cpu"]["wa"] = results[resultkey]["avg"];
                    }
                }
            }
        }
        //push processdatas and sys data to outdatas
        for(var processkey in processdatas){
            outdatas.push( processdatas[processkey] );
        }
        for(var machinekey in machinedatas){
            var machinevalue = machinedatas[machinekey]["values"];
            machinevalue["cpu"] = machinevalue["cpu"]["used"] + "( us:" + machinevalue["cpu"]["us"] + " sy:" + machinevalue["cpu"]["sy"] + " wa:" + machinevalue["cpu"]["wa"]  + " )";
            machinevalue["mem"] = machinevalue["mem"]["used"] + "( total:"+ machinevalue["mem"]["total"] + " :buffer" + machinevalue["mem"]["buffer"] + " :cache" + machinevalue["mem"]["cache"] + " )";
            machinedatas[machinekey]["values"] = machinevalue;
            outdatas.push( machinedatas[machinekey] );
        }
    }
    return outdatas;
}

function showTables(data){
    drawTables( dataTransfer(data) );
}
//init functions
$(function(){
    show_table_function = showTables;
    /*var datas = new Array();
    for(var i = 0; i < 2; ++i){
        var data = object_clone(TABLEHEADS.NUMBER);
        data.values.throughout = i;
        data.values.res = 50 + i;
        data.values.res99 = 80 + i;
        datas.push(data);
    }
    for(var j = 0; j < 3; ++j){
        var data = object_clone(TABLEHEADS.STATUS);
        data.values.throughout = j;
        data.values.status = {"ok":1111,"fail":222,"timeout":333};
        datas.push(data);
    }*/
    var datas = mockdatas();
    datas = dsf(datas);
    drawTables(datas);
});
