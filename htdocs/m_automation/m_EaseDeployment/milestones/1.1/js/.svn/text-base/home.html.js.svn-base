function lock(){
    $("a.save_all").hide();
    $("input#deploy_step_btn").hide();
    $("a.deploy_collect").hide();
    $("a.var_link").hide();
    $("a.step_link").hide();
    $("a.collect_link").hide();
    $("a.edit_step").show();
    $("a.edit_collect").show();
}
function unlock(){
    $("a.save_all").show();
    $("input#deploy_step_btn").show();
    $("a.deploy_collect").show();
    $("a.var_link").show();
    $("a.step_link").show();
    $("a.collect_link").show();
}

// when deploying, hide copy load rename del opration
function processLoadConf(ishide,deploynumber){
    if((ishide == 1) && (deploynumber == 0)){
        $("a.op_load").hide();
        $("a.op_copy").hide();
        $("a.op_rename").hide();
        $("a.op_del").hide();
        $("#toolbar").setbtEnable("new",false);
    }else if ((ishide == 0) && (deploynumber == 0)){
        $("a.op_load").show();
        $("a.op_copy").show();
        $("a.op_rename").show();
        $("a.op_del").show();
        $("#toolbar").setbtEnable("new",true);
    }
}

function create_log_tab(name){
    var li_str = "<li class=\"tab ui-state-active\" title=\"" + name + "\"><a>" + name + "</a>" + 
                 "<a class=\"closelogtab\">X</a></li>";
    var div_str = "<div id=\"" + name + "\" class=\"contents_log\"></div>";
    $("div#log_tabs ul").append(li_str);
    $("div#log_tabs").append(div_str);
}

function startLogging(deployname,logname){
    $("div[id='"+deployname+"']").remove();
    $("li:contains('" + deployname + "')").remove();
    $("div.contents_log").hide();
    $("li.ui-state-active").addClass("ui-state-default");
    $("li.ui-state-active").removeClass("ui-state-active");

    create_log_tab(deployname);
    $("#deploy_page").tabs('select', 2);
    _lognumber[deployname] = 0;
    _mytimer[deployname] = setInterval(function(){
        getLog(deployname,logname)
        },1000);
}
//var myVar=setInterval(function(){myTimer()},1000);
function getLog(deployname,logname)
{
	$.post(_log_php, {"linenum": _lognumber[deployname], "logpath":logname}, function(message) {
		//alert(message);
        var result = $.parseJSON(message);
        _lognumber[deployname] = result["linenum"];
        //alert(_lognumber);
        for(var i=0;i <result["lines"].length; i++){
            $("div[id='" + deployname + "']").append(result["lines"][i].replace("ERROR","<font color='red'>ERROR</font>"));
            $("div[id='" + deployname + "']").append("<br/>"); 
        }
	});
}
function stopLogging(deployname)
{
    $("div[id='" + deployname + "']").prepend("*************部署结束**************<br/>");
    clearInterval(_mytimer[deployname]);
    _lognumber[deployname] = 0;
}
function clear_step_info() {
    $("input.step_info").val("");
    $("select#ignore_fail").get(0).selectedIndex = 1; //interface "否" default, in fact not too much usage
    $("table#step_info_cmd_table tbody").html("");

}

function clear_stepAPIList(){
    $("select#step_cmd").find("option").remove();
}

//add function to cmd list
function addAPIList(){
	$.getJSON(_step_help_php, function(json) {
        _stepAPIList = json;
        var key = null;
        for(key in json){
          $("select#step_cmd").append($('<option></option>').val(key).html(key));
        }
    });

}

// delete step from _confObj.steps
function dragStep() {
   _confObj.steps.splice(_opStepIndex, 1); 
}

// makes rows in steps table draggable
function register_step_table_draggable() {
    $("table#steps_table").tableDnD({
        onDragClass: "myDragClass",

        onDrop: function(table, row) {
            _opDragStepEndIndex = $(row).index();

            if (_opDragStepEndIndex != _opDragStepStartIndex) {
                var _stepObj = _confObj.steps[_opDragStepStartIndex];

                if (_opDragStepStartIndex < _opDragStepEndIndex) {
                    // drag step from small index to big index
                    for ( var i = _opDragStepStartIndex; i < _opDragStepEndIndex; i++) {
                        _confObj.steps[i] = _confObj.steps[i+1];
                    }

                } else {
                    // drag step from big index to small index
                    for ( var i = _opDragStepStartIndex; i > _opDragStepEndIndex; i--) {
                        _confObj.steps[i] = _confObj.steps[i-1];
                    }
                }
                _confObj.steps[_opDragStepEndIndex] = _stepObj;

                _opStepType = "drag";
                clear_step_info();
            }
        },

        onDragStart: function(table, row) {
            _opDragStepStartIndex = $(row).index();
        }
    });
}

/*function clear_collect_info() {
    alert("clear");
    $("input.collect_info").val("");
    $("table tbody#collect_detailstep_tbody ").html("");
}*/

// create a new file
function create_new_file() {
    if (_ldap == null) {
		alert("Error: NOT login!");
        return;
    }

	var new_filename = prompt("Create new config file:");
	if (new_filename == null) {
		return;
	} else{
        new_filename = new_filename.replace(/^\s+|\s+$/g,""); 
		if (new_filename == "") {
			alert("Error: Empty Input!");
			return;
		}
	}
	$.get(_create_user_dir_php_file, {"user": _ldap});
	$.get(_create_new_user_php_file, {"user": _ldap, "filename": new_filename}, function(message) {
        set_select_current_user();
        load_user_info(_ldap);
		alert(message);
	});
}

// delete selected file
function delete_selected_file(filename) {
	var ret_val = confirm("Delete file: " + filename + " ?");
	if (ret_val == false) {
		return;
	}

	$.get(_delete_conf_php_file, {"user": _ldap, "filename": filename}, function(message) {
		load_user_info(_ldap);
		alert(message);
	});
}

// rename selected file
function rename_selected_file(filename) {
	var new_filename = prompt("Input new filename:");
	if (new_filename == null) {
		return;
	} else{
        new_filename = new_filename.replace(/^\s+|\s+$/g,""); 
		if (new_filename == "") {
			alert("Error: Empty Input!");
			return;
		}
	}

	$.get(_rename_conf_php_file, {"user": _ldap, "oldfilename": filename, "newfilename": new_filename}, function(message) {
        load_user_info(_ldap);
		alert(message);
	});
}

// copy selected file
function copy_selected_file(user,filename) {
	var new_filename = prompt("Input filename:",filename);
	if (new_filename == null) {
		return;
	} else{
        new_filename = new_filename.replace(/^\s+|\s+$/g,""); 
		if (new_filename == "") {
			alert("Error: Empty Input!");
			return;
		}
	}
    $.get(_create_user_dir_php_file, {"user": _ldap});
	$.get(_copy_conf_php_file, {"touser": _ldap, "fromuser": user, "oldfilename": filename,"newfilename": new_filename}, function(message) {
        load_user_info(_ldap);
		alert(message);
	});
}

function get_export_message(data,filename,export_path){
    var infolist = data.split('\n');                         
    var message = "";
    for(var id in infolist){
        var info = infolist[id].replace(/^\s+|\s+$/g,"");
        if ((info != "") && (info.indexOf("spawn su") == -1) && (info.indexOf("Password:") == -1) && info.indexOf("]$") == -1){
            message += info + '\n';
        }
    }
    if (message == "" || (message.indexOf("100%") != -1)){
        message = "Export file("+filename+") to ("+export_path+") Successfull";
    }else {
        message = "Export ('"+filename+"') Failed:\n" + message;
    }
    return message;
}

// export selected file
function export_selected_file(user,filename) {
	var export_path = prompt("Input path (such as: nc044:/disk1/test/):","/global/share/" + _ldap + "/");
	if (export_path == null) {
		return;
	} else{
        export_path = export_path.replace(/^\s+|\s+$/g,""); 
		if (export_path == "") {
			alert("Error: Empty Input!");
			return;
		}
	}
    //输入机群密码
    if (_key == null){
        var key = "";
        $("#sshpassword-dialog").css("display","")
        $("#sshpassword-dialog").dialog({
            position: [d_left, d_height],
            modal:true,
            buttons:{
                确定: function(){
                    key = $("#ssh_passwd").val();
                    $("#sshpassword-dialog").dialog("close");
                    if (key == null) { 
                        key = " ";
                    }       
                    //加密机群登录密码
                    $.get(_encrypt_php, {"password":key},function(data){
                       _key = data; 
	                    $.get(_export_conf_php_file, {"ldap": _ldap, "key": _key, "user": user, "filename": filename,"path": export_path}, function(data){
		                    alert(get_export_message(data,filename,export_path));
	                    });
                    });     
                },      
                取消: function(){
                    $("#sshpassword-dialog").dialog("close");
                }       
            },      
            open: function() {
                $("#sshpassword-dialog").keypress(function(e) {
                    if (e.keyCode == $.ui.keyCode.ENTER) {
                        $(this).parent().find("button:eq(0)").trigger("click");
                    }       
                });     
            }       
        });     
    }else {  
	    $.get(_export_conf_php_file, {"ldap": _ldap, "key": _key, "user": user, "filename": filename,"path": export_path}, function(data){
		    alert(get_export_message(data,filename,export_path));
	    });
    }     
}

function get_var_row(key, value) {
    return "<tr><td class='kv_td var_key'>" + key + "</td>" + 
        "<td class='kv_td var_value'>" + value + "</td>" + 
        "<td><a href='' class='var_link add_var'>添加</a>" +
            "<a href='' class='var_link del_var'>删除</a>"+
             "<a href='' class='var_link copy_var'>复制</a></td></tr>";
}

function show_vars() {
    // show variables in page
    $("table#var_table tbody").html("");
    $.each(_confObj.vars, function(idx, var_obj) {
        // add td with class kv_td
        var tr_str = get_var_row(var_obj[0], var_obj[1]);
        $("table#var_table tbody").append(tr_str);
    });
}

function get_step_row(step_name) {
    return "<tr><td class='step_name'>" + step_name + "</td>" + 
        "<td><a href='' class='step_link add_step'>添加</a>" +
            "<a href='' class='step_link del_step'>删除</a>" + 
            "<a href='' class='step_link edit_step'>编辑</a>"+
            "<a href='' class='step_link copy_step'>复制</a></td></tr>";
}

function get_step_cmdarg_row(argname,argvalue){
    return "<tr> <td>" + argname +"</td> <td> <input type = 'text' id='step_cmd_arg_value' class = 'step_cmd_param step_cmd step_info' value='"+ argvalue +"'></td> </tr>";
}
function get_step_cmd_sep_row(argname,argvalue){
    return "<tr> <td>" + argname +"</td> <td> <input type = 'text' id=' step_cmd_arg_value' class ='step_param_sep step_info' value='"+ argvalue +"'></td> </tr>";
}


function get_step_cmd_desc(step){

     var desc = "命令说明:  " + step["desc"] + "\r\n\r\n";
     var count = 0;
     for(var i in step["parameters"]){
         count++;
         desc += "    参数"+ count + "\"" + i + "\":  " + step["parameters"][i] + "\r\n";
     }      
     desc +="\r\n";
     for(var demoname in step["demo"]){
        desc += demoname + ":\r\n";
        for(var key in step["demo"][demoname]){
            desc += "    " + key + ":    " + step["demo"][demoname][key]+"\r\n";
        }
        desc+="\r\n"
     }
     var result = "<tr>  <td colspan='2' class='step_cmd_desc'> <textarea readonly id='step_cmd_desc' class='step_info' rows='10' cols='100'>" + desc +"</textarea></td> </tr>";
     return result;
 }
          
function get_collect_row(collect_name){
    return "<tr><td class='collect_name'>" + collect_name + "</td>"+            
           "<td><a href='' class='collect_link add_collect'>添加</a>"+
           "<a href='' class='collect_link del_collect'>删除</a>" + 
           "<a href='' class='collect_link edit_collect'>编辑</a>" +
           "<a href='' class='collect_link copy_collect'>复制</a>" +
           "<a href='' class='collect_link deploy_collect'>执行部署</a></td></tr>";
}

function get_collect_step_row(step_name){
    return "<tr class='collect_step_tr'><td> <input type='text' id='collect_step_name' class='collect_step_name collect_info' value='" + step_name + 
           "'></td><td><a href='' class='collect_step_link add_collect_step'>添加</a> </td>" +
           "<td><a href='' class='collect_step_link del_collect_step'>删除</a> </td></tr>";
}

function show_steps() {
    // show steps in page
    $("tbody#steps_tbody").html("");
    $.each(_confObj.steps, function(idx, stepObj) {
        var tr_str = get_step_row(stepObj["step.name"]);
        $("tbody#steps_tbody").append(tr_str);
    });

    register_step_table_draggable();
}

function show_collects() {
    // show collects in page
    $("table#collect_table tbody").html("");
    $.each(_confObj.collects,function(idx,collectObj){
    	for (key in collectObj){
   			if(key == "collect.name"){
                $collect_line = get_collect_row(collectObj[key]);
    			$("table#collect_table tbody").append($collect_line);
			}
		}
    });
    register_collect_table_draggable();
}

// load deploy-conf file's content to conf page
function load_selected_file(user, filename,e) {
    clear_step_info();
    clear_collect_info();
    clear_stepAPIList();
	$.getJSON(_read_conf_php_file, {"user": user, "filename": filename}, function(data) {
		_confObj = data;
        show_vars();
        show_steps();
        show_collects();
        addAPIList();
        if (user == _ldap)
            unlock();
        else
            lock();
        $("table#step_info_table").offset({top: $("table#steps_table").offset().top});
	});
}

// when document ready
/*
// click on 新建
$(document).on("click", "a.op_new",function(e){
    create_new_file();
    e.preventDefault();
});
*/
// handle when user-name-list drop-down-list change
$(document).on("change","select#user_name_list",function(e){
    _selectedUser = $(this).val();
    load_user_info(_selectedUser);

});

$(function() {

    // click on 删除
    $("a.op_del").live("click", function(e) {
        var conf_file = $(this).parent().prev().html();
        delete_selected_file(conf_file);
        e.preventDefault();
    });

    // click on 读取
    $("a.op_load").live("click", function(e) {
        var user = $(this).parent().prev().prev().html();
        var conf_file = $(this).parent().prev().html();
        _selectedEditConfFilename = conf_file;
        load_selected_file(user, conf_file,e);
        $("#deploy_page").tabs('select', 1);
        e.preventDefault();
    });

    // click on 重命名
    $("a.op_rename").live("click", function(e) {
	    var conf_file = $(this).parent().prev().html();
	    rename_selected_file(conf_file);
        e.preventDefault();
    });
   
    // click on 复制
    $("a.op_copy").live("click", function(e) {
        var user = $(this).parent().prev().prev().html();
	    var conf_file = $(this).parent().prev().html();
        copy_selected_file(user, conf_file);
        set_select_current_user();
        e.preventDefault();
    });
    
    // click on 导出
    $("a.op_export").live("click", function(e) {
        var user = $(this).parent().prev().prev().html();
	    var conf_file = $(this).parent().prev().html();
        export_selected_file(user, conf_file);
        e.preventDefault();
    });
    
});
