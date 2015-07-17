$(function() {
    $("input#apl_box").focus();
    show_prod_lines();

    $("input#apl_btn").click(function(e) {
        var prod_line_name = $("input#apl_box").val();
        if (prod_line_name == "") {
            alert("请输入产品线名称");
            return false;
        }

        var php_file = "./php/add-prod-line.php";
        $.getJSON(php_file, {"prod_line_name": prod_line_name}, function(data) {
            alert(data[1]);
            show_prod_lines();
            $("input#apl_box").focus();
        });
    });

    function show_prod_lines() {
        var php_file = "./php/show-prod-line.php";
        $("div#apl_result").html("").load(php_file);
    }
});
