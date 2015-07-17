        // get rectangle area
        function get_box(color) {
            var len = "5px";

            var $rec = $("<div></div>");
            $rec.css({"width":len, "height":len, "border":"2px solid #000", "background-color":color, "display":"inline-block"});
            return $rec;
        }

        //show graphs
        function show_graph(dataobj,from_ts,end_ts) {
           //draw graphics
           draw_grahpics(dataobj,from_ts,end_ts,add_click_handler_for_container);
        }

        //add click function to container
        function add_click_handler_for_container(container,data){
                // click on the container
                $(container).click(function(e) {
                    var $dialog = $("#dialog");
                    $dialog.children().html("");

                    $(this).children("p").each(function() {
                        if ($(this).attr("class") == "title") {
                            return;
                        }

                        $(this).clone().appendTo($dialog.children().last());
                    });

                    $dialog.dialog("option", "title", $(this).children().first().html());
                    $dialog.dialog("open");

                    $.plot(
                        $("#content"),
                        data.values,
                        {
                            series: {
                                lines: {show : true, lineWidth : 4},
                            },
                            xaxis: {
                                mode: "time",
                                timeformat: data.timeformat,
                                timezone: "browser",
                            }
                        }
                    );
                });
        };
        function isSpecialKey(key,type){
            var sepecialkeys = [".qps"];
            for(var i = 0,length = sepecialkeys.length; i < length; ++i){
                var skey = sepecialkeys[i];
                if ( -1 != key.indexOf(skey, key.length - skey.length)){
                    return skey;
                }
            }
            return false;
        }
        function isCompressKey(key ,type){
            var compresskeys = [ ".sswap_in",".sswap_out",".sblock_in",".sblock_out",".scpu_us",".scpu_sy",".scpu_id",".scpu_wa",".smem_total",".smem_used",".smem_buffer",".smem_cache" ];
            if("system" == type){
                for(var i = 0,length = compresskeys.length; i < length; ++i){
                    var skey = compresskeys[i];
                    if ( -1 != key.indexOf(skey, key.length - skey.length)){
                        return key.substring(0, key.lastIndexOf( "_" ))  + "_~All";
                    }
                }
            }
            return false;
        }
        //restruct data
        function reStructData(dataObj,keepolddata){
            //add datas
            for (var type_cube in dataObj) {
                var list = type_cube.split(/^A/);
                var type_name = list[0];
                var cube_name = list[1];

                var delgraphs = [];
                var adddata = {};
                var graphObj = dataObj[type_cube];
                for (var graph_name in graphObj) {
                    var graph_values = graphObj[graph_name];
                    var graph_type = graph_values["type"];
                    var graph_result = graph_values["_results"];
                    var delitems = [];
                    for (var itemname in graph_values) {
                        if("type" == itemname || "_results" == itemname){
                            continue;
                        } else {
                            var ret = isSpecialKey( itemname, graph_type );
                            if(false !== ret){
                                var newgraphname = graph_name + ret;
                                adddata[newgraphname] = {"type":graph_type, "_results": {} }
                                adddata[newgraphname]["_results"][itemname] = graph_values["_results"][itemname];
                                adddata[newgraphname][itemname] = graph_values[itemname];
                                delitems.push( itemname );
                            }
                        }
                    }
                    for(var i = 0, length = delitems.length; i < length; ++i){
                        var delitem = delitems[i];
                        delete graph_values["_results"][delitem];
                        delete graph_values[delitem]; 
                    }
                    var newcompresskey = isCompressKey( graph_name, graph_type );
                    if( false !== newcompresskey ){
                        if( undefined === adddata[newcompresskey] ){
                            adddata[newcompresskey] = {"type":graph_type, "_results": {} };
                        }
                        adddata[newcompresskey]["_results"][graph_name] = graph_values["_results"][graph_name];
                        adddata[newcompresskey][graph_name] = graph_values[graph_name];
                        delgraphs.push( graph_name );
                    }
                }
                for(var graph_name in adddata) {
                    dataObj[type_cube][graph_name] = adddata[graph_name];
                }
                if( ! keepolddata ){
                    for(var i = 0, length = delgraphs.length; i < length; ++i){
                        var deldelgraph = delgraphs[i];
                        delete dataObj[type_cube][deldelgraph];
                    }
                }
            }
            return dataObj;
        }
        // draw graphics on 2nd tab
        // a null signifies separate line segments
        function draw_grahpics(dataObj,from_ts,end_ts,callbackfunction_container){
            $("#tabs").tabs("select", 1)
            //$("#tabs-2").html("");
            $("#graphcs").html("");
            var rowcount = $("#g_count_in_row").val().trim();
            var index = 0;

            var ts_span = end_ts - from_ts;
            var timeformat = "%m/%d";
            if (ts_span < 24 * 60 * 60) {
                timeformat = "%H:%M";
            }
            //compress data 
            dataObj = reStructData( dataObj );
            //alert(JSON.stringify(dataObj));
            for (var type_cube in dataObj) {
                var list = type_cube.split(//);
                var type_name = list[0];
                var cube_name = list[1];

                var graphObj = dataObj[type_cube];
                        
                var sortedgraphnames = [];
                for (var graph_name in graphObj){
                    sortedgraphnames.push( graph_name );
                }
                sortedgraphnames.sort();
                for (var i = 0, length = sortedgraphnames.length; i < length; ++i) {
                    var graph_name = sortedgraphnames[i];
                    var val_dict = {};
                    var graph_values = graphObj[graph_name];
                    var graphtype = graph_values["type"];

                    var linearrayvalues = [];
                    var linearraydescs = [];
                    for (var itemname in graph_values) {
                        //在处理数据的时候：默认会将所有的数据都放在一块儿
                        //对于特定的的一些类型需要特殊处理： 1，数值类型的qps
                        if (itemname == "type") {
                            continue;
                        }else if(itemname == "_results" ){
                            //处理统计结果
                            var itemvalues = graph_values[itemname];
                            for(var key in itemvalues){
                                var temp_str = "[" + key + "]: ";
                                var val = itemvalues[key];
                                for (var skey in val) {
                                    temp_str += " " + skey + ": " + val[skey];
                                }
                                linearraydescs.push( temp_str );
                            }
                        } else {
                            //处理具体的值信息
                            var newitemvalues = [];
                            var itemvalues = graph_values[itemname];
                            for(var key in itemvalues){
                                var ts = parseInt(key);
                                var val = itemvalues[key];
                                newitemvalues.push([ts * 1000 + 8 * 60 * 60 * 1000, val]);
                            }
                            //linearrayvalues.push( { label: itemname, data: newitemvalues} );
                            linearrayvalues.push( { data: newitemvalues} );
                        }
                    }

                        var data = {"timeformat":timeformat, "values":linearrayvalues};
                        // draw container
                        draw_single_container(index, rowcount, graph_name + " - " + cube_name, data, linearraydescs );
                        //set call back function to container
                        if(callbackfunction_container){
                            (function(){
                                callbackfunction_container("#tabs-2 #container_" + index,data);
                            })();
                        }
                    index++;
                }
            }
        }

        // draw single container
        // index begins with 0
        // count_in_row: how many graphic(s) show in one row
        function draw_single_container(index, count_in_row, title, data, desc_list) {
            var $graph = $("#tabs-2 #graphcs");

            // add sub div "yui3-g"
            if (index % count_in_row == 0) {
                $graph.append("<div></div>")
                    // yui3-g node
                    .children().last().addClass("yui3-g");
            }

            // build container and add title
            $graph.children().last()
                    // yui3-u-1-* node
                    .append("<div></div>").children().last().addClass("yui3-u-1-" + count_in_row)
                    // container node
                    .append("<div></div>").children().first().addClass("container").attr("id", "container_" + index)
                        // p node
                        .append("<p></p>").children().last().addClass("title").html(title).parent()
                        .append("<div id='placeholder" + index + "'></div>").children().last().addClass("graphics");

            // set container height
            var $placeholder = $("#placeholder" + index);
            var height = $placeholder.parent().css("width").slice(0, -2) * 0.75;
            $placeholder.parent().css("height", height + "px");

            // set height for graphics and line-height for description p
            var height = g_count_style_dict[count_in_row]["height"];
            $placeholder.css("height", height);

            // draw graphics
            var plot = $.plot(
            $("#placeholder" + index),
                data.values,
                {
                    series: {
                        lines: {show : true ,lineWidth : 4 },
                    },
                    xaxis: {
                        mode: "time",
                        timeformat: data.timeformat,
                        timezone: "browser",
                    }
                }
            );

            // match color with description items
            var series = plot.getData();
            var desc_color_tuple = [];
            for (var j = 0; j < series.length; j++) {
                var desc = desc_list[j];
                var color = series[j].color;
                desc_color_tuple.push([desc, color]);
            }

            // draw description items
            draw_desc(desc_color_tuple);
            var line_height = g_count_style_dict[count_in_row]["line-height"];
            $placeholder.parent().children(".desc").css("line-height", line_height);
        }

        function draw_desc(desc_color_tuple) {
            var $graph = $("#tabs-2 #graphcs");

            //sort data first 
            desc_color_tuple.sort( function(a,b){return String(a[0]).localeCompare( String(b[0]) );} );
            // append desc lists
            var $container = $graph.children().last().children().last().children().last();
            for (var i = 0; i < desc_color_tuple.length; i++) {
                var tuple = desc_color_tuple[i];
                var desc_str = tuple[0];
                var color = tuple[1];
                $container.append("<p></p>").children().last().addClass("desc").html(get_box(color)).append("&nbsp;").append(desc_str);
            }
        }


        // ============================= Main Logic Area =================================
        $("#dialog").hide();
        $(function(){
            //init graph show function
            if( (undefined == show_graph_function) || ("function" != typeof show_graph_function) ){
                show_graph_function = show_graph;
            }
            var dialogwidth = $(document.body).width() * 0.6;
            var dialogheight = $(document.body).height() * 0.8;
            if(dialogwidth > 800){
                dialogwidth = 800;
            }
            if(dialogheight > 800){
                dialogheight = 800;
            }
            $dialog = $("#dialog").dialog({autoOpen: false})
                    .dialog("option", "modal", true)
                    .dialog("option", "width", dialogwidth)
                    .dialog("option", "height", dialogheight);
        });
