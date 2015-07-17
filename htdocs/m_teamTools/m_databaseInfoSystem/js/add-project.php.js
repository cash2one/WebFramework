$(function() {
    $("input#apj_box").focus();
    show_prod_lines2();

    $("input#apj_btn").click(function(e) {
        var prod_line_name = $("select#apj_apl").val();
        var proj_name = $("input#apj_box").val();
        if (proj_name == "") {
            alert("请输入项目/服务名称");
            return false;
        }

        var php_file = "./php/add-proj.php";
        $.getJSON(php_file, {"prod_line_name": prod_line_name, "proj_name": proj_name}, function(data) {
            alert(data[1]);
            show_proj_list();
            $("input#apj_box").focus();
        });
    });

    $("select#apj_apl").change(function(e) {
        show_proj_list();
    });

    function show_proj_list() {
        var prod_line = $("select#apj_apl").val();
        if (prod_line == "") {
            alert("选个产品线吧");
            return false;
        }

        var php_file = "./php/show-proj.php";
        $("div#apj_result").html("").load(php_file, {"prod_line": prod_line});
    }

    function show_prod_lines2() {
        var php_file = "./php/show-prod-line2.php";
        $("select#apj_apl").html("").load(php_file, function(e) {
            show_proj_list();
        });
    }
});
