$(function() {
    $("div#content").html("").load("./php/pages/codeSearchPage.php");

    $("div#index span a").click(function(e) {
        $("div#index span a").removeClass("selected");
        $(this).addClass("selected");

        var name = $(this).attr("name");
        $("div#content").html("").load("./php/pages/" + name + "Page.php");

        e.preventDefault();
    });
});
