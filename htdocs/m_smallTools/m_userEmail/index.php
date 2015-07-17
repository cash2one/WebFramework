<html ng-app="MailList">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../js-base/bootstrap/css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="../../js-base/bootstrap/css/bootstrap-theme.min.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../../css-base/grid.css" />

    <script src="../../js-base/jquery.min.js" type="text/javascript"></script>
    <script src="../../js-base/jquery-ui.min.js" type="text/javascript"></script>
    <script src="../../js-base/json.min.js" type="text/javascript"></script>
    <script src="../../js-base/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>

    <script src="../../js-base/angularJS/angular.min.js" type="text/javascript"></script>
    <script src="../../js-base/angularJS/angular-resource.min.js" type="text/javascript"></script>

    <style>
        body {margin-left: 10px}
        h3 {display:inline-block; margin-bottom: 20px;}
        a  {text-decoration: none}
        a:hover {text-decoration: underline}
        div#query_part {
            margin-bottom: 10px;
        }

        input#userList {width: 700px}

        label {
            font-size: 1.4em;
            margin-right: 5px
        }

        table td {
            padding-bottom: 5px
        }

        div.yui3-g div {
            margin-right: 20px; 
        }

        div#mailSend {
            margin-bottom: 10px;
        }
        div#groupSend {
            margin-top: 10px;
        }
    </style>
</head>

<body ng-controller="UsersCtrl">
<h3>邮件发送助手</h3> -- 记住我: <a href='http://mail.iyoudao.net'>http://mail.iyoudao.net</a>

<div id="query_part">
    <label>查找:</label>
    <input id="userList" ng-model="usersInput" placeholder="比如查找zhangpei，可以输入zhang, pei或者zhangpei, 多对象以空格或逗号分割, 支持邮件组搜索, 比如搜索ead" />
</div>

<div class="yui3-g">
    <div class="yui3-u-1-4">
        <table border='1'>
            <tr>
                <th>To</td>
                <th>Cc</td>
                <th>名称</td>
                <th>图片</td>
            </tr>
            <tr ng-repeat="user in users | filter:myFilterUserInput">
                <td><input type=checkbox ng-model="user.to" ng-change="buildLink()" /></td>
                <td><input type=checkbox ng-model="user.cc" ng-change="buildLink()"/></td>
                <td>{{user.name}} ({{user.ldap}})</td>
                <td ng-if="user.type == 'user'"><img ng-src="http://weekly.corp.youdao.com/avatar/{{user.ldap}}.jpg" width=50 height=50 /></td>
                <td ng-if="user.type == 'group'"></td>
            </tr>
        </table>
    </div>
    <div class="yui3-u-1-4">
        <div id="mailSend">
            <a ng-href="./php/log.php?url={{link}}">发邮件给如下同事或邮件组：</a>
        </div>
        <table border='1'>
            <tr>
                <th>To</td>
                <th>Cc</td>
                <th>名称</td>
                <th>图片</td>
            </tr>
            <tr ng-repeat="user in users | filter:myFilter">
                <td><input type=checkbox ng-model="user.to" ng-change="buildLink()" /></td>
                <td><input type=checkbox ng-model="user.cc" ng-change="buildLink()" /></td>
                <td>{{user.name}} ({{user.ldap}})</td>
                <td ng-if="user.type == 'user'"><img ng-src="http://weekly.corp.youdao.com/avatar/{{user.ldap}}.jpg" width=50 height=50 /></td>
                <td ng-if="user.type == 'group'"></td>
            </tr>
        </table>

        <div id="groupSend">
            <input ng-model="group_name" placeholder="快捷组名" />
            <a ng-href="./php/group.php?group_name={{group_name}}&url={{link}}">创建</a>
        </div>
    </div>
    <div class="yui3-u-1-4">
        <h4>快捷组名列表:</h4>
        <ul>
            <li ng-repeat="group in groups">
                <a ng-href='{{group.url}}'>{{group.name}}</a>
            </li>
        </ul>
    </div>
</div> 

<script src="./js/index.php.js" type="text/javascript"></script>
</body>

</html>
