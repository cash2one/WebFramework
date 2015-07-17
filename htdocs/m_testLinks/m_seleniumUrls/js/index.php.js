$(function() {

    load_page();
    
    $("select#url_select").change(function(e){
        load_page();
    }); 

    function load_page() {
        var url = $("select#url_select").val();
        if (url.substring(0, 5) == "https") {
            window.open(url, "_target");
        } else {
            $("iframe#content").attr("src", url);
        }
    }
});
