<?php
    
    require_once("settings.php");
    $user        = $_POST["user"];
    $key         = $_POST["key"]; //机群登录密码
    $hostName    = $_POST["hostname"];
    $deployName  = $_POST["deployname"];
    $type        = $_POST["type"];

    if($type == 1){
        $cmd = "/global/share/test/deploy_web/stop.py -c ".$deployName." -m ".$hostName." -u ".$user;
    }
    else if($type == 2){
        $cmd = "/global/share/test/deploy_web/stop.py -s ".$deployName." -m ".$hostName." -u ".$user;
    }
    else{
        echo "0";    
    }
    $output = null;
    exec($cmd,$output);
    echo $output;
    echo $cmd;
?>
