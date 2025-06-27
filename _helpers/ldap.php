<?php
header('Content-Type: application/json');

// Configure session cookie parameters
$cookieParams = session_get_cookie_params();
$cookieParams['domain'] = '.interpol.sd-lab.nl'; // Set cookie domain to include all subdomains
session_set_cookie_params(
    $cookieParams['lifetime'],
    $cookieParams['path'],
    $cookieParams['domain'],
    $cookieParams['secure'],
    $cookieParams['httponly']
);

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function ldap($gebruikersnaam, $wachtwoord) {
    // Test account for TEACHER
    if (
        $gebruikersnaam === "productiehuis1@glr.nl" && $wachtwoord === "S7GPmxT8K92K!" || 
        $gebruikersnaam === "productiehuis2@glr.nl" && $wachtwoord === "mI0zT626aCVg!" || 
        $gebruikersnaam === "docent123" && $wachtwoord === "docent123") {
        $_SESSION["inlogError"] = "";
        $_SESSION['login'] = true;
        $_SESSION["ingelogdAls"] = "DOCENT";
        $_SESSION["inlogDocent"] = 'productiehuis';
        $_SESSION["mail"] = 'productiehuis@glr.nl';
        return json_encode([
            'message' => 'Docent ingelogd',
            'session' => [
                'ingelogdAls' => 'DOCENT',
                'mail' => $_SESSION["mail"],
                'gebruikersnaam' => $gebruikersnaam
            ]
        ]);
    }

    if (
        $gebruikersnaam === "student1" && $wachtwoord === "student1" || 
        $gebruikersnaam === "student2" && $wachtwoord === "student2") {
        $_SESSION["inlogError"] = "";
        $_SESSION['login'] = true;
        $_SESSION["ingelogdAls"] = "STUDENT";
        $_SESSION["inlogStudent"] = 'teststudent';
        $_SESSION["mail"] = 'student@glr.nl';
        return json_encode([
            'message' => 'Student ingelogd',
            'session' => [
                'ingelogdAls' => 'STUDENT',
                'mail' => $_SESSION["mail"],
                'gebruikersnaam' => $gebruikersnaam
            ]
        ]);
    }

    // LDAP test
    $ldapconn = ldap_connect("145.118.4.6");
    if (!$ldapconn) {
        $_SESSION["inlogError"] = "error";
        return json_encode(['error' => 'LDAP connection testing error']);
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
            return json_encode([
                'message' => 'Docent ingelogd',
                'session' => [
                    'ingelogdAls' => 'DOCENT',
                    'mail' => $_SESSION["mail"],
                    'gebruikersnaam' => $gebruikersnaam
                ]
            ]);
        } else {
            //$filter STUDENTEN
            $filter = "(samaccountname=$gebruikersnaam)";
            $ldaprdn = 'ou=glr_studenten,dc=ict,dc=lab,dc=locals'; // ldap rdn or dn
            $sr = ldap_search($ldapconn, $ldaprdn, $filter);
            $info = ldap_get_entries($ldapconn, $sr);

            if ($info["count"] == 1) {
                $_SESSION["inlogError"] = "";
                $_SESSION['login'] = true;
                $_SESSION["ingelogdAls"] = "STUDENT";
                $_SESSION["inlogStudent"] = $gebruikersnaam;
                $_SESSION["mail"] = $info[0]['mail'][0] ?? '';
                $_SESSION["info"] = $info;
                return json_encode([
                    'message' => 'Student ingelogd',
                    'session' => [
                        'ingelogdAls' => 'STUDENT',
                        'mail' => $_SESSION["mail"],
                        'gebruikersnaam' => $gebruikersnaam
                    ]
                ]);
            } else {
                @$_SESSION["inlogError"] = "error";
                return json_encode(['error' => 'Geen docent of student van het glr']);
            }
        }
    }

    $_SESSION["inlogError"] = "error";
    return json_encode(['error' => 'LDAP binding error']);
}