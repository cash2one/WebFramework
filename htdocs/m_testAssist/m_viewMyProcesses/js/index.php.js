$(function() {
    load_user_processes($("select#user_list").val());

    $('select#user_list').change(function(e) {
        load_user_processes($(this).val());
    });

    function load_user_processes(user) {
        $("table#result_table").html("");            
        var php_file = "./php/load_user_processes.php";
        $.get(php_file, {"user": user}, function(ret) {
            $("table#result_table").html(ret);            
        });
    }
});
