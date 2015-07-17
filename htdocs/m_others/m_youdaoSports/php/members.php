<table> 
<?php
    $lines = file("./data/members.txt");
    foreach ($lines as $line) {
        $line = trim($line);
        list($name, $mail) = explode(":", $line); 
        list($ldap, $other) = explode("@", $mail);
        echo "<tr><td><img src='http://weekly.corp.youdao.com/avatar/$ldap.jpg' /></td></tr>\n";
        echo "<tr><td>$name</td></tr>\n";
        echo "<tr><td>&nbsp;</td></tr>\n";
    }
?>
</table>
