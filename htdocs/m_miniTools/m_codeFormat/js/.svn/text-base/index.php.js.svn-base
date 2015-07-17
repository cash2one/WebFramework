$(function() {

});

$("input#get_info").click(function(e) {
    var rb_code_content = $("textarea#rb_code_content").val();
    var lines = rb_code_content.split("\n");
    for (var i = 0; i < lines.length; i++) {
        var line = lines[i];
        if (line[0] == '\t') {
            line = line + "<br>";
        }
        line = line.replace(/\t/g, "&nbsp;&nbsp;&nbsp;&nbsp;");
        line = line.replace(/ /g, "&nbsp;");
        lines[i] = line;
    }

    $("div#result").html(lines.join(""));
});
