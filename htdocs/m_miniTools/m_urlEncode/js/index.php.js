$(function() {
    $("input#encode").click(function(e) {
        var input_url = $("textarea#url_input").val(); 
        var php_file = "./php/encode.php";
        $.get(php_file, {"url": input_url}, function(ret) {
            $("div#output").html("<p>" + ret + "</p>" + "<a title='" + ret + "' href='" + ret + "' target=_blank>链接地址</a>"); 
        });
    });

    $("input#decode").click(function(e) {
        var input_url = $("textarea#url_input").val(); 
        var php_file = "./php/decode.php";
        $.get(php_file, {"url": input_url}, function(ret) {
            $("div#output").html("<p>" + ret + "</p>" + "<a title='" + ret + "' href='" + ret + "' target=_blank>链接地址</a>"); 
        });
    });
});
