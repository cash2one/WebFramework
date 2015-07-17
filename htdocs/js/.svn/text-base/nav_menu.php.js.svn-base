// == Global Variables
last_file = "";

// triggered when document is ready
$(function(){
    load_desc("../readme.tts");
    $("body div:last").hide();
});

$(document).on("mouseenter", "h1", function(e) {
    load_desc("../readme.tts");

    e.preventDefault();
});

$(document).on("click", "li a span", function(e) {
    var url = document.location + $(this).parent().attr("name").substr(3);
    window.open(url, "_self");

    e.preventDefault();
});

$(document).on("mouseleave", "li a span", function(e) {
    if ($(this).hasClass("item")) {
        $(this).css("color", "rgb(169,169,169)");
    }
    e.preventDefault();
});

$(document).on("mouseenter", "li a span", function(e) {
    if ($(this).hasClass("item")) {
        $(this).css("color", "rgb(255,255,255)");
    }
    if (! $(this).hasClass("top")) {
        var file = $(this).parent().attr("name") + "/readme.tts";
        // load_desc(file);
    }

    e.preventDefault();
});

function load_desc(file) {
    if (file != last_file) {
        last_file = file;

        $.get("php/read_desc.php", {"path": file}, function(message) {
            $("div#desc").html(message);
        });
    }
}
