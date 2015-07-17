<html>
<head>
    <meta charset="utf-8"/>
    <script src="../../../../js-base/jquery.min.js"></script>
    <?php
        include("./serviceList.php");
    ?>
    <style>
    </style>
</head>

<body>

<div>
    <h3>添加新版本:</h3>
    服务名称：
    <select id="prodList">
    <?php
    foreach ($serviceList as $serviceName) {
        echo "<option>$serviceName</option>", "\n";
    }
    ?>
    </select>

    版本号：
    <input type=text id="version" />

    <input type=button id="addNewVersion" value="添加" />
</div>

<div>
    <h3>测试执行</h3>
    服务名称：
    <select id="serviceList">
    <?php
    foreach ($serviceList as $serviceName) {
        echo "<option>$serviceName</option>", "\n";
    }
    ?>
    </select>

    版本号：
    <select id="versionList">
    <?php
        include("./dbUtil.php");
        $firstService = $serviceList[0];
        $versions = dbUtil(LoadVersions, array($firstService));
        sort($versions);
        foreach (array_reverse($versions) as $version) {
            echo "<option>$version</option>", "\n";
        }
    ?>
    </select>

    <input type=button id='startRun' value='开始' />
    <input type=button id='stopRun' value='停止' />

    <div id="runInfo">
    </div>
</div>

<h3>执行状态</h3>
<div id="status">
</div>

<script src="../js/home.php.js"></script>
</body>
</html>
