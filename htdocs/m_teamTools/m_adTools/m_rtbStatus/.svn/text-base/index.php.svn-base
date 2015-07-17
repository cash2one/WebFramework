<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="../../../css-base/jquery-ui.min.css" type="text/css" media="all" />

<script src="../../../js-base/jquery.min.js"></script>
<script src="../../../js-base/jquery-ui.min.js"></script>
<script src="../../../js-base/json.min.js"></script>
<?php
    $tanx = get_value("tanx", "0");
    $adx = get_value("adx", "0");
    $tade = get_value("tade", "0");
    $allyes = get_value("allyes", "0");
    $bex = get_value("bex", "0");
    $decodeNum = get_value("decodeNum", "0");

    function get_value($key, $def_value) {
        global $_POST;
        if (array_key_exists($key, $_POST))
            return $_POST[$key];
        return $def_value;
    }
  
    function rtbEncode($tanx,$adx,$tade,$allyes,$bex) {
       $tmp= (intval($tanx)) + ((intval($adx))<<4)  ;
       $num = (intval($tanx)) + ((intval($adx))<<4) + ((intval($tade))<<8) + ((intval($allyes))<<12) + ((intval($bex))<<16);
       return $num;
    }

    function getText($num) {
        if ($num == 0) {
            return "<font color='black'> 待提交</font>";
        } else if($num ==1) {
            return "<font color='blue'> 待审核</font>";
        } else if($num ==2) {
            return "<font color='green'> 审核通过</font>";
        } else if($num ==3) {
            return "<font color='red'> 审核失败</font>";
        } else {
            return "<font color='red'> error!!!</font>";
        }
    }

    function rtbDecode($decodeNumS) {
        $decodeNum = intval($decodeNumS);
        return "<p>tanx: ".(getText($decodeNum&15))."  adx: ".(getText(($decodeNum>>4)&15))."  tade: ".(getText(($decodeNum>>8)&15))." allyes: ".(getText(($decodeNum>>12)&15))."  bex: ".(getText(($decodeNum>>16)&15))."</p>";
    }
?>

</head>

<body>
<h3>生成rtb状态值</h3>
<form action="" method="POST" style="width:100%">
    <p>0:待提交  1：待审核   2：审核通过   3：审核不通过</p>
    <p>tanx(101): <input type="text" name="tanx" value="<?php echo $tanx ?>"/>  </p>
    <p>adx(102): <input type="text" name="adx" value="<?php echo $adx ?>"/>  </p>
    <p>tade(103): <input type="text" name="tade" value="<?php echo $tade ?>"/>  </p>
    <p>allyes(104): <input type="text" name="allyes" value="<?php echo $allyes ?>"/>  </p>
    <p>bex(105): <input type="text" name="bex" value="<?php echo $bex ?>"/>  </p>
    <p><input type="submit" name="encode" value="生成状态值" /></p>
    <p><input type="text" name="encodeNum" value="<?php echo rtbEncode($tanx,$adx,$tade,$allyes,$bex) ?>"></input></p>
    <hr/>
    <h3>解析rtb状态值</h3>
    <p><input type="text" name="decodeNum" value="<?php echo $decodeNum?>"></input><input type="submit" name="decode" value="解析状态值" /></p>
    rtb状态：<?php echo rtbDecode($decodeNum) ?>
</form>
<?php
    
?>
</body>
</html>
