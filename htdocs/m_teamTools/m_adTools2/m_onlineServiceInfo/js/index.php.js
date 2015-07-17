$(function() {
    $("table tbody tr").hide();
    $("table tbody tr#click").show();

    $("select").change(function(e) {
        var select_value = $(this).val();
        select_value = select_value.replace(".", "\\.");
        $("table tbody tr").hide();
        $("table tbody tr#" + select_value).show();
    });

});
