<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="./css/index.php.css" />
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>
</head>

<body>

<!-- header bar -->
<center>
<a href="" title="配置查看" id="show_conf_div">&lt;&lt;&lt;</a>
<h2>广告测试服务请求URL发送</h2>
<a href="" title="结果查看" id="show_result_div">&gt;&gt;&gt;</a>
</center>

<div id="conf_div">
    <table border="1" align="center" id="conf_table">
        <tbody id="conf_tbody">
            <tr>
                <th>测试机器</th>
                <td> 
                    机器名:
                    <select id="machine_name">
                        <option>nb092x.corp.youdao.com</option>
                        <option>nb093x.corp.youdao.com</option>
                        <option>nb292x.corp.youdao.com</option>
                        <option>nb293x.corp.youdao.com</option>
                        <option>nb403x.corp.youdao.com</option>
                        <option>nb404x.corp.youdao.com</option>
                        <option>nc044x.corp.youdao.com</option>
                        <option>nc069x.corp.youdao.com</option>
                        <option>nc070x.corp.youdao.com</option>
                        <option>nc107x.corp.youdao.com</option>
                        <option>nc108x.corp.youdao.com</option>
                        <option>nc109x.corp.youdao.com</option>
                        <option>nc111x.corp.youdao.com</option>
                        <option>tb037x.corp.youdao.com</option>
                        <option>hs014x.corp.youdao.com</option>
                    </select>
                    端口号:
                    <input type=text id="service_port" />
                </td>
            </tr>

            <tr>
                <th>请求URL来源</th>
                <td>
                    <input checked name="url_src" type=radio for="线上AccessLog" id="src_online"/> 线上AccessLog
                    <input name="url_src" type=radio for="机群上某个文件" id="src_host"/> 集群上某个文件
                    <input name="url_src" type=radio for="手动输入" id="src_manual"/> 手动输入
                    <!-- input name="url_src" type=radio id="src_upload"/> 本地文件上传 -->
                </td>
            </tr>

            <tr class="access_type_tr">
                <th rowspan="2">线上Access日志类型</th>
                <td>
                    展示Resin:
                    <input checked name="service_type" type="radio" for="邮箱" id="resin_mail"/>邮箱
                    <input name="service_type" type="radio" for="词典" id="resin_dict"/>词典
                    <input name="service_type" type="radio" for="频道右侧" id="resin_channel"/>频道右侧
                    <!-- 还没有
                    <input name="service_type" type="radio" for="搜索" id="resin_search"/>搜索
                    <input name="service_type" type="radio" for="DSP" id="resin_dsp"/>DSP
                    <input name="service_type" type="radio" for="线下直销" id="resin_offline"/>线下直销
                    -->
                    <input name="service_type" type="radio" for="联盟中小站" id="resin_union"/>联盟中小站
                    <input name="service_type" type="radio" for="OMedia" id="resin_omedia"/>OMedia
                </td>
            </tr>

            <tr class="access_type_tr">
                <td>
                    点击Resin:
                    <input name="service_type" type="radio" for="邮箱" id="click_mail"/>邮箱
                    <input name="service_type" type="radio" for="词典" id="click_dict"/>词典
                    <input name="service_type" type="radio" for="搜索/频道/线下直销/OMedia" id="click_search"/>搜索/频道/线下直销/OMedia
                    <input name="service_type" type="radio" for="联盟中小站" id="click_union"/>联盟中小站
                </td>
            </tr>

            <tr id="src_detail_tr">
                <td colspan="2" id="src_detail_td">
                    <div id="src_host_div" class="url_src">
                        机器名: <input id="url_src_host" type="text" />
                        文件路径: <input id="url_src_path" type="text" />
                    </div>

                    <div id="src_manaul_div" class="url_src">
                        请求Url后缀：<input id="url_src_input" type="text" /> <a href="" id="add_url">添加URL</a>
                        <table id="manual_url_table">
                        </table>
                    </div>

                    <div id="src_upload_div" class="url_src">
                        <!-- usage: http://www.w3school.com.cn/php/php_file_upload.asp -->
                        <input type=file id=upload_btn><br>
                    </div>
                </td>
            </tr>

            <tr>
                <th>请求方式</th>
                <td>
                    <input checked name="req_style" type=radio for="手动请求" id="req_manu"/> 手动请求
                    <!--
                    <input name="req_style" type=radio for="自动顺序请求" id="req_auto" class="auto" /> 自动顺序请求
                    <input name="req_style" type=radio for="自动随机请求" id="req_rand" class="auto" /> 自动随机请求
                    -->
                    <span id="req_auto_interval"> --- 请求间隔：<input type="text" id="req_interval" value="5" /> 秒</span>
                </td>
            </tr>
            <tr id="user_input_tr">
                <th>用户信息</th>
                <td>
                    LDAP: <input type=text id="username" />
                    机群密码: <input type=password id="password" />
                </td>
            </tr>
            <tr>
                <td colspan="2" id="req_td">
                    <input type=button id="doWork" value="加载Url列表" />
                </td>
            </tr>
        </tbody>
    </table>

    <br>
    <br>

    <table border="1" align="center" id="log_table">
        <thead>
            <tr>    
                <th colspan="4">用户日志</th> 
            </tr>   
            <tr>    
                <th>时间</th> 
                <th>用户</th> 
                <th>服务</th> 
                <th>内容</th> 
            </tr>   
        </thead>
        <tbody id="log_tbody">
        </tbody>
    </table>
</div>

<div id="result_div">
    <center>
    <div id="user_nav">
        <a href="" id="first_url">first</a>
        <a href="" id="pre_url">pre</a>
        <a href="" id="cur_url">re-visit</a>
        <a href="" id="next_url">next</a>
        <a href="" id="last_url">last</a>
        <a href="" id="rand_url">random</a>
        { <span id="num1">0</span>/<span id="num2">0</span> }
        <input type=checkbox for="显示Url" id="url_show" checked />显示Url

        <div id="url_content">
            <pre>
            </pre>
        </div>
    </div>

    <div>
        <iframe id="web_show" src=""></iframe>
    </div>
    </center>
</div>

<script src="./js/index.php.js"></script>
</body>
</html>
