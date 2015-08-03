<?php
define("PASS", "1");
define("FAIL", "0");
define("LDAP_VER", 3);

$user     = stripslashes($_GET["username"]);
$ldappass = stripslashes($_GET["password"]);
$ldaphost = "mail.com"; // your ldap server
$ldapport = 389; // your ldap server's port number
$ldapuser = "uid=" . $user . ",ou=people,dc=rd,dc=netease,dc=com";

$ldapconn = ldap_connect($ldaphost, $ldapport);
if ($ldapconn) {
    if (ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, LDAP_VER)) {
        // do nothing
    } else {
   	    echo FAIL;
    }

    $ldapbind = ldap_bind($ldapconn, $ldapuser, $ldappass);
    if ($ldapbind) {
        echo PASS;
    }
} else {
    echo FAIL;
}
?>
