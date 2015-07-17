<?php

#========= Global Variables =================
$admon_url_list = Array(
    "click" => "http://nb014x.corp.youdao.com:8000/click",
    "anti"  => "http://nb014x.corp.youdao.com:8000/antifraud",
    "resin-impr" => "http://nb014x.corp.youdao.com:8000/impr?category=response",
);

$hostDict = Array(
    "click" => Array(),
    "antifrauder" => Array(),
    # "AD_EXCHANGE.bidResult" => Array(),
    "AD_EXCHANGE.bid" => Array(),
    # "eadc_left" => Array(),
    # "eadc_left_resin" => Array(),
    "eadc_right" => Array(),
    "eadc_right_resin" => Array(),
    "eadd" => Array(),
    "eadd_resin" => Array(),
    "eadm" => Array(),
    "eadm_resin" => Array(),
    "eadu" => Array(),
    "eadu_resin" => Array(),
    "omedia" => Array(),
    "omedia_resin" => Array(),
);

#========= Functions ========================
function downloadPage($name, $url) {
    $cmd = "wget --quiet '$url' -O '$name'";
    system($cmd);
}

function parseFile($file_path, $key_name) {
    global $hostDict;

    $doc = new DOMDocument();
    @$doc->loadHTMLFile($file_path);
    $query = "//table[1]/tr[@class != 'first']/td[position() = 2]";
    $xpath = new DOMXPath($doc);
    $entries = $xpath->query($query);
    foreach ($entries as $entry) {
        array_push($hostDict[$key_name], $entry->nodeValue);
    }
}

function parseResinImprFile($file_path) {
    global $hostDict;

    $doc = new DOMDocument();
    @$doc->loadHTMLFile($file_path);
    $query = "//table[1]/tr[@class != 'first']/td[position() < 3]";
    $xpath = new DOMXPath($doc);
    $entries = $xpath->query($query);
    $index  = 0;
    $key = "";
    $value = "";
    foreach ($entries as $entry) {
        if ($index % 2 == 0) {
             $key = $entry->nodeValue;
        } else {
             $value = $entry->nodeValue;
        }

        if ($index != 0 and $index % 2 == 1) {
            if ($key == "AD_EXCHANGE.bidResult") {
                # 还不能区分bid 和 bidResult, 所以加到一起
                $key = "AD_EXCHANGE.bid";
            }

            if (array_key_exists($key, $hostDict) && ! in_array($value, $hostDict[$key])) {
                array_push($hostDict[$key], $value);
            }
        }

        $index ++;
    }
}

#========= Main Logic =======================
# download admon result pages
foreach ($admon_url_list as $name => $url) {
    downloadPage("./temp/$name", $url);
}

# read values in html file
parseFile("./temp/click", "click");
parseFile("./temp/anti", "antifrauder");
parseResinImprFile("./temp/resin-impr");

$trArray = Array();
$selectArray = Array();

foreach($hostDict as $name => $hosts) {
    foreach ($hosts as $host) {
        if ($name == "click") {
            $script_file = "./click.sh";

        } else if($name == "antifrauder") {
            $script_file = "./antifrauder.sh";

        } else if($name == "AD_EXCHANGE.bid") {
            $script_file = "./dsp.sh";

        } else if($name == "eadc_left") {
            $script_file = "./eadc_left.sh";

        } else if($name == "eadc_left_resin") {
            $script_file = "./eadc_left_resin.sh";

        } else if($name == "eadc_right") {
            $script_file = "./eadc_right.sh";

        } else if($name == "eadc_right_resin") {
            $script_file = "./eadc_right_resin.sh";

        } else if($name == "eadd") {
            $script_file = "./eadd.sh";

        } else if($name == "eadd_resin") {
            $script_file = "./eadd_resin.sh";

        } else if($name == "eadm") {
            $script_file = "./eadm.sh";

        } else if($name == "eadm_resin") {
            $script_file = "./eadm_resin.sh";

        } else if($name == "eadu") {
            $script_file = "./eadu.sh";

        } else if($name == "eadu_resin") {
            $script_file = "./eadu_resin.sh";

        } else if($name == "omedia") {
            $script_file = "./omedia.sh";

        } else if($name == "omedia_resin") {
            $script_file = "./omedia_resin.sh";

        } else {
            echo "Invalid name: $name\n";
            break;
        }

        $cmd = "ssh $host 'cd /global/share/zhangpei/DeployList/scripts; $script_file'";
        $lines = Array();
        exec($cmd, $lines);

        if (! in_array("<option>$name</option>", $selectArray)) {
            array_push($selectArray, "<option>$name</option>");
        }

        if (count($lines) == 1) {
            array_push($trArray, "<tr id='$name'><td>$host</td><td>" . $lines[0] . "</td></tr>");

        } else if (count($lines) > 1) {
            array_push($trArray, "<tr id='$name'><td rowspan=" . count($lines) . ">$host</td><td>" . array_shift($lines) . "</td></tr>");
            foreach ($lines as $line) {
                array_push($trArray, "<tr id='$name'><td>" . $line . "</td></tr>");
            }
        }
    }
}

file_put_contents("./temp/select.php", implode("\n", $selectArray));
file_put_contents("./temp/tr.php", implode("\n", $trArray));
