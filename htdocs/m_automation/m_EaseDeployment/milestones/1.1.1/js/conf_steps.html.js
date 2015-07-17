function copyNewStep(){
    var stepObj =Clone(_confObj.steps[_opStepIndex]);
    if(_opStepIndex == _confObj.steps.length - 1) {
        // add obj to the end of the array
        _confObj.steps.push(stepObj);

    } else {
        // insert obj into array
        _confObj.steps.splice(_opStepIndex + 1, 0, stepObj);
    }
}
// add new step into _confObj.steps
function addNewStep() {
    var stepObj = new Object();
    stepObj["step.name"] = "";
    stepObj["step.desc"] = "";
    stepObj["step.cmd"] = "[shell]:";
    stepObj["step.cmd.sep"] = ",";
    stepObj["step.ignore_fail"] = "0";

    if (_opStepIndex == -1) {
        // add obj to the font of the array
        _confObj.steps.unshift(stepObj);

    } else if(_opStepIndex == _confObj.steps.length - 1) {
        // add obj to the end of the array
        _confObj.steps.push(stepObj);

    } else {
        // insert obj into array
        _confObj.steps.splice(_opStepIndex + 1, 0, stepObj);
    }
}

function delStep() {
   _confObj.steps.splice(_opStepIndex, 1); 
}

// edit step from _confObj.steps
function editStep(key, value) {
    _confObj.steps[_opStepIndex][key] = value;
}

function is_param_erorr(value,sep){
    var re = new RegExp(/\$(.*?)\$/);
    var result = value.match(re);
    if(result != null){
        for(var i = 1; i < result.length; i++){
            var find = 0; 
            $.each(_confObj.vars, function(idx,varObj){
                if(String(varObj[0])== String(result[i])){
                    find = 1;
                    if(varObj[1].indexOf(sep)>=0){
                        alert("参数中含有分隔符:  " + varObj[0] + ":" + varObj[1]);
                        value = "";
                        return false;
                    }
                }else if((idx == (_confObj.vars.length-1)) && find == 0){
                    alert("命令参数中使用的变量" + result[i] +"在变量列表中无定义");
                    value = "";
                    return false;
                }
            });
            if(value == ""){
                break;
            }
        }
    }
    return value;
}
// when step cmd change  return step_cmd
function getStepcmd(){
    var stepcmd = "[" + $("select#step_cmd").val() + "]:";
    var sep = $(".step_param_sep").val();
    if(sep==null)
        sep =",";
    $(".step_cmd_param").each(function(i) {
        var value = $(this).val();
        if(value.indexOf(sep)>=0){
            alert("参数中含有分隔符：  " + value);
            value = "";
        }else{
            value=is_param_erorr(value,sep)
        }
        stepcmd += value +sep;
       
    });
    if(stepcmd[stepcmd.length-1] == sep) {
      stepcmd = stepcmd.substring(0,stepcmd.length-1);
    }
    return stepcmd;
}

//get function name from step.cmd
function getAPIOfStep(stepcmd){
    var re = new RegExp(/\[(.*?)\]/);
    var result = (stepcmd.match(re));
    if(result == null)
        return null;
    if(result.length<2){
        return -1;
    }
    return result[1];
}

// get args from step_cmd
function getArgs(step_cmd,sep){
    var arg = step_cmd.substring(step_cmd.indexOf(":")+1,step_cmd.length);
    var argslist = null;
    if(arg != null){
       argslist = arg.split(sep);
    }
    return argslist;
}
function getDefaultcmd(){
    $("select#step_cmd").selectedIndex = 1; 
    var newrow = get_step_cmdarg_row("shell命令cmd:","");
    var cmdtable = $("table#step_info_cmd_table tbody");
    cmdtable.append(newrow);      
}

//copy a step
$(document).on("click", "a.copy_step", function(e) {
    _opStepIndex = $(this).parent().parent().index();
    var new_row = get_step_row(_confObj.steps[_opStepIndex]["step.name"]);
    clear_step_info();
    $(new_row).insertAfter($(this).parent().parent());
    copyNewStep();
    register_step_table_draggable();
    getDefaultcmd();
    _opStepType = "copy";
    e.preventDefault();
});

// add a row
$(document).on("click", "a.add_step", function(e) {
    var new_row = get_step_row("");
    clear_step_info();

    if ($(this).hasClass("step_thead")) {
        // click "添加" in head row
        var tbody = $(this).parent().parent().parent().next();
        $(new_row).prependTo($(tbody));
        
        _opStepIndex = -1;
    } else {
        $(new_row).insertAfter($(this).parent().parent());
        _opStepIndex = $(this).parent().parent().index();
    }

    _opStepType = "add";
    addNewStep();
    register_step_table_draggable();
    getDefaultcmd();
    e.preventDefault();
});

// delete a row
$(document).on("click", "a.del_step", function(e) {
    var ret = confirm("Confirm to delete row ?");
    if (ret == false) return false;
    clear_step_info();

    // Must be before remove action or $(this) will be invalid
    _opStepType = "del";
    _opStepIndex = $(this).parent().parent().index();

    $(this).parent().parent().remove();
    delStep();

    e.preventDefault();
});

// highlight for selected step_line
$(document).on("click", "a.step_link", function(e) {
    $("tbody#steps_tbody").children().removeClass("ui-state-highlight");
    if ($(this).hasClass("edit_step") || $("td.step_cmd_desc").length > 0){
        $(this).parent().parent().addClass("ui-state-highlight");
    }
    e.preventDefault();
});

// selected edit a step
$(document).on("click", "a.edit_step", function(e) {
    edit_step($(this),e);
    e.preventDefault();
});
function edit_step(element,e){
    // make step input table move with edit action
    // postion refer: http://blog.chinaunix.net/uid-8532476-id-2029117.html
    var $steps_table       = $("table#steps_table");
    var $step_detail_table = $("table#step_info_table");

    var steps_table_top          = $steps_table.offset().top;
    var step_detail_table_height = $step_detail_table.height();

    // Notice: 在chrome下按照正常的逻辑来处理的话，会有问题, 所以用这这种trick的办法!!!!
    var new_top = e.pageY - parseInt(step_detail_table_height / 2.0);
    if (new_top > $steps_table.height() + steps_table_top - step_detail_table_height - 50) {
        new_top = new_top - step_detail_table_height - 50; 
    }
    if (new_top < steps_table_top) {
        new_top = steps_table_top;
    }
    $step_detail_table.offset({top: new_top});
    clear_step_info();
    _opStepType = "edit";
    _opStepIndex = element.parent().parent().index();

    
    var stepObj = _confObj.steps[_opStepIndex];
    $("input#step_name").val(stepObj["step.name"]);
    $("input#step_desc").val(stepObj["step.desc"]);
    var stepkey = getAPIOfStep(stepObj["step.cmd"]);

    if(stepkey!=null){
        $("select#step_cmd").val(stepkey);
        var current_step_api = _stepAPIList[stepkey];
        if(current_step_api == null) {
            alert("配置文件中包含了非法的命令：" + stepkey);
            return false;
        }
        $("select#step_cmd").attr("title",current_step_api["desc"]);
   
        var sep = null;
        if (stepObj["step.cmd.sep"] == null) {
            sep = ",";
        }
        else {
            sep = stepObj["step.cmd.sep"];
        }
        var cmd_arg_length = 0;
        for(var i in current_step_api["parameters"]){ cmd_arg_length++;}
        var newrow = null;
        var count = 0;
        var cmdtable = $("table#step_info_cmd_table tbody");
        if(getAPIOfStep(stepObj["step.cmd"]) == "shell"){
            newrow = get_step_cmdarg_row("shell命令cmd:",stepObj["step.cmd"].split(":")[1]);
            cmdtable.append(newrow);        
        }else{
            args = getArgs(stepObj["step.cmd"],sep);
            $.each(current_step_api["parameters"],function(key,value){
                count++;
                newrow = get_step_cmdarg_row("参数"+ count + ":" + key ,args[count-1]);
                cmdtable.append(newrow);
            });
            if(args!=null && args.length > cmd_arg_length){
                var ret = confirm("步骤的命令参数个数大于实际的参数个数:"+cmd_arg_length+",多余参数将被删除!!! 参数或者参数使用的变量中可能包含分隔符");
                if(ret = false){
                    return;
                }
            }   
        }
        if(count > 1){
            newrow = get_step_cmd_sep_row("分隔符:", sep);
            cmdtable.append(newrow);
        }
    
        //插入命令完整说明
        newrow = get_step_cmd_desc(current_step_api);
        cmdtable.append(newrow); 
    }
    if (stepObj["step.ignore_fail"] == "1") {
        $("select#ignore_fail").get(0).selectedIndex = 0;
    } else {
        $("select#ignore_fail").get(0).selectedIndex = 1;
    }

}

$(document).on("change","select#step_cmd",function(e){
    if (_opStepType != "edit") {
        return;
    }
    var current_step_api = _stepAPIList[$("select#step_cmd").val()];
    var cmdtable = $("table#step_info_cmd_table tbody");
    cmdtable.html("");
    var newrow = null;
    var count = 0;
    $.each(current_step_api["parameters"],function(key,value){
        count++;
        newrow = get_step_cmdarg_row("参数"+ count + ":" + key ,"");
        cmdtable.append(newrow);
    });
    
    if(count > 1){
        newrow = get_step_cmd_sep_row("分隔符:", ",");
        cmdtable.append(newrow);
    }
    newrow = get_step_cmd_desc(current_step_api);
    cmdtable.append(newrow);//插入命令的完整说明

});

// handler on change in text or select for class step_info
$(document).on("change", ".step_info", function(e) {
    // return if NOT in edit mode
    if (_opStepType != "edit") {
        alert("您还没有选中需要编辑的步骤");
        return;
    }

    var key = null;
    var value = $(this).val();
    if ($(this).hasClass("step_name")) {
        key = "step.name";

    }else if($(this).hasClass("step_desc")) {
        key = "step.desc";

    }else if($(this).hasClass("step_cmd")) {
        key = "step.cmd";
        value = getStepcmd();

    }else if($(this).hasClass("step_param_sep")) {
        key = "step.cmd.sep";
        editStep(key,value);

        key = "step.cmd";
        value = getStepcmd();
        

    }else if($(this).hasClass("ignore_fail")) {
        key = "step.ignore_fail";
        var selectedVal = $(this).val();
        if (selectedVal == "是") 
            value = "1";
        else
            value = "0";
    }

    editStep(key, value);
    
    e.preventDefault();
});

// handler when keyup triggered
$(document).on("keyup", "input.step_name", function(e) {
    // return if NOT in edit mode
    if (_opStepType != "edit") {
        return false;
    }

    var value = $(this).val();
    $("tbody#steps_tbody").children().eq(_opStepIndex).children().first().html(value);

    e.preventDefault();
});

var _opDeployStepName = null;
var _opDeployStepHost = null;
// handler when click on deploy_step_btn
$(document).on("click", "input#deploy_step_btn", function(e) {

    var type = 2;

    if ($(this).val() == "执行部署"){
        var hostName = $("input#step_deploy_host").val();

        if (hostName == "") {
            alert("Error: Host Empty");
            return false;
        }
        if (_opStepType != "edit") {
            alert("Error: No Step to Edit");
            return false;
        }
        var filename = _selectedEditConfFilename;
        var stepObj = _confObj.steps[_opStepIndex];
        var logpath = _logDir + "/" + _ldap + "/" + stepObj["step.name"] + "." + hostName; 

        _opDeployStepName = stepObj["step.name"];
        _opDeployStepHost = hostName;
        execDeploy(filename,logpath,stepObj["step.name"],hostName,type,$(this));
    }

    if ($(this).val() == "停止部署"){
        stopDeploy(_opDeployStepName,_opDeployStepHost,type);
    }

    e.preventDefault();
});
