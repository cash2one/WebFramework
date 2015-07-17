<?php

require_once("settings.php");
require_once("conf_class/Config.class.php");

$user = $_GET["user"];
$filename = $_GET["filename"];
$file_path = $conf_dir . "$user/$filename";

// 读取配置文件里的信息到全局变量confObj中
function read_file($file_path) {
	$confObj = array(
		"vars" => array(),
		"steps" => array(),
		"collects" => array(),
	);
        
    $config = new Config($file_path);
    $config->parse();

	foreach($config->varibles as $var){
		array_push($confObj["vars"], array($var->name, $var->value));
	}

	$confObj = read_element("step", $config, $confObj);
    $confObj = read_element("collect", $config, $confObj);
	return $confObj;
}

function read_element($type, $config, $confObj){
	if ("step" == $type)
		$list = $config->steps;
    elseif ("collect" == $type)
		$list = $config->collects;
    foreach($list as $element){
		$temp_dict = array();
		$temp_dict[$type . ".name"] = $element->getName();
        $temp_dict[$type . ".desc"] = $element->getDesc();
		$temp_dict[$type . ".cmd"] = $element->getCmd();
		$temp_dict[$type . ".ignore_fail"] = $element->getIgnoreFail();
		$temp_dict[$type . ".hostname"] = $element->getHostName();
        if ("" == $element->getCmdSep())
			$temp_dict[$type . ".cmd.sep"] = null;
		else
			$temp_dict[$type . ".cmd.sep"] = $element->getCmdSep();
        array_push($confObj[$type."s"], $temp_dict);
 	}
	return $confObj;
}

// =========== Main Logic 
$retObj = read_file($file_path);
echo json_encode($retObj);
