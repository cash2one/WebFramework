// ============================= Main Logic Area =================================
// run after document.ready
$(function() {
    //load all pages
    $("#tabs-1").load("./html/home.html");
    $("#tabs-2").load("./html/graphs.html");
    $("#tabs-3").load("./html/tables.html");

    // render tabs
    $("#tabs").tabs();
});
