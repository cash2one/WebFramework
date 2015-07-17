//save config file
function save_conf_file(confObj, filename) {
    var confObjStr = $.toJSON(confObj);

    $.post(_save_conf_php_file, {"user": _ldap, "filename": filename, "confObjStr": confObjStr}, function(data) {
	    var returncode = data.substr(0,1);
	    var returncontent = data.substr(2);
            alert(returncontent);
    });
}

$("a.save_all").click(function(e) {
    if (_selectedEditConfFilename == null) {
        alert("Error: No file to save!");
        return false;
    }

    save_conf_file(_confObj, _selectedEditConfFilename);
    e.preventDefault();
});

$("a.check_conf").click(function(e) {
    if(check_varibles()&&check_steps()&&check_collects()){
        alert("配置定义正确");
    }
    e.preventDefault();
});

function check_varibles(){
    var ret = true;
    $.each(_confObj.vars, function(idx, var_obj) {
        if(!is_empty(var_obj[0])){
            alert(var_obj + ":变量名不能为空");
            ret = false;
            return;
        }
        if(!is_empty(var_obj[1])){
            alert(var_obj + ":变量值为空");
            ret = false;
            return;
        }
        if(var_obj[1].charAt(0)=="[" &&
                var_obj[1].charAt(var_obj[1].length-1)=="]"){
            var re = new RegExp(/\[python:|\[shell:/);
            if(var_obj[1].match(re)==null){
                alert(var_obj + ":变量值[]括号内的值必须为: python: 或者 shell：");
                ret = false;
                return;
            }
        }else if(var_obj[1].charAt(0)=="[" && 
                var_obj[1].charAt(var_obj[1].length-1)!="]"){
            alert(var_obj + ":变量值如果以 [ 开始必须以 ] 结束");
            ret = false;
            return;
        }
        
    }); 
    return ret;
}

function check_steps(){
    var ret = true;
    $.each(_confObj.steps, function(idx,step_obj){
        if(step_obj["step.name"]==""){
            alert("第" + (_opStepIndex+1) + "个步骤定义有错，步骤名称不能为空");  
            ret = false;
            return;
        }
        if(step_obj["step.cmd"]==""){
            alert("第" + (_opStepIndex+1) + "个步骤定义有错，步骤命令不能为空");  
            ret = false;
            return;
        }
    });
    return ret;
}

function check_collects(){
    var ret = true;
    $.each(_confObj.collects, function(idx,collect_obj){
        if(collect_obj["collect.name"]==""){
            alert("第" + (_opCollectIndex+1) + "个集合定义有错，集合名称不能为空");  
            ret = false;
            return;
        }
        if(collect_obj["collect.cmd"]==""){
            alert("第" + (_opCollectIndex+1) + "个集合定义有错，集合步骤列表不能为空");  
            ret = false;
            return;
        }        
    });
    return ret;
}

function is_empty(vars){
    if(vars == null ||
        vars == undefined ||
        vars == ""){
        return 0;
    }
    return 1;
}
function set_select_current_user() {
    $("select#user_name_list").val(_ldap);
}

function get_op_str(user) {
    if (user == _ldap) {
        return '<a href="" class="op_del op_link">删除</a> ' + 
           '<a href="" class="op_load op_link">读取</a> ' + 
           '<a href="" class="op_copy op_link">复制</a> ' + 
           '<a href="" class="op_rename op_link">重命名</a> ' +
           '<a href="" class="op_export op_link">导出</a>';
    }

    return '<a href="" class="op_load op_link">读取</a> ' + 
           '<a href="" class="op_copy op_link">复制</a> ' +
           '<a href="" class="op_export op_link">导出</a>';
}

function load_user_info(user_ldap) {
	$.get(_read_conf_list_php_file, {"user": user_ldap}, function(data) {
		$("tbody#user-conf-list").html("");
        var op_str = get_op_str(user_ldap);

        if (data.length == 0) return false;

		var list = data.split(//);
		for (var i = 0; i < list.length; i++) {
            var tr_html_str = "<tr><td>" + user_ldap + "</td><td>" + list[i] + "</td><td>" + op_str +"</td></tr>";
			$("tbody#user-conf-list").append(tr_html_str);
		}
	});
}

function load_pages() {
    // load html files into jquery node
    $("div#page_home").load(_home_html_file);
    $("div#section_var").load(_var_section_html_file);
    $("div#section_step").load(_step_section_html_file);
    $("div#section_collect").load(_collect_section_html_file);
    $("div#page_log").load(_log_html_file);
}

function after_login() {
    // create user dir if not exists
    if (_ldap != null) {
        $.get(_create_user_dir_php_file, {"user": _ldap}, function(data) {});
    }

    // read user list into drop-down list
    $.get(_read_user_list_php_file, function(data) {
        $("#user_name_list").html("");
		var list = data.split(//);
		for (var i = 0; i < list.length; i++) {
            $("#user_name_list").append("<option>" + list[i] + "</option>");
		}
        set_select_current_user();

        _selectedUser = $("select#user_name_list").val();
        load_user_info(_selectedUser);
	});
}
function ssh_input() {
    // input the ssh password
    $("a#ssh_pwd").click(function(e){
        $("#ssh_dialog").css("display","");
	    $("#ssh_dialog").dialog({
            modal:true,
            buttons:{
	        确定: function(){
		    var key = "";
                    key = $("#ssh_password").val();
                    $("#ssh_dialog").dialog("close");
                    if (key == null) { 
                        key = " ";
                    }       
                    //加密机群登录密码
                    $.get(_encrypt_php, {"password":key},function(data){
                        _key = data; 
                    });     
                 },      
                 取消: function(){
                     $("#ssh_dialog").dialog("close");
                 }       
             },       
            open: function() {
                $("#ssh_dialog").keypress(function(e) {
                    if (e.keyCode == $.ui.keyCode.ENTER) {
                        $(this).parent().find("button:eq(0)").trigger("click");
                    }
                });
            } 
         });     
        e.preventDefault();
    });
}
function user_login() {
    // deal with login/re-login senarios
    $("a#login_btn").click(function(e){
        $("#login-panel").css("display","");
        $("#login-panel").dialog({
            modal:true,
            buttons:{
                登陆: function(){
                    var username = $("#user_ldap").val();
                    var password = $("#user_passwd").val();

                    $.get(_login_php_file, {"username": username,"password":password}, function(data) {
                        if(data==1){
                            $("#login-panel").dialog("close");

                            _ldap = username;
                            _passwd = password;

                            // alert("登陆成功,欢迎使用自动化部署系统！！！"); 
                            $("#user_info").html(username);
                            $("#login_btn").html("切换用户");
       			            $("#ssh_link").css("display",""); 
                            after_login();
                        }else{  
                            alert("登陆失败，用户名或者密码错误"); 
                        }       
                    });

                },//close bracket for 登陆function

                取消: function(){
                    $(this).dialog("close");
                }       

            },//close bracket for buttons    
            open: function() {
                $("#login-panel").keypress(function(e) {
                    if (e.keyCode == $.ui.keyCode.ENTER) {
                        $(this).parent().find("button:eq(0)").trigger("click");
                    }
                });
            } 
        });     
        e.preventDefault();
    });     
}

// ================ triggered when document ready ================ 
$(function() {
    // render sections in page into tab pages
    $("#deploy_page").tabs();
    load_pages();
    user_login();
    ssh_input();
});
