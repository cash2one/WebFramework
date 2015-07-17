<?php
 $user = $_GET["username"];
 $ldappass = $_GET["password"];
 $ldaphost = "soda.rd.netease.com";  // your ldap server
 $ldapport = 389;                    // your ldap server's port number
 $ldapuser = "uid=".$user.",ou=people,dc=rd,dc=netease,dc=com";
 // Connecting to LDAP
 $ldapconn = ldap_connect($ldaphost, $ldapport);
 if($ldapconn) {
 // echo "[info]: Connect to LDAP server successful.<br>";
  if (ldap_set_option($ldapconn,LDAP_OPT_PROTOCOL_VERSION,3)){
   //echo "[info]: Using LDAP v3.<br>";
  }
  else{
   	echo "0";
   //echo "[info]: Failed to set version to protocol 3.<br>";
  }

  $ldapbind = ldap_bind($ldapconn,$ldapuser,$ldappass);
  if ($ldapbind){
   echo "1";
  # echo "[info]: LDAP bind successful.<br>";
  }
 }
 else{
   echo "0";
 }
?>

