<html>

<head>
    <meta charset="utf-8"/>
    <?php
        $val = get_val("input", 0);
        $unit = get_val("unit", "sec");

        function get_val($pname, $defVal) {
            global $_POST;
            if (array_key_exists($pname, $_POST)) {
                return $_POST[$pname];
            }

            return $defVal;
        }
    ?>
</head>

<body>
    <h3>时间可读化工具</h3>
    <form method="POST">
        输入：<input type="text" name="input" value="<?php echo $val; ?>" placeholder="<?php echo "最大值" . PHP_INT_MAX ?>" /> 
        <select name="unit">
        <?php
            $timeArray = array(
                "milli" => "毫秒",
                "sec" => "秒",
                "min" => "分钟",
                "hour" => "小时");
            
            foreach ($timeArray as $name => $rName) {
                if ($name == $unit) {
                    echo '<option value="' . $name . '" selected>' . $rName . '</option>';
                } else {
                    echo '<option value="' . $name . '">' . $rName . '</option>';
                }
            }
        ?>
        </select>

        <input type="submit" value="转化" />
    </form>

    <br>

    <div>
    <?php
        $time_count_in_sec = 0;
        if ($unit == "milli")
            $time_count_in_sec = (int)$val / 1000;

        elseif ($unit == "sec")
            $time_count_in_sec = $val;
    
        elseif ($unit == "min")
            $time_count_in_sec = $val * 60;

        elseif ($unit == "hour")
            $time_count_in_sec = $val * 60 * 60;

        $secs = $time_count_in_sec % 60;
        $time_count_in_sec -= $secs; 

        $min = $time_count_in_sec % 3600;
        $time_count_in_sec -= $min;
        $min /= 60;

        $hour = $time_count_in_sec % (3600 * 24);
        $time_count_in_sec -= $hour;
        $hour /= 3600;

        $day = $time_count_in_sec / (3600 * 24);
        
        echo "结果: ", sprintf("%s天%s小时%s分钟%s秒", $day, $hour, $min, $secs);
    ?>
    </div>
</body>

</html>
