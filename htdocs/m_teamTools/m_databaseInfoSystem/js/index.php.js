$(function(e) {
    $pages = $('div#tabs div');

    $.each($pages, function(idx, page) {
        var page_url = "./pages/" + $(page).attr("id").replace("tabs-", "") + ".php";
        $(page).load(page_url);
    });
});
