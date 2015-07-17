<?php
$myconn=mysql_connect("tb029x.corp.youdao.com:3306","yinxj","^YHN6yhn");
mysql_query("set names 'gbk'");
mysql_select_db("perfdata",$myconn);
$strSql1="SELECT * FROM indexTable";
$result=mysql_query($strSql1,$myconn);
$retArray = array();

while(list($id, $userName, $product, $type, $cubetype, $numvar, $strvar, $machinevar) = mysql_fetch_array($result)) {

    if (!array_key_exists($userName, $retArray)) {
        $retArray[$userName] = array();
    }

    if (!array_key_exists($product, $retArray[$userName])) {
        $retArray[$userName][$product] = array();
    }

    $type_cube = $type."".$cubetype;
    if (!array_key_exists($type_cube, $retArray[$userName][$product])) {
        $retArray[$userName][$product][$type_cube] = array();
    }	

	$graphnum = explode(",", $numvar);
	$graphstr = explode(",", $strvar);
	$graphmachine = explode(",", $machinevar);
	$graph_name = array_merge($graphnum, $graphstr, $graphmachine);
	foreach($graph_name as $name) {
    	if (!array_key_exists($name, $retArray[$userName][$product][$type_cube])) {
        	$retArray[$userName][$product][$type_cube][$name] = 0;
    	}
	}
}	

echo json_encode($retArray);
mysql_close($myconn);
?>
