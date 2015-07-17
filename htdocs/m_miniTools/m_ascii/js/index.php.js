$(function() {

    $("input#char").blur(function(e) {
        char_to_ascii();
    });

    $("input#char").keyup(function(e) {
        char_to_ascii();
    });

    $("input#ascii").blur(function(e) {
        ascii_to_char();
    });

    $("input#ascii").keyup(function(e) {
        ascii_to_char();
    });

    function char_to_ascii() {
        var ch = $("input#char").val();
        if (ch == "") return;
        $("label#result_char").html(ch.charCodeAt());
    }

    function ascii_to_char() {
        var ascii = $("input#ascii").val();
        if (ascii == "") return;
        $("label#result_ascii").html(String.fromCharCode(parseInt(ascii)));
    }

});
