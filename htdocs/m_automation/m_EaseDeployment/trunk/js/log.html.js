function escapeString(str){
    var length = str.length;
    var finalStr = tmpStr = '';
        // 遍历每个字符
    for( var i = 0; i < length; i++ ) {
            // 通过if...else if...进行判断是否需要转义的字符
        if ( str[i] === '<' ) { 
            tmpStr = '&lt;';
        }else if ( str[i] === '>' ) { 
            tmpStr = '&gt;';
        }else if ( str[i] === '&' ) {
            tmpStr = '&amp;'; 
        }else if ( str[i] === '"' ) { 
            tmpStr = '&quot;';
        }else if ( str.charCodeAt(i) === 8211 || str.charCodeAt(i) === 8212 ) { 
            tmpStr = '-';
        }else if ( str.charCodeAt(i) === 8216 || str.charCodeAt(i) === 8217 || str.charCodeAt(i) === 8242 ) { 
            tmpStr = '\'';
        }else{
            tmpStr = str[i];
        }
        // 如果你需要添加更多的条件, 可以在这里添加else if代码
        finalStr += tmpStr;
    }

    return finalStr;
}
function create_file_tab(name){
    var li_str = "<li class='tab ui-state-active' title='" + name + "'><a class='file_tab'>" + name + "</a>" + 
                 "<a class='stop_getfile log_link'>STOP</a>" +
                 "<a class='closelogtab log_link'>X</a></li>";
    var div_str = "<div id='" + name + "' class='contents_log'> </div>";
    $("div#log_tabs ul").append(li_str);
    $("div#log_tabs").append(div_str);
}

function startgetfile(filepath){
    $("div[id='"+filepath+"']").remove();
    $("li:contains('" + filepath + "')").remove();
    $("div.contents_log").hide();
    $("li.ui-state-active").addClass("ui-state-default");
    $("li.ui-state-active").removeClass("ui-state-active");

    create_file_tab(filepath);
    $("#deploy_page").tabs('select', 2);
    _lognumber[filepath] = 0;
    _mytimer[filepath] = setInterval(function(){
        getfile(filepath)
        },1000);
}
function getfile(filepath)
{
    var host = filepath.split(":")[0];
    var path = filepath.split(":")[1];
	$.post(_log_php, {"linenum": _lognumber[filepath], "logpath":path, "host":host}, function(message) {
        var result = $.parseJSON(message);
        _lognumber[filepath] = result["linenum"];
        var appendString = "";
        for(var i=0;i <result["lines"].length; i++){
            appendString += escapeString(result["lines"][i])+"<br/>";
        }
        $("div[id='" + filepath + "']").append(appendString); 
	});
}
function stopgetfile(filepath)
{
    clearInterval(_mytimer[filepath]);
    alert("停止刷新文件：" + filepath);
}

$(document).on("click","input#start_getfile",function(e) {
    var filepath = $("div#file_input input.file_path").val();
    startgetfile(filepath); 
    e.preventDefault();
});

$(document).on("click","a.stop_getfile",function(e) {
    var filepath = $(this).parent().attr("title");
    stopgetfile(filepath);
    e.preventDefault();
});

$(document).on("click","li.tab",function(e) {
    // switch all tabs off
    //$("li.ui-state-active").addClass("ui-state-default");
    $("li.tab").removeClass("ui-state-active");
    $("li.tab").addClass("ui-state-default");
    
    // switch this tab on
    $(this).removeClass("ui-state-default");
    $(this).addClass("ui-state-active");
    
    // hide file content
    $("div.contents_file").hide();

    // slide all content up
    $("div.contents_log").hide();
    
    // slide this content up
    var content_show = $(this).attr("title");
    $("div[id='"+content_show+"_file']").show();
    $("div[id='"+content_show+"']").show();
    e.preventDefault();
});

$(document).on("click","a.closelogtab",function(e) {
    var id = $(this).parent().attr("title");
    $("div[id='"+id+"']").remove();
    $(this).parent().remove();

    e.preventDefault();
});

$(function(){
    $(window).scroll(function(){  //只要窗口滚动,就触发下面代码 
        var scrollt = document.documentElement.scrollTop + document.body.scrollTop; //获取滚动后的高度 
        if( scrollt >200 ){  //判断滚动后高度超过200px,就显示  
            $("#回到顶部").fadeIn(400); //淡出     
        }else{      
            $("#回到顶部").stop().fadeOut(400); //如果返回或者没有超过,就淡入.必须加上stop()停止之前动画,否则会出现闪动   
        }
    });

    $("#回到顶部").click(function(){ //当点击标签的时候,使用animate在200毫秒的时间内,滚到顶部
        $("html,body").animate({scrollTop:"0px"},200);
    });
});
