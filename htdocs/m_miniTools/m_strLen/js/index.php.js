$(function() {
    $("textarea#input_text").mouseup(function() {
        show_result();
    });
    $("textarea#input_text").keyup(function() {
        show_result();
    });

    function show_result() {
        var content = $("textarea#input_text").val(); 
        content = content.replace(/\n/g, "");
        $("label#output").html("" + content.length + "个字符");
    }

});
