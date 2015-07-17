$(function(e) {
    $("a#home").click(function(e) {
        $("iframe").attr("src", "./frontPhp/home.php");
        e.preventDefault();
    });

    $("a#result").click(function(e) {
        $("iframe").attr("src", "./frontPhp/diff.php");
        e.preventDefault();
    });
});
