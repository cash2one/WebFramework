function clear_collect_info() {
    $("input.collect_info").val("");
    $("table tbody#collect_detailstep_tbody ").html("");
}

function dragCollect(index) {
   _confObj.collects.splice(index, 1); 
}

// makes rows in collects table draggable
function register_collect_table_draggable() {
    $("table#collect_table").tableDnD({
        onDragClass: "CollectDragClass",

        onDrop: function(table, row) {
            _opDragCollectEndIndex = $(row).index();
            //alert($(row).index());
            if (_opDragCollectEndIndex != _opDragCollectStartIndex) {//需要测试 是否从零开始
                // swap two collects postion
                var collectObj = _confObj.collects[_opDragCollectStartIndex];
                dragCollect(_opDragCollectStartIndex);
                _confObj.collects.splice(_opDragCollectEndIndex,0,collectObj);

                _opCollectType = "drag";
                clear_collect_info();
            }
        },

        onDragStart: function(table, row) {
            _opDragCollectStartIndex = $(row).index();
            //alert($(row).index());
        }
    });
}

// makes rows in collect steps  table draggable
function register_collect_step_table_draggable() {
    $("table#collect_detailstep_table").tableDnD({
        onDragClass: "CollectStepDragClass",

        onDrop: function(table, row) {
            _opDragCollectStepEndIndex = $(row).index();
            if(_opDragCollectStepStartIndex != _opDragCollectStepEndIndex){
                getCollectSteps();
            }
        },

        onDragStart: function(table, row) {
            _opDragCollectStepStartIndex = $(row).index();
        }
    });
}

function copyNewCollect(){

    var collectObj = _confObj.collects[_opCollectIndex];
    if(_opCollectIndex == _confObj.collects.length - 1) {
        // add obj to the end of the array
        _confObj.collects.push(collectObj);

    } else {
        // insert obj into array
        _confObj.collects.splice(_opCollectIndex + 1, 0, collectObj);
    }

}

function addNewCollect() {
    var collectObj = new Object();
    collectObj["collect.name"] = "";
    collectObj["collect.desc"] = "";
    collectObj["collect.cmd"] = "";
    collectObj["collect.hostname"] = "";

    if (_opCollectIndex == -1) {
        // add obj to the font of the array
        _confObj.collects.unshift(collectObj);

    } else if(_opCollectIndex == _confObj.collects.length - 1) {
        // add obj to the end of the array
        _confObj.collects.push(collectObj);

    } else {
        // insert obj into array
        _confObj.collects.splice(_opCollectIndex + 1, 0, collectObj);
    }
//    alert(_confObj.collects.length);
}

function delCollect() {
   _confObj.collects.splice(_opCollectIndex, 1); 
}

function editCollect(key, value) {
    _confObj.collects[_opCollectIndex][key] = value;
}

function getCollectSteps(){
    var steps="";
    $("#collect_detailstep_table").find("tr").each(function(i) {
        var t = $(this).children().children().first().val();
        steps += t;
        steps += ",";
    });
    steps = steps.substring(0,steps.length-2);
    //alert(steps);
    return steps;
}

function run(filepath,logpath,deployname,hostname,type,element) {
//加密密码
    $.get(_encrypt_php, {"password":_passwd},function(data){
        if (type ==1)
            element.html("停止部署");
        else
            element.val("停止部署");
        
        stopLogging(deployname);
        startLogging(deployname,logpath);
        processLoadConf(1,_deployNumber);
        _deployNumber += 1;
        $.post(_web_deploy_php_file, {"user": _ldap, "password": data, "key": _key, "filepath": filepath,"logpath": logpath,"deployname":deployname,"hostname":hostname,"type":type}, function(message) {
            setTimeout(function(){
                _deployNumber -= 1;
                processLoadConf(0,_deployNumber);
                stopLogging(deployname);
                alert(deployname + "部署结束");
                if (type ==1)
                    element.html("执行部署"); 
                else
                    element.val("执行部署");
            }, 4000);
	    });//end of deploy php
        
    });//end of password encrpty php
}


//filename:保存时配置文件名称，如果是临时保存配置文件需要加ldap账号
//filepath：配置文件路径
//logpath:日志文件路径
//type:类型“ 1：执行collect 部署， 2：执行step部署
function deploy(filename,filepath,logpath,user,deployname,hostname,type,element){
    var confObjStr = $.toJSON(_confObj);
    // alert(_confObj.collects[0]["collect.hostname"]);
    $.post(_save_conf_php_file, {"user": user, "filename": filename, "confObjStr": confObjStr}, function(data) {
        var errorcode = data.substr(0,1);
        if (errorcode == "0") {
           // alert("Info: Save " + filepath + "successfully!");
            //设置机群密码
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
        		            });
			                run(filepath,logpath,deployname,hostname,type,element);
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
	    }
            else {
	        run(filepath,logpath,deployname,hostname,type,element);
	    }

        } else {
            alert(data);            
            alert("Error: Save " + filepath +"failed!");
            return false;
        }
	});//end if save conf php

}
function execDeploy(filename,logpath,deployname,hostname,type,element){
    var user = null;
    var filepath = null;
    $("#confirm-dialog").dialog({
        resizable:false,
        height:140,
        //FIXME: 如果不设置position，那么对话框的弹出位置会在页面的最下方
        position: [d_left, d_height],
        modal:true,
        buttons:{
            "是":function(){
                filepath = _confDirForDeploy + "/" + _ldap + "/" + filename;
                user = _ldap;
                //alert(filepath);
                $(this).dialog("close");
                deploy(filename,filepath,logpath,user,deployname,hostname,type,element);
            },
            "否":function(){
                filepath = _tempConfDir + "/" + filename + "." + _ldap;
                filename = filename + "." + _ldap;
                user = "temp";
                $(this).dialog("close");
               // alert(filepath);
                deploy(filename,filepath,logpath,user,deployname,hostname,type,element);
            },
            "取消":function(){
                $(this).dialog("close");
            }
        }
    });
}

function stopDeploy(deployname,hostname,type){
    var ret_val = confirm("Stop deploying process: " + deployname + " ?");
    if (ret_val == false){
	    return;
    }
    $.post(_stop_deploy_php_file,{"user":_ldap, "deployname":deployname, "hostname":hostname, "key":_key, "type":type}, function(message){
    }); 
    
}
// add a  collect row
$(document).on("click", "a.add_collect", function(e) {
    var new_row = get_collect_row("");

    if ($(this).hasClass("collect_thead")) {
        // click "添加" in head row
        var tbody = $(this).parent().parent().parent().next();
        $(new_row).prependTo($(tbody));
        
        _opCollectIndex = -1;

    } else {
        $(new_row).insertAfter($(this).parent().parent());
        _opCollectIndex = $(this).parent().parent().index();
    }

    _opCollectType = "add";

    addNewCollect();
    register_collect_table_draggable();
    e.preventDefault();
});

// copy a  collect 
$(document).on("click", "a.copy_collect", function(e) {
    clear_collect_info();
    _opCollectIndex = $(this).parent().parent().index();
    var new_row = get_collect_row(_confObj.collects[_opCollectIndex]["collect.name"]);

    $(new_row).insertAfter($(this).parent().parent());

    _opCollectType = "copy";
    copyNewCollect();
    register_collect_table_draggable();
    e.preventDefault();
});


// add a  collect_step row
$(document).on("click", "a.add_collect_step", function(e) {

    if(_opCollectType == "edit"){
        var new_row = get_collect_step_row("");
        if ($(this).hasClass("collect_step_thead")) {
            var tbody = $(this).parent().parent().parent().next();
            $(new_row).prependTo($(tbody));
        
        } else {
            $(new_row).insertAfter($(this).parent().parent());
        }
    }else{
        alert("您还没有选中需要编辑的集合");
    }
    register_collect_step_table_draggable();
    e.preventDefault();
});


// delete a collect  row
$(document).on("click", "a.del_collect", function(e) {
    var ret = confirm("是否删除 ?");
    if (ret == false) return false;
    clear_collect_info();
    _opCollectIndex = $(this).parent().parent().index();
    $(this).parent().parent().remove();

    _opCollectType = "del";
    delCollect();

    e.preventDefault();
});

// delete a collect_step  row
$(document).on("click", "a.del_collect_step", function(e) {
    var ret = confirm("是否删除 ?");
    if (ret == false) return false;

    $(this).parent().parent().remove();
    //clear_collect_info();    
    var key = "collect.cmd";
    var value = getCollectSteps();
    editCollect(key, value);
    e.preventDefault();
});

// highlight for selected collect_line
$(document).on("click", "a.collect_link", function(e) {
    $("table#collect_table tbody").children().removeClass("ui-state-highlight");
    if ($(this).hasClass("edit_collect")){
        $(this).parent().parent().addClass("ui-state-highlight");
    }
    e.preventDefault();
});

// edit a collection
$(document).on("click", "a.edit_collect", function(e) {
    edit_collect($(this),e);
    e.preventDefault();
});

function edit_collect(element,e){
    clear_collect_info();
    _opCollectType = "edit";
    _opCollectIndex = element.parent().parent().index();
    var collectObj = _confObj.collects[_opCollectIndex];
    $("input#collect_name").val(collectObj["collect.name"]);
    $("input#collect_desc").val(collectObj["collect.desc"]); 
    $("input#collect_hostname").val(collectObj["collect.hostname"]);
    var stepslist = collectObj["collect.cmd"].split(",");

    for(var step in stepslist){
        $collect_step_line= get_collect_step_row(stepslist[step]);
        $("table#collect_detailstep_table tbody").append($collect_step_line);
    }
    register_collect_step_table_draggable();
}

// deal with position for dialog
var d_left = $(window).width() / 2 , d_height = $(window).height() / 2;

// deploy a collection
$(document).on("click", "a.deploy_collect",function(e){
    _opCollectType = "deploy";
    _opCollectIndex = $(this).parent().parent().index();
    var collectObj = _confObj.collects[_opCollectIndex];

    var logpath = _logDir + "/" + _ldap + "/" + collectObj["collect.name"]+"."+collectObj["collect.hostname"];
   // alert(logpath);
    var type = 1;// 1: deploy collect  2: deploy step
    var filename = _selectedEditConfFilename;

    if(collectObj["collect.hostname"] == undefined||
        collectObj["collect.hostname"] ==""||
        collectObj["collect.hostname"] == null){
        alert("Error: Hostname Empty");
        return false;
    }
    if ($(this).html() == "执行部署"){
    	execDeploy(filename,logpath,collectObj["collect.name"],collectObj["collect.hostname"],type,$(this));
    }
    if ($(this).html() == "停止部署"){
        stopDeploy(collectObj["collect.name"],collectObj["collect.hostname"],type);	
    }
    e.preventDefault();

});
// handler on change in textbox or select for class collect_info
$(document).on("change", ".collect_info", function(e) {
    // return if NOT in edit mode
    if (_opCollectType != "edit"){
        alert("您还没有选中需要编辑的集合,编辑的内容无效");
        return false;
    }

    var key = null;
    var value = $(this).val();
    if ($(this).hasClass("collect_name")) {
        key = "collect.name";

    }else if($(this).hasClass("collect_desc")) {
        key = "collect.desc";

    }else if($(this).hasClass("collect_hostname")) {
        key = "collect.hostname";

    }else if($(this).hasClass("collect_step_name")){
        key = "collect.cmd";
        value = getCollectSteps();
    }
    editCollect(key, value);
    
    e.preventDefault();
});

// handler when keyup triggered
$(document).on("keyup", "input.collect_name", function(e) {
    // return if NOT in edit mode
    if (_opCollectType != "edit") {
        return false;
    }
    var value = $(this).val();
    $("tbody#collect_tbody").children().eq(_opCollectIndex).children().first().html(value);

    e.preventDefault();
});

/*$(document).on("keyup",".collect_info",function(e){
     if (_opCollectType != "edit") {
        return false;
     }
    var value = $(this).val();

});*/
