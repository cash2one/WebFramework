<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="./css/index.php.css" type="text/css" />
    <link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" />
    <link rel="stylesheet" href="./js/bootstrap/css/bootstrap.min.css" type="text/css" /> 
    <link rel="stylesheet" href="./js/bootstrap/css/bootstrap-theme.min.css" type="text/css" />
    <link rel="stylesheet" href="../../m_webTechPages/js/jPages-master/css/jPages.css" type="text/css" />

    <script src="../../../js-base/jquery.min.js" type="text/javascript"></script>
    <script src="../../../js-base/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../../js-base/json.min.js" type="text/javascript"></script>
    <!-- script src="./js/bootstrap/js/bootstrap.min.js" type="text/javascript"></script -->
    <script src="../m_webTechPages/js/jPages-master/js/jPages.min.js" type="text/javascript"></script>
</head>

<body> 
<h3>MongoDB傻瓜工具</h3>

<div style="padding:10px;margin:200px auto;width:400px;border:1px #ccc solid">
    <form method="post" action="./php/home.php">
        <table>
            <tr>
                <td colspan="2" style="background:#eee">
                    <strong>登录:</strong>
                </td>
            </tr>
            <tr>
                <td width="100">机器名:</td>
                <td>
                    <div class="ui-widget">
                        <input type="text" id="host" name="host" value="" style="width:150px"> 
                    </div>
                </td>
            </tr>
            <tr>
                <td width="100">端口:</td>
                <td>
                    <div class="ui-widget">
                        <input type="text" id="port" name="port" value="" style="width:150px"></td>
                    </div>
                </td>
            </tr>
            <tr>
                <td nowrap="">用户名:</td>
                <td>
                    <div class="ui-widget">
                        <input type="text" id="username" name="username" value="" style="width:150px" placeholder="没有可不填写" />
                    </div>
                </td>
            </tr>
            <tr>
                <td>密码:</td>
                <td><input type="password" id="password" name="password" style="width:150px" placeholder="没有可不填写" /></td>
            </tr>
            <tr>
                <td colspan="2" align="center"><input type="submit" value="登录"></td>
            </tr>
        </table>
    </form>
</div>

<script src="./js/index.php.js" type="text/javascript"></script>
</body>
</html>
