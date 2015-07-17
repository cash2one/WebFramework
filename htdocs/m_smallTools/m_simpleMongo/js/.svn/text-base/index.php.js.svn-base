$(function() {
    // === 初始化区
    $("input#host").focus();
    load_login_info();

    // === 函数定义区
    function load_login_info() {
        var php_file = "./php/search.php";
        $.getJSON(php_file, function(data) {
            hosts = [];
            ports = [];
            users = [];
            $.each(data, function(idx, row) {
                hosts.push(row[0]); 
                ports.push(row[1]); 
                users.push(row[2]); 
            });
            $("#host").autocomplete({
                source: hosts
            });
            $("#port").autocomplete({
                source: ports
            });
            $("#user").autocomplete({
                source: users
            });
        });
    }
});
