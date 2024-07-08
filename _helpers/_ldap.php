<?php
session_start();

function ldap($conn, $gebruikersnaam, $wachtwoord) {
    // LDAP test
    $ldapconn = ldap_connect("145.118.4.6");
    if ($ldapconn == false) {
        @$_SESSION["inlogError"] = "error";
        return json_encode(['error' => 'LDAP connection testing error']);
    }

    if ($ldapconn) {
        // Binding to ldap server
        $inlognaam = $gebruikersnaam."@ict.lab.locals";
        $ldapbind = ldap_bind($ldapconn, $inlognaam, $wachtwoord);
        // Verify binding
        if (($ldapbind) && ($wachtwoord <>"")) {
            $inloggen = "ok";
        } else {
            $inloggen = "fout";
        }
    }

    if ($inloggen == "ok") {
        //$filter DOCENTEN
        $filter = "(samaccountname=$gebruikersnaam)";
        $ldaprdn = "ou=docenten,dc=ict,dc=lab,dc=locals"; // ldap rdn or dn
        $sr = ldap_search($ldapconn, $ldaprdn, $filter);
        $info = ldap_get_entries($ldapconn, $sr);
        if ($info["count"] == 1) {
            @$_SESSION["inlogError"] = "";
            @$_SESSION['login'] = true;
            @$_SESSION["ingelogdAls"] = "DOCENT";
            @$_SESSION["inlogDocent"] = $gebruikersnaam;
            @$_SESSION["mail"] = @$info[0]['mail'][0];
            return json_encode('message', 'Docent ingelogd');
        }
    } else {
        @$_SESSION["inlogError"] = "error";
        return json_encode('error', 'LDAP binding error');
    }
}