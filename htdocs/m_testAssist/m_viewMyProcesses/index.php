<html>
<head>
    <meta charset="utf-8" />

    <link rel="stylesheet" type="text/css" href="./css/index.php.css" />
    <link rel="stylesheet" href="../../css-base/jquery-ui.min.css" type="text/css" media="all" />
    <link rel="stylesheet" href="./css/examples.css" type="text/css" media="all" />

    <script src="../../js-base/jquery.min.js"></script>
    <script src="../../js-base/jquery-ui.min.js"></script>
    <script src="../../js-base/ldap_login.js"></script>
    <script src="../../js-base/json.min.js"></script>
</head>

<body>
    <h2>查看我的Java进程</h2>

    选择用户:
    <select id="user_list">
    <?php
        $date = strftime("%Y%m%d", time());
        $users = Array();
        $ref_dir = "../m_ProcessPerfMonitor/perf_results";
        $handle = opendir($ref_dir);
        while (false !== ($machine_name = readdir($handle))) {
            if (substr($machine_name, 0, 1) == ".") continue;
            $user_file = $ref_dir . "/" . $machine_name . "/" . $date . "/*-*.cmd";
            $cmd_files = glob($user_file);
            foreach ($cmd_files as $cmd_file) {
                $filename = basename($cmd_file);
                $fields = preg_split("/\.|-/", $filename);
                $user = $fields[1];
                $option = "<option>$user</option>";
                if (! in_array($option, $users)) {
                    array_push($users, $option);
                }
            }
        }
        closedir($handle);
        
        sort($users);
        echo implode("\n", $users);
    ?>
    </select>

    <br>
    <br>

    <table id="result_table" border='1'>
    </table>

    <script src="./js/index.php.js"></script>
</body>

</html>
