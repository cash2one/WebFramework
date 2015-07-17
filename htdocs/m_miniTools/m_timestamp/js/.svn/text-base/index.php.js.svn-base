$(function() {

    $("input#ts_to_time").click(function(e) {
        var input_timestamp = $("input#ts").val();
        var php_file = "./php/get_read_time.php";
        $.get(php_file, {"param": input_timestamp}, function(ret) {
            $("label#result_ts").html(ret);
        });
    });    

    $("input#time_to_ts").click(function(e) {
        var input_time = $("input#time_str").val();
        var php_file = "./php/get_timestamp.php";
        $.get(php_file, {"param": input_time}, function(ret) {
            $("label#result_ts2").html(ret);
        });
    });    
});
