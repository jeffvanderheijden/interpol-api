<?php
header('Content-Type: application/json');
session_start();

function ldap($conn, $gebruikersnaam, $wachtwoord) {
    // LDAP test
    $ldapconn = ldap_connect("145.118.4.6");
    if (!$ldapconn) {
        $_SESSION["inlogError"] = "error";
        echo json_encode(['error' => 'LDAP connection testing error']);
        return;
    }

    // Initialize inloggen variable
    $inloggen = "fout";

    if ($ldapconn) {
        // Binding to ldap server
        $inlognaam = $gebruikersnaam . "@ict.lab.locals";
        $ldapbind = ldap_bind($ldapconn, $inlognaam, $wachtwoord);

        // Verify binding
        if ($ldapbind && $wachtwoord !== "") {
            $inloggen = "ok";
        }
    }

    if ($inloggen == "ok") {
        // Filter DOCENTEN
        $filter = "(samaccountname=$gebruikersnaam)";
        $ldaprdn = "ou=docenten,dc=ict,dc=lab,dc=locals"; // ldap rdn or dn
        $sr = ldap_search($ldapconn, $ldaprdn, $filter);
        $info = ldap_get_entries($ldapconn, $sr);

        if ($info["count"] == 1) {
            $_SESSION["inlogError"] = "";
            $_SESSION['login'] = true;
            $_SESSION["ingelogdAls"] = "DOCENT";
            $_SESSION["inlogDocent"] = $gebruikersnaam;
            $_SESSION["mail"] = $info[0]['mail'][0] ?? '';
            echo json_encode(['message' => 'Docent ingelogd']);
            return;
        }
    }

    $_SESSION["inlogError"] = "error";
    echo json_encode(['error' => 'LDAP binding error']);
}