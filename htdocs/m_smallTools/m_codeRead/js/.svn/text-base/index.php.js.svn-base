$(function() {
    // SyntaxHighlighter and Chrome vertical scrollbar bug: http://blog.gorwits.me.uk/2011/02/01/syntaxhighlighter-and-chrome-vertical-scrollbar-bug/
    $("table#entry_table tbody").load("./php/read_svn_history.php"); 

    $(document).on("click", "table#entry_table tr td a", function(e) {
        var url = $(this).html();
        var md5Val = hex_md5(url);
        window.location = "./reader.php?dir=" + md5Val;
        e.preventDefault();
    });

    $("input#svn_out").click(function(e) {
        $(this).attr("disabled", "disabled");
        var ldap_login_check_php = "../../php-base/ldap_login_check.php";
        if (_ldap == null || _passwd == null) {
            ldap_login(ldap_login_check_php, after_login);
      
        } else {
            after_login();
        }
    });

    function after_login() {
        var phpFile = "./php/svn_export.php";
        $.post(phpFile, {"code": $("input#svn_addr").val(), "ldap": _ldap, "passwd": _passwd}, function(msg) {
            history.go(0);
            alert(msg);
            $("input#svn_out").attr("disabled", false);
        });
    }

    $("input#download").click(function(e) {
        $(this).attr("disabled", "disabled");
        var phpFile = "./php/download.php";
        $.get(phpFile, {"src_addr": $("input#src_addr").val()}, function(msg) {
            history.go(0);
            alert(msg);
            $(this).attr("disabled", false);
        });
    });
});
