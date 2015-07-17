<html>
<head>
    <meta charset="utf-8"/>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
    <link rel="Stylesheet" href="http://ajax.aspnetcdn.com/ajax/jquery.ui/1.8.10/themes/redmond/jquery-ui.css" />

    <script>
        $(function() {
            var val = 4;
            var _handler;
            $( "#progressbar" ).progressbar({
                value: 4,
                change: function() {
                    if ($(this).progressbar("value") == 100) {
                        alert("Hello World");
                        clearInterval(_handler);
                    }
                }
            });

            function showStatus() {
                val ++;
                $("#info").html("当前进度：" + val + "%");
                $( "#progressbar" ).progressbar("value", val);
            }

            var _handler = setInterval(showStatus, 100);
        });

    </script>
</head>

<body>
    <div id="progressbar"></div> 
    
    <div id="info"></div>
</body>
</html>
