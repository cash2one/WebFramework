<html>
<head>
    <meta charset="utf-8">
	<link rel="stylesheet" href="../../css-base/jquery-ui.min.css" type="text/css" media="all" />
	<script src="../../js-base/jquery.min.js" type="text/javascript"></script>
	<script src="../../js-base/json.min.js" type="text/javascript"></script>
	<script src="../../js-base/jquery-ui.min.js" type="text/javascript"></script>
	<script src="../../js-base/tablednd.min.js" type="text/javascript"></script>

	<link rel="stylesheet" href="../../css-base/grid.css" type="text/css" />
	<link rel="stylesheet" href="./css/index.php.css" type="text/css" />

    <script src="./js/conf_collects.html.js" type="text/javascript"></script>
	<script src="./js/base.js" type="text/javascript"></script>
    <script src="./js/conf_steps.html.js" type="text/javascript"></script>
    <script src="./js/home.html.js" type="text/javascript"></script>
    <script src="./js/common_toolbar.js" type="text/javascript"></script>
    <script src="./js/toolbar.js" type="text/javascript"></script>
    <script src="./js/clone.js" type="text/javascript"></script>
    <link rel="stylesheet" href="./css/toolbar.css" type="text/css" />
    <!--script src="./js/conf_vars.html.js" type="text/javascript"></script-->
</head>

<body>

    <!-- user input form -->
    <div id="login-panel" style="display:none" class="ui-widget ui-widget-content ui-corner-all" title="用户登陆">
        <p id="login_tip"></p>
        <table id="user_input_table">
            <tr>
                <td>用户名:</td>
                <td><input type="text" id="user_ldap" class="text ui-widget-content ui-corner-all" /></td>
            </tr>
            <tr>
                <td>密码:</td>
                <td><input type="password" id="user_passwd" value="" class="text ui-widget-content ui-corner-all" /></td>
            </tr>
        </table>
    </div>

    <div id="setting_bar">
    	<!-- user login link -->
    	<span id="login_link">
            <label id="user_info"></label>
            <a href="" id="login_btn">登陆</a>
    	</span>
    	<!-- ssh password input link -->
    	<!-- span id="ssh_link" style="display:none">
            <a href="" id ="ssh_btn">集群密码</a>
    	</span -->
        <!-- toolbar -->
        <span id="toolbar"></span>
    </div>

    <!-- ssh password input form-->
    <div id = "ssh_dialog" style="display:none">
        <table>
        <tr>
	        <td>部署帐号:</td>
            <td><input type="text" id="ssh_user" class="text ui-widget-content ui-corner-all" /></td>
        </tr>
        <tr>
	        <td>机群密码:</td>
            <td><input type="password" id="ssh_password" class="text ui-widget-content ui-corner-all" /></td>
        </tr>
        </table>
    </div>
    <div>
    <pre id="help" style="display:none" class="var_help">

    命令demo:
        1. /global/share/test/deploy/run.py -f deploy.conf -c start_resin
        2. /global/share/test/deploy/run.py -f deploy.conf -s stop_resin -l log/log.start_resin -m nb173,nb174
    参数说明：
        -f: 配置文件路径，必须
        -c: 执行部署的集合名称，与 -s 二选一
        -s: 执行部署的步骤名称，与-c 二选一
        -l: 日志的输出路径，非必须，默认为当前目录下的log文件
        -m: 执行部署的机器名，非必须，默认情况下，会读取配置文件中相应集合或步骤的机器名，当配置文件中也未设置机器名时，会在本机执行

</pre>
    </div>

    <!-- define pages for deployment -->
	<div id="deploy_page">
        <ul>
			<li><a href="#page_home">首页</a></li>
			<li><a href="#page_conf">配置</a></li>
			<li><a href="#page_log">日志</a></li>
		</ul>
			
		<div id="page_home"></div>
		<div id="page_conf">
            <hr>
            <b id="select_conf_name">配置文件:</b>
            <br>
            <br>
            <b>变量定义</b> 
            <a href="" class="show_var show_conf">[-]</a>
            <a href="" class="save_all">保存全部|</a>
            <a href="" class="check_conf">检查配置|</a> 
            <hr>
			<div id="section_var"></div>
            <br>
            <b>步骤定义</b> 
            <a href="" class="show_step show_conf">[-]</a> 
            <a href="" class="save_all">保存全部|</a>
            <a href="" class="check_conf">检查配置|</a>
            <hr>
			<div id="section_step"></div>
            <br>
            <b>集合定义</b> 
            <a href="" class="show_collect show_conf">[-]</a> 
            <a href="" class="save_all">保存全部|</a>
            <a href="" class="check_conf">检查配置|</a>
            <hr>
			<div id="section_collect"></div>
		</div>
		<div id="page_log"></div>
    </div> 

    <script src="./js/index.php.js" type="text/javascript"></script>

</body>
</html>
