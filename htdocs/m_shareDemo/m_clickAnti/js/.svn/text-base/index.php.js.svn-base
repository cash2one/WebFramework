$(function(e) {
    $("div#idx a").click(function(e) {
        var file_name = $(this).attr("href");
        var file = "./content/" + file_name + ".html?ver=" + new Date().getTime();
        $("div#content pre").html("").load(file);

        e.preventDefault();
    });

    $(document).tooltip();
});
