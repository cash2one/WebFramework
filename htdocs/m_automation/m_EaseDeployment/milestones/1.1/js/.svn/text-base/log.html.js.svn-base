$(document).on("click","li.tab",function(e) {
    // switch all tabs off
    //$("li.ui-state-active").addClass("ui-state-default");
    $("li.tab").removeClass("ui-state-active");
    $("li.tab").addClass("ui-state-default");
    
    // switch this tab on
    $(this).removeClass("ui-state-default");
    $(this).addClass("ui-state-active");
   
    // slide all content up
    $("div.contents_log").hide();
   
    // slide this content up
    var content_show = $(this).attr("title");
    $("div[id='"+content_show+"']").show();

    e.preventDefault();
});

$(document).on("click","a.closelogtab",function(e) {
    var id = $(this).parent().attr("title");
    $("div[id='"+id+"']").remove();
    $(this).parent().remove();

    e.preventDefault();
});
