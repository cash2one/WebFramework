$(function() {

    var name = $("#name");
    var url = $("#url");
    var allFields = $( [] ).add( name ).add( url );

    $("select#url_select").load("./php/load.php", function(e) {
        load_page();
    });

    $( "#dialog-form" ).dialog({
        autoOpen: false,
        height: 300,
        width: 350,
        modal: true,
        buttons: {
            "添加": function() {
                var name_str = name.val();
                var url_str = url.val();
                if (name_str == "") {
                    name.addClass("ui-state-error");
                    return false;
                }
                if (url_str == "") {
                    url.addClass("ui-state-error");
                    return false;
                }
                
                var php_file = "./php/add.php";
                $_this = $(this);
                $.get(php_file, {"name":name_str, "url": url_str}, function(e) {
                    $_this.dialog( "close" );
                    location.reload();
                });
            },
            "取消": function() {
                $( this ).dialog( "close" );
            },
         },
         close: function() {
            allFields.val( "" ).removeClass( "ui-state-error" );
         }
    });
 
    $("select#url_select").change(function(e){
        load_page();
    }); 

    $("input#cont").click(function(e) {
        $( "#dialog-form" ).dialog( "open" );
    });

    function load_page() {
        var url = $("select#url_select").val();
        $("span#location").html("<a href='" + url + "' target=_blank>直接访问</a>");
        if (url.substring(0, 5) == "https") {
            $("iframe#content").attr("src", "about:blank");
            window.open(url, "_target");
        } else {
            $("iframe#content").attr("src", url);
        }
    }
});
