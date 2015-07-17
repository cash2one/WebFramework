$(function() {
    $("a.update").click(function(e) {
        var tname = $("table").attr("name");
        var name = $("input[name='name']").val();
        var tags = $("input[name='tags']").val();
        var douban = $("input[name='douban']").val();

        var php_file = "./update_book_info.php";
        $.post(php_file, {"tname": tname, "name":name, "tags": tags, "douban": douban}, function(e) {
            window.location.href="../index.php";
        });
        e.preventDefault();
    });
});
