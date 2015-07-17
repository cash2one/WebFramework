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
<h2>财务系统（月结）测试工具</h2>

<div id="deploy_conf">
    财务系统部署机器: <input type=text id="finance_host" /> &nbsp;
    路径: <input type=text id="finance_dir" /> 
    <input type=button id="set_config" value="设定" />
</div>

<div id="nav_bar">
    <br>
    <table>
        <thead>
            <tr>
                <th><a href="" id="show_conf">显示配置</a></th>
                <th><a href="" id="show_tables">显示数据库表</a></th>
                <th><a href="" id="delete_tables_data">删除广告商数据</a></th>
                <th><a href="./check.php" id="check_table_data">查看结果</a></th>
                <th><a href="./record.php" id="record_data">录入状态</a></th>
                <th><a target="_blank" title="模拟每月自动结算" href="http://nc111x.corp.youdao.com:49991/finance/manual/manualMonthlySettlement.do?update=1" id="doSettlement">手动结算上个月</a></th>
                <th><a target="_blank" title="模拟每月自动结算" href="http://nc111x.corp.youdao.com:49991/finance/manual/manualMonthlySettlement.do?cur=0" id="doSettlement2">手动结算上个月(不写库)</a></th>
                <th><a target="_blank" title="模拟每月自动结算" href="http://nc111x.corp.youdao.com:49991/finance/manual/manualMonthlySettlement.do?cur=1" id="doSettlement3">手动结算1号到昨天(不写库)</a></th>
                <th><a target="_blank" title="模拟10分钟的ChargeUp定时任务（张培用的）" href="http://nc111x.corp.youdao.com:49991/finance/manual/manualChargeUp.do" id="doChargeUp">ChargeUp</a></th>
                <!--
                <th><a target="_blank" title="模拟10分钟的ChargeUp定时任务(周晓兰用的)" href="http://nc111x.corp.youdao.com:49992/finance/manual/manualChargeUp.do" id="doChargeUp2">ChargeUp2</a></th>
                -->
                <th><a target="_blank" href="./test_document/user.php">测试文档</a></th>
            </tr>
        </thead>
    </table>
</div>

<br>

<div id="user_input">
    <div id="show_table_input">
        <table>
            <tr>
                <td>广告商ID:</td>
                <td>
                    <input type=text id="sponsor_id" /> 
                    <input type=button id="show_table_data" value="查询" />
                    <input type=checkbox id="check_all_query" for="全选" />全选
                    <input type=checkbox id="readable" for="使用可读性" />使用可读性
                </td>
            </tr>
            <tr>
                <td>数据库表:</td>
                <td> 
                    <input type=checkbox for="Sponsor" class="table_checkbox"> Sponsor <br>
                    <input type=checkbox for="Log_DA" class="table_checkbox"> Log_DA <br>
                    <input type=checkbox for="Log_DA_SIM" class="table_checkbox"> Log_DA_SIM <br>
                    <input type=checkbox for="SPONSOR_BALANCE" class="table_checkbox"> SPONSOR_BALANCE <br>
                    <input type=checkbox for="SPONSOR_MONTHLY_CONTRACT" class="table_checkbox"> SPONSOR_MONTHLY_CONTRACT <br>
                    <input type=checkbox for="SPONSOR_MONTHLY_DISCOUNT" class="table_checkbox"> SPONSOR_MONTHLY_DISCOUNT <br>
                    <input type=checkbox for="SPONSOR_MONTHLY_DISCOUNT_DETAIL" class="table_checkbox"> SPONSOR_MONTHLY_DISCOUNT_DETAIL <br>
                    <input type=checkbox for="SPONSOR_MONTHLY_SETTLEMENT" class="table_checkbox"> SPONSOR_MONTHLY_SETTLEMENT <br>
                    <input type=checkbox for="SPONSOR_ACCOUNT_HISTORY" class="table_checkbox"> SPONSOR_ACCOUNT_HISTORY <br>
                    <input type=checkbox for="CLICK" class="table_checkbox"> CLICK <br>
                    <input type=checkbox for="AD_CLICK_CHARGE_UP_PROGRESS" class="table_checkbox"> AD_CLICK_CHARGE_UP_PROGRESS <br>
                    <input type=checkbox for="SPONSOR_TODAY_COST" class="table_checkbox"> SPONSOR_TODAY_COST <br>
                    <input type=checkbox for="SPONSOR_DAILY_COST" class="table_checkbox"> SPONSOR_DAILY_COST <br>
                    <input type=checkbox for="SPONSOR_BALANCE_CHANGE" class="table_checkbox"> SPONSOR_BALANCE_CHANGE <br>
                </td>
        </table>
    </div>

    <div id="delete_data_input">
        <table>
            <tr>
                <td>广告商ID:</td>
                <td>
                    <input type=text id="sponsor_id2" /> 
                    <input type=button id="delete_table_data" value="删除" />
                    <input type=checkbox id="check_all_delete" for="全选" />全选
                </td>
            </tr>
            <tr>
                <td>数据库表:</td>
                <td> 
                    <input type=checkbox for="SPONSOR_BALANCE" class="table_checkbox2"> SPONSOR_BALANCE <br>
                    <input type=checkbox for="SPONSOR_MONTHLY_CONTRACT" class="table_checkbox2"> SPONSOR_MONTHLY_CONTRACT <br>
                    <input type=checkbox for="SPONSOR_MONTHLY_DISCOUNT" class="table_checkbox2"> SPONSOR_MONTHLY_DISCOUNT <br>
                    <input type=checkbox for="SPONSOR_MONTHLY_DISCOUNT_DETAIL" class="table_checkbox2"> SPONSOR_MONTHLY_DISCOUNT_DETAIL <br>
                    <input type=checkbox for="SPONSOR_MONTHLY_SETTLEMENT" class="table_checkbox2"> SPONSOR_MONTHLY_SETTLEMENT <br>
                    <input type=checkbox for="SPONSOR_ACCOUNT_HISTORY" class="table_checkbox2"> SPONSOR_ACCOUNT_HISTORY <br>
                </td>
        </table>
    </div>
</div>

<br>

<div id="content">
</div>

<script src="./js/index.php.js"></script>
</body>
</html>
