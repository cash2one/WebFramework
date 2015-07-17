$(function() {
    $("input#mongo_name").focus();

    $("input#reg").click(function(e) {
        var name = $("input#mongo_name").val();
        var host = $("input#mongo_host").val();
        var port = $("input#mongo_port").val();

        if (name == "" || host == "" || port == "") {
            alert("Error: Invalid Input!");
            return false;
        }

        $.get("./save.php", {"name": name, "host": host, "port":port}, function() {
            window.location.href='index.php';
        });
    }); 

    $("input#cancel").click(function(e) {
        window.location.href='index.php';
    });
});
