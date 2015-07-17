<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="../../css-base/jquery-ui.min.css" type="text/css" media="all" />
    <script src="../../js-base/jquery.min.js"></script>
    <script src="../../js-base/jquery-ui.min.js"></script>
    <script src="../../js-base/json.min.js"></script>

    <style>
        div {margin-top: 10px;}        
        div {margin-bottom: 10px;}        
        table {width: 100%;}
        table textarea {
            width: 100%;
            border: 0px;
            background-color: #F0FFFF;
            margin: 0px;
        }
        table td.op {
            width: 50px;
            font-size: 0.8em;
        }
        a {
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <h3>Wiki表格生成工具</h3>

    <div id="header">
        列数: <input type='text' id='fields_cnt' size=4 value='4' />
        行数: <input type='text' id='row_cnt' size=4 value='10' />
        <input type='button' id='set_fields_cnt' value="生成表格" />
        <input type='button' id="output" value="输出为Wiki格式" />
    </div>

    <div id='history_log'>
    <?php
        # include("load.php");
    ?>
    </div>

    <table border='1'>
    </table>

    <div id="outputDiv"></div>

    <script>
        window.onbeforeunload = function(){
            return '提示：确定离开!';
        }

        var _row_str = "";
        $(function() {
            $("input#set_fields_cnt").click(function(e) {
                $("div#history_log").hide();
                var fieldsCnt = $("input#fields_cnt").val();
                var rowCnt    = $("input#row_cnt").val();

                var trs = $("table tr")
                if (trs.length != 0) {
                    var ret = confirm("当前表格数据会丢失，确定生成新表格?");
                    if (ret != true) {
                        return false;
                    }
                }

                _row_str = ""
                for (var i = 0; i < fieldsCnt; i++) {
                    _row_str += "<td><textarea></textarea></td>";
                }
                _row_str += "<td class='op'><a href='' class='add'>添加行</a><br><a href='' class='delete'>删除行</a></td>";
                _row_str = "<tr>" + _row_str + "</tr>" + "\n";

                $("table").html("");
                for (var i = 0; i < rowCnt; i++) {
                    $("table").append(_row_str);
                }
            });

            $(document).on("click", "a", function(e) {
                var clazzName = $(this).attr("class");
                if (clazzName == "add") {
                    $(this).parents("tr").after(_row_str);

                } else if (clazzName == "delete") {
                    $(this).parents("tr").remove();
                }
                e.preventDefault();
            });

            $("input#output").click(function(e) {

                var $output = $("div#outputDiv")
                $output.html("");
                $.each($("table tr"), function(idx, tr) {
                    var tas = $("textarea", tr);
                    var row_str = "||";
                    $.each(tas, function(idx, ta) {
                        row_str += " " + $(ta).val() + " ||";
                    });
                    row_str = row_str.replace(/\n/g, " <<BR>> ");
                    row_str = row_str.replace(/</g, "&lt;");
                    row_str = row_str.replace(/>/g, "&gt;");
                    $output.append(row_str + "<br>" + "\n");
                });

                $.post("./save.php", {"content": $output.html()});
            });

            $("div#history_log input").click(function(e) {
                var filename = $(this).data('filename');
                $("table").load("./loadFile.php", {"filename": filename});
            });
        });
    </script>
</body>
</html>
