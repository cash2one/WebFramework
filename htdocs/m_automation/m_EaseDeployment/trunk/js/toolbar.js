$(document).ready(function(){
	$("#toolbar").dtoolbar({
		items:[{
			id:'new',
		    ico: 'btnNew',
		    text: '新建',
		    handler: function(e){
                create_new_file();
                e.preventDefault(); 
		    }
		  },{
			id:'check',
		    ico: 'btnChecked',
		    text: '检查配置',
		    handler: function(e){
		        if(check_varibles()&&check_steps()&&check_collects()){
                     alert("配置定义正确");
                }
                e.preventDefault();
            }
		  },{
			id:'save',
		    ico: 'btnSave',
		    text: '保存配置',
		    handler: function(e){
                if (_selectedEditConfFilename == null) {
                    alert("请先读取需要保存的文件!");
                    return false;
                }
                save_conf_file(_confObj, _selectedEditConfFilename);
                e.preventDefault();
		    }
		  },{
			id:'ssh_pwd',
		    ico: 'btnEdit',
		    text: '部署帐号',
            handler: function(e) {
                $("#ssh_user").val(_deploy_user);
                $("#ssh_dialog").css("display","");
                $("#ssh_dialog").dialog({
                    modal:true,
                    buttons:{
                        确定: function(){
                            var key = "";
                            _deploy_user = $("#ssh_user").val();
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
            }
		  },{
			id:'help_deploy_back',
		    ico: 'btnprint2',
		    text: '后台部署帮助',
		    handler: function(e){
		        if ($("#help").css('display') == 'none'){
                    $("#help").show();
                }
                else {
                    $("#help").hide();
                }
                e.preventDefault();
            } 
		  },{
			id:'introduce',
		    ico: 'btnIntroduce',
		    text: '工具简介',
		    handler: function(e){
                window.open("https://dev.corp.youdao.com/svn/outfox/incubator/Test/WebFramework/htdocs/m_automation/m_EaseDeployment/trunk/doc/introduction");
                e.preventDefault();
		    }
	    }]
	});
});

