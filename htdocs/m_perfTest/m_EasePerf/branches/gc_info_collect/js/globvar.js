var g_idxObj = {};

var g_currentUser = "";
var g_currentProduct = "";
var g_currentSelectedType = "";
var g_currentSelectedTime = "Hour";

var g_machineHeader = "machine_"
var g_userSelectObj = {};
var g_machineInfoName = "_machineinfo_";

var g_load = 0;
var g_queryobj = {};
var g_realtime = -1;


var DATATYPES = {
    "STRING" : "string",
    "SYSTEM" : "system",
    "PROCESS": "process",
    "NUMBER" : "number",
};

var g_count_style_dict = {
    "1" : {"height": "72%", "line-height": "36px"},
    "2" : {"height": "63%", "line-height": "26px"},
    "3" : {"height": "60%", "line-height": "16px"},
    "4" : {"height": "54%", "line-height": "14px"},
    "5" : {"height": "51%", "line-height": "12px"},
    "6" : {"height": "45%", "line-height": "12px"},
    "7" : {"height": "36%", "line-height": "10px"},
};
// load_graph
var load_graph_function = undefined;
//graph show function
var show_graph_function = undefined;
var show_table_function = undefined;

//mock data function
function mockdatas(){
    var data = {};
    for(var i = 0; i < 4; ++i){
        var tc = "typename" + i + "cubename" + i;
        var tcd = {};
        for(var j = 0; j < 3; ++j){
            var monitorname = "monitorname" + j;
            var monitordict = {};
            if( i == j + 1) {
                monitordict["type"] = "process";
            } else if( i == j + 2 ) {
                monitordict["type"] = "string";
            } else {
                monitordict["type"] = "number";
            }
            monitordict["_results"] = {};
            for(var h = 0; h < 3; ++h){
                var mnsub = "mkey" + h;
                if(monitordict["type"] == "number"){
                    var typetails = [".99",".avg",".qps"];
                    mnsub += typetails[h];
                } else if(monitordict["type"] == "process"){
                    var typetails = [".cpu",".mem",".other"];
                    mnsub += typetails[h];
                } else if(monitordict["type"] == "string"){
                    var typetails = [".ok",".timeout",".qps"];
                    mnsub += typetails[h];
                } else {
                    var typetails = [".other1",".other2",".other3"];
                    mnsub += typetails[h];
                }
                monitordict["_results"][mnsub] = {};
                monitordict["_results"][mnsub]["last"] = h + 1 + i * j;
                monitordict["_results"][mnsub]["avg"] = h + 100 + i * j;
                monitordict["_results"][mnsub]["max"] = h + 1000 + i * j;

                monitordict[mnsub] = {};
                for(var ii = 0; ii < 20; ++ii){
                    var ts = 1341038352 + ii;
                    var value = Math.sin(ii + i + h) * (4 + h) + Math.cos(j);
                    if(ii == (10 + 2 * h)  && i == 1 && 2 == j ){
                        value = null;
                    }
                    monitordict[mnsub][ "" + ts] = value;
                }
            }
            tcd[monitorname] = monitordict;
        }
        data[tc] = tcd;
    }
    return data;
}
