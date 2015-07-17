<?php
require_once("settings.php");
require_once("conf_class/Config.class.php");

$user = $_POST["user"];
$filename = $_POST["filename"];
$confObjStr = str_replace('\"','\\"',$_POST["confObjStr"]);
$file_path = $conf_dir . "$user/$filename";

if(isset($confObjStr)){
	if (get_magic_quotes_gpc())
	$confObjStr = stripslashes($confObjStr);
}
$confObj = json_decode($confObjStr,true);

if($confObj == null){
    echo "1, 保存失败，配置文件为空";
    return;
}

try{
    // parse the vars
    $varlist = $confObj["vars"];
    $varObjlist = array();
    foreach($varlist as $element){
	    $var = new Varible();
	    $var->set($element[0], $element[1]);
	    array_push($varObjlist, $var);
    }

    // parse steps and collects
    $stepObjlist = parse_element("step", $confObj);
    $collectObjlist = parse_element("collect", $confObj);
    $config = new Config($file_path);
    $config->varibles = $varObjlist;
    $config->steps = $stepObjlist;
    $config->collects = $collectObjlist;
	$config->write();
	echo "0, 配置文件保存成功";
}catch (Exception $e){
	echo "1, 配置文件保存失败:" + $e;
}

// 从字典confObj中将配置文件的step与collect信息写入到类Element当中
function parse_element($type, $confObj){
	$elementlist = array();
	if ("step" == $type){
		$objlist = $confObj["steps"];
	} 
    elseif ("collect" == $type){
		$objlist = $confObj["collects"];
	}
    foreach($objlist as $obj){
		if ("step" == $type){
			$element = new Step();
		}
        elseif ("collect" == $type){
			$element = new Collect();
		}		
		$element->setName($obj[$type . ".name"]);
		$element->setCmd($obj[$type . ".cmd"]);
        $element->setDesc($obj[$type . ".desc"]);
        if (array_key_exists($type . ".hostname",$obj)){
            $element->setHostName($obj[$type . ".hostname"]);
        }
        if (array_key_exists($type . ".cmd.sep",$obj)){
            $element->setCmdSep($obj[$type . ".cmd.sep"]);
        }
        if (array_key_exists($type . ".ignore_fail",$obj)){
            $element->setIgnoreFail($obj[$type . ".ignore_fail"]);
        }
        array_push($elementlist, $element);
 	}
	return $elementlist;
}
?>

