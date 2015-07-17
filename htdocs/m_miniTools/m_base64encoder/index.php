<html>

<head>
    <meta charset="utf-8"/>
    <?php
        $input_encode = "";
        $input_decode = "";
        if (array_key_exists("input_encode", $_POST))
            $input_encode = $_POST["input_encode"];
        if (array_key_exists("input_decode", $_POST))
            $input_decode = $_POST["input_decode"];
    ?>
</head>

<body>
    <h3>Base64编码/解码工具</h3>
    <fieldset>
        <legend>base64</legend>
        <form method="POST">
            编码：<input size=200 name="input_encode" value="<?php echo $input_encode ?>" /> <input type=submit name="base64_encode" />
            <div>
            <?php
                echo "<b>编码结果是：</b>", base64_encode($input_encode);
            ?>
            </div>

            <br>

            解码：<input size=200 name="input_decode" value="<?php echo $input_decode ?>" /> <input type=submit name="base64_decode" />
            <div>
            <?php
                echo "<b>解码结果是：</b>", base64_decode($input_decode);
            ?>
            </div>
        </form>
    </fieldset>
</body>
</html>
