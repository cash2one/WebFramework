// solution src: http://blog.csdn.net/apollokk/article/details/9183247
jQuery.expr[':'].Contains = function(a, i, m) {
  return jQuery(a).text().toUpperCase()
      .indexOf(m[3].toUpperCase()) >= 0;
};

jQuery.expr[':'].contains = function(a, i, m) {    
  return jQuery(a).text().toUpperCase()    
      .indexOf(m[3].toUpperCase()) >= 0;    
};

// triggered when document is ready
$(document).ready(function(){
    $("div#menu ul.menu").load("./php/nav_menu.php");

    var php_file = "./php/user_record.php";
    $.get(php_file);

    $(document).on("click", "table a", function(e) {
        var url = $(this).attr("href");
        $.post("./php/save_access.php", {"url": url}, function(data){
            window.location.href = url;
        });
        e.preventDefault();
    });

    $("div input#filter_box").keyup(function(e) {
        var s_val = $(this).val();
        // $("table td a").removeClass("hide2");
        $("table td a").show();
        if (s_val != "") {
            $("table td a:not(:contains('" + s_val + "'))").hide();
        }
    });

    $("a#complain").click(function(e) {
        alert("别吐了，槽已经满了~");
        e.preventDefault();
    });
})
