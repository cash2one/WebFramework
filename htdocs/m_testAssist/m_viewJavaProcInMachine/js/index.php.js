$(function() {
    load_user_processes();

    $('input[type=checkbox]').click(function(e) {
        load_user_processes();
    });

    $("a#refresh").click(function(e) {
        load_user_processes();
        e.preventDefault();
    });

    function load_user_processes() {
        $("table#result_table").html("");
        var $machines_selected = $("input[type=checkbox]:checked");
        var machines_str = ""
        $.each($machines_selected, function(idx, input) {
            machines_str += $(input).attr('id') + ",";
        });

        var php_file = "./php/load_user_processes.php";
        $.get(php_file, {"machine_list": machines_str}, function(ret) {
            $("table#result_table").html(ret);            
        });
    }
});
