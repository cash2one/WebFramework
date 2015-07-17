

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
   for(var i = 0,length = data.length; i < length; ++i){
      var item = data[i];
      if( DATATYPES.STRING == item.type){
          drawStatusTable(item.values);
      } else if ( DATATYPES.NUMBER == item.type){
          drawNumberTable(item.values);
      }else if ( DATATYPES.PROCESS == item.type){
          drawProcessTable(item.values);
      }
   }
   return data.length > 0;
}
function drawMachineTable(data){
   for(var i = 0,length = data.length; i < length; ++i){
      var item = data[i];
      if ( DATATYPES.SYSTEM == item.type){
          drawSystemTable(item.values);
      }
   }
   return data.length > 0;
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
    var values = [value.type, value.res,value.res99,value.resmin,value.resmax,value.throughout];

    if($("#number th").length < 1){
        $("#number thead").first().append("<tr></tr>").children().last().html(buildTag(["监控项","平均响应时间(ms)","99响应时间(ms)","最小响应时间(ms)","最大响应时间(ms)","吞吐(qps)"],"th"));
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
    var values = [value.type, value.mem,value.cpu];

    if($("#process th").length < 1){
        $("#process thead").first().append("<tr></tr>").children().last().html(buildTag(["进程名","内存(M)","CPU"],"th"));
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
    var outdatas = {};
    for(var productname in dataObj){
        var productdata = dataObj[productname];
        var outproductdata = new Array();
        //process service info
        if(g_machineInfoName != productname) {
            for (var type_cube in productdata) {
                var list = type_cube.split(//);
                var type_name = list[0];
                var cube_name = list[1];
                var processdatas = {};
         
                var graphObj = productdata[type_cube];
                for (var graph_name in graphObj) {
                    var val_dict = {};
                    var graph_values = graphObj[graph_name];
                    var graphtype = graph_values["type"];
                    var results = graph_values["_results"];
                    if("number" == graphtype){
                        var data = {"type" : DATATYPES.NUMBER,"values":{"type":"type","res":"","res99":"","resmin":"","resmax":"","throughout":""}};
                        data.values.type = cube_name + " : " + graph_name;
                        for( var resultkey in results){
                            if ( -1 != resultkey.indexOf(".99", resultkey.length - ".99".length)){
                                data.values.res99 = results[resultkey]["avg"] || 0;
                            } else if ( -1 != resultkey.indexOf(".avg", resultkey.length - ".avg".length)){
                                data.values.res = results[resultkey]["avg"] || 0;
                            } else if ( -1 != resultkey.indexOf(".min", resultkey.length - ".min".length)){
                                data.values.resmin = results[resultkey]["avg"] || 0;
                            } else if ( -1 != resultkey.indexOf(".max", resultkey.length - ".max".length)){
                                data.values.resmax = results[resultkey]["avg"] || 0;
                            } else if ( -1 != resultkey.indexOf(".qps", resultkey.length - ".qps".length)){
                                data.values.throughout = results[resultkey]["avg"] || "0";
                                if(results[resultkey]["avg"]){
                                      data.values.throughout += ( "  (" + results[resultkey]["min"] + " ~ " + results[resultkey]["max"] + ")" );
                                }
                            }
                        }
                        outproductdata.push(data);
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
                        outproductdata.push(data);
                    } else if("process" == graphtype){
                        var processname_out = cube_name + " : " + graph_name.substr(0,graph_name.lastIndexOf(".p"));
                        var processname = processname_out;
                        if(undefined == processdatas[processname]){
                            processdatas[processname] = {"type" :DATATYPES.PROCESS,"values":{"type":processname, "mem": 0,"cpu": 0 , "iowait": 0}};
                        }
                        for( var resultkey in results){
                            if ( -1 != resultkey.indexOf(".pcpu", resultkey.length - ".pcpu".length)){
                                processdatas[processname].values.cpu = Number( processdatas[processname].values.cpu ) + Number( results[resultkey]["avg"] );
                            } else if ( -1 != resultkey.indexOf(".pmem", resultkey.length - ".pmem".length)){
                                processdatas[processname].values.mem = Number( processdatas[processname].values.mem ) + Number( results[resultkey]["avg"] );
                            }
                        }
                        processdatas[processname].values.mem = Number( processdatas[processname].values.mem ).toFixed(2);
/*
                        for( var resultkey in results){
                            var processname2 = resultkey.substr(0,resultkey.lastIndexOf(".p"));
                            if ( -1 == processname_out.indexOf(processname2, resultkey.length - processname2.length)){
                                processname =  processname_out + ":" + processname2;
                            }
                            if(undefined == processdatas[processname]){
                                processdatas[processname] = {"type" :DATATYPES.PROCESS,"values":{"type":processname, "mem":"","cpu":"", "iowait":""}};
                            }
                            if ( -1 != resultkey.indexOf(".pcpu", resultkey.length - ".pcpu".length)){
                                processdatas[processname].values.cpu = results[resultkey]["avg"];
                            } else if ( -1 != resultkey.indexOf(".pmem", resultkey.length - ".pmem".length)){
                                processdatas[processname].values.mem = results[resultkey]["avg"];
                            }
                        }
*/
                    } 
                }
                //add process info
                for(var processkey in processdatas){
                    outproductdata.push( processdatas[processkey] );
                }
            }
            outdatas[productname] = outproductdata;
        } else {
            var outmachinedata = new Array();
            for (var machinename in productdata) {
                var machinedata  = productdata[machinename];
                var data =  {"type" :DATATYPES.SYSTEM,"values":{"type":machinename, "mem":{"total":"","used":"","cache":"","buffer":""},"cpu": {"used":"","sy":"","us":"","wa":""},"load":"","iowait":"","sys_in":"","sys_cs":""}};
                for(var itemname in machinedata){
                    var itemdata = machinedata[itemname]["_results"];
                    if("cpu" == itemname){
                        var cpuused = 100 - Number( itemdata["cpu_id"]["avg"] );
                        data["values"]["cpu"]["used"] = cpuused.toFixed(2);
                        data["values"]["cpu"]["sy"] = itemdata["cpu_sy"]["avg"];
                        data["values"]["cpu"]["us"] = itemdata["cpu_us"]["avg"];
                        data["values"]["cpu"]["wa"] = itemdata["cpu_wa"]["avg"];
                        data["values"]["cpu"] = data["values"]["cpu"]["used"] + " (us:" + data["values"]["cpu"]["us"] + " sy:" + data["values"]["cpu"]["sy"] + " wa:" + data["values"]["cpu"]["wa"]  + ")";
                    } else if("mem" == itemname){
                        data["values"]["mem"]["total"] = itemdata["mem_total"]["avg"];
                        data["values"]["mem"]["used"] = itemdata["mem_used"]["avg"];
                        data["values"]["mem"]["cache"] = itemdata["mem_cache"]["avg"];
                        data["values"]["mem"]["buffer"] = itemdata["mem_buffer"]["avg"];
                        data["values"]["mem"] = data["values"]["mem"]["used"] + "(total:"+ data["values"]["mem"]["total"] + " buffer:" + data["values"]["mem"]["buffer"] + " cache:" + data["values"]["mem"]["cache"] + ")";
                    } else if("sys_load" == itemname){
                        data["values"]["load"] = itemdata[itemname]["avg"];
                    } else if("sys_in" == itemname){
                        data["values"]["sys_in"] = itemdata[itemname]["avg"];
                    } else if("sys_cs" == itemname){
                        data["values"]["sys_cs"] = itemdata[itemname]["avg"];
                    }
                }
                if("object" == typeof data["values"]["cpu"]){
                    data["values"]["cpu"] = "";
                }
                if("object" == typeof data["values"]["mem"]){
                    data["values"]["mem"] = "";
                }
                outmachinedata.push(data);
            }
            outdatas[productname] = outmachinedata;
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
