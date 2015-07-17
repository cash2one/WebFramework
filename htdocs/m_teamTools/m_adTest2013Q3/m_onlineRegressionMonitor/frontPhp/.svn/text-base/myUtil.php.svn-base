<?php

date_default_timezone_set("PRC");
include("interface.php");

function getTreeHtmlStr() {
    $retArray = array(); 
    array_push($retArray, '<ul id="tt" class="easyui-tree">');

    try {
        $mongo = new MongoClient('mongodb://nb403:27127/admin:admin');
        $db = $mongo->onlineRegressionMonitor;
        $collection = $db->prodVersionDetail;
        $cursor = $collection->find();

        $temp_arr = array();
        foreach ($cursor as $doc) {
            $prodName = $doc["prodName"];
            $version  = $doc["version"];
            $tsList   = $doc["tsList"];
            if (count($tsList) == 0) continue;
            if (!array_key_exists($prodName, $temp_arr)) {
                $temp_arr[$prodName] = array();
            }
            $temp_arr[$prodName][$version] = $tsList;
        }

        foreach ($temp_arr as $prodName => $sub_arr) {
            array_push($retArray, '<li data-options="attributes:{type:0}, state:\'closed\'">');
            array_push($retArray, '<span>' . $prodName . '</span>');
            array_push($retArray, '<ul>');

            krsort($sub_arr);
            foreach ($sub_arr as $version => $tsList) {
                array_push($retArray, '<li data-options="attributes:{type:0}, state:\'closed\'">');
                array_push($retArray, '<span>' . $version . '</span>');
                array_push($retArray, '<ul>');
                
                foreach ($tsList as $ts) {
                    array_push($retArray, '<li data-options="attributes:{diff_path:\'' . "$prodName:$version:$ts" . '\', type:1}">');
                    array_push($retArray, '<span>' . date("Y-m-d H:i:s", $ts) . '</span>');
                    array_push($retArray, '</li>');
                }

                array_push($retArray, '</ul>');
                array_push($retArray, '</li>');
            }

            array_push($retArray, '</ul>');
            array_push($retArray, '</li>');
        }

        $mongo->close();
    } catch(MongoConnectionException $e) {
        die($e->getMessage());
        return; 
    }

    array_push($retArray, '</ul>');
    return implode("\n", $retArray);
}
