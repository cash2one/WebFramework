<html>
<head>
    <meta charset="utf-8" />

    <link rel="stylesheet" type="text/css" href="./css/index.php.css" />
    <link rel="stylesheet" href="../../css-base/jquery-ui.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="../../css-base/jquery-ui.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="../../css-base/grid.css" type="text/css" />

    <script src="../../js-base/jquery.min.js"></script>
    <script src="../../js-base/jquery-ui.min.js"></script>
    <script src="../../js-base/ldap_login.js"></script>
    <script src="../../js-base/json.min.js"></script>
</head>

<body>
    <h2>查看机器上的Java进程</h2>

    <div class='yui3-g'>
        <div class='yui3-u-1-8'>
        <b>选择机器:</b>
        <br>
        <br>
        <?php
            $machines = Array();
            $ref_dir = "../m_ProcessPerfMonitor/perf_results";
            $handle = opendir($ref_dir);
            while (false !== ($machine_name = readdir($handle))) {
                if (substr($machine_name, 0, 1) == ".") continue;
                array_push($machines, "<input type=checkbox name='machine' id='$machine_name'> $machine_name <br>");
            }
            closedir($handle);
        
            sort($machines);
            echo implode("\n", $machines);
        ?>

        <br>
        <a href="" id="refresh">刷新</a>
        </div class=''> 

        <div class='yui3-u-7-8'>
            <table id="result_table" border='1' style='width:100%'>
            </table>
        </div>
    </div>

    <script src="./js/index.php.js"></script>
</body>

</html>
