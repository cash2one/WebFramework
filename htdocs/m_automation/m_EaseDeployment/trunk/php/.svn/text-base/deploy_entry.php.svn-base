<?php
    
    require_once("settings.php");
    $user        = $_POST["user"];
    $deployUser  = $_POST["deployuser"];
    $key         = $_POST["key"]; //机群登录密码
    $password    = $_POST["password"]; //ldap 密码 加密后的
    $filePath    = $_POST["filepath"];
    $logPath     = $_POST["logpath"];
    $hostName    = $_POST["hostname"];
    $deployName  = $_POST["deployname"];
    $type        = $_POST["type"];

    if($type == 1){
        $cmd = "/global/share/test/deploy_web/run.py -f ".$filePath." -c ".$deployName." -m ".$hostName." -u ".$user." -p ".$password." -l ".$logPath." -e ".$deployUser." -k ".$key;
    }
    else if($type == 2){
        //单步执行
        $cmd = "/global/share/test/deploy_web/run.py -f ".$filePath." -s ".$deployName." -m ".$hostName." -u ".$user." -p ".$password." -l ".$logPath." -e ".$deployUser." -k ".$key;
    }
    else{
        echo "0";    
    }
    $output = null;
    exec($cmd,$output);
    echo $output;
    echo $cmd;
?>
