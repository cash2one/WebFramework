// global settings
_ldap   = null;
_passwd = null;
_width  = null;
_height = null;

// get ldap login panel html code
function get_ldap_input_panel() {
    var panel_html = 
'<div id="login-panel" style="display:none" class="ui-widget ui-widget-content ui-corner-all" title="用户登陆"> \
    <p id="login_tip"></p> \
    <table id="user_input_table"> \
        <tr> \
            <td>用户名:</td> \
            <td><input type="text" id="user_ldap" class="text ui-widget-content ui-corner-all" /></td> \
        </tr> \
        <tr> \
            <td>密码:</td> \
            <td><input type="password" id="user_passwd" value="" class="text ui-widget-content ui-corner-all" /></td> \
        </tr> \
    </table> \
</div>';

    return panel_html;
}

// triggered when document ready
$(function() {
    var panel_html = get_ldap_input_panel();
    $("body").append($(panel_html));
    _width  = $(window).width();
    _height = $(window).height();
});

// deal with login/re-login senarios
function ldap_login(ldap_login_check_php_file, login_pass_func) {
    $("#login-panel").css("display","");
    $("#login-panel").dialog({
        modal: true,
        position: [_width / 2 - 200, _height / 2 - 150],
        buttons: {
            登陆: function( ){
                var username = $("#user_ldap").val();
                var password = $("#user_passwd").val();

                $.get(ldap_login_check_php_file, {"username": username,"password":password}, function(data) {
                    if (data == 1) {
                        _ldap = username;
                        _passwd = password;

                        $("#login-panel").dialog("close");

                        // call login_pass_func() after login pass
                        if (login_pass_func != null) {
                            login_pass_func();
                        }
                
                    } else {
                        alert("登陆失败，用户名或者密码错误"); 
                    }       
                });
            },

            取消: function() {
                $(this).dialog("close");
            }       

        },//close bracket for buttons    

        open: function() {
            $("#login-panel").keypress(function(e) {
                if (e.keyCode == $.ui.keyCode.ENTER) {
                    $(this).parent().find("button:eq(0)").trigger("click");
                    e.preventDefault();
                }
            });     
        }
    });     
}
