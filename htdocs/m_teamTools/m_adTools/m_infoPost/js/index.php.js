$(function() {
    // init area
    $("div#post_add").hide(); 
    $("div#title_box").load("php/load_posts.php", function(e) {
        $("table td:first").trigger("click");
    });

    $post_show = $("<tr><td id='post_show'></td></tr>").css("background", "yellow");

    $(document).on("click", "table td",  function(e) {
        if ($(this).hasClass("highlight")) {
            $(this).removeClass("highlight");
            $post_show.hide();
            
        } else {
            $("table td").removeClass("highlight");
            $(this).addClass("highlight");
            $("div#post_add").hide();
            $("div#post_show").show();
            $post_show.show();
            $post_show.load("php/show_post.php", {"file": $(this).attr("id")});
            $(this).parent().after($post_show);
        }

        e.preventDefault();
    });

    $("a#add_news").click(function(e) {
        $("div#post_show").hide();
        $("div#post_add").show();
        $("textarea").focus();
        e.preventDefault();
    });

    $("input#post_btn").click(function(e) {
        var ret = confirm("确定保存?");
        if (ret != true) return false; 

        $(this).attr("disabled", true);
        var news_content = $("textarea").val();
        if (news_content != "") {
            $.post("php/post_news.php", {"data": news_content}, function(data) {
                // clear content of textarea when news posted to server
                $("textarea").val("");

                $("div#post_add").hide();
                $("div#post_show").show();
                $("div#title_box").load("php/load_posts.php", function(e) {
                    $("table td:first").trigger("click");
                });
            });
        } else {
            alert("Error: news empty!");
        }
        $(this).attr("disabled", false);
    });
});
