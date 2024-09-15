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

// Session was already started..
// session_start();

function ldap($gebruikersnaam, $wachtwoord) {
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
            return json_encode(['message' => 'Docent ingelogd', 'session' => $_SESSION]);
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
                return json_encode(['message' => 'Student ingelogd', 'session' => $_SESSION]);
                // THIS CODE IS TO GET TEAMS, UNNEEDED FOR NOW
                // require('connection.php');
                // $query = "SELECT student_number, group_id, name FROM students WHERE student_number = '$gebruikersnaam'";
                // $result = mysqli_query($conn, $query);
                // $row = mysqli_fetch_assoc($result);
                // if (!empty($row['student_number'])) {
                //     $query = "SELECT name, image_url, class FROM groups WHERE id='$row[group_id]'";
                //     $result = mysqli_query($conn, $query);
                //     $row2 = mysqli_fetch_assoc($result);
                //     $_SESSION["inlogError"] = "";
                //     $_SESSION['login'] = true;
                //     $_SESSION['ingelogdAls'] = 'STUDENT';
                //     $_SESSION["inlogStudent"] = $gebruikersnaam; // dubbel
                //     $_SESSION['group_id'] = $row['group_id'];
                //     $_SESSION['groepsnaam'] = $row2['name'];
                //     $_SESSION['voornaam'] = $row['name'];
                //     $_SESSION['klas'] = $row2['class'];
                //     $_SESSION['image_url'] = $row2['image_url'];
                //     return json_encode(['message' => 'Student ingelogd', 'session' => $_SESSION]);
                // } else {
                //     return json_encode(['error', 'Geen 1e jaars student...']);
                // }
            } else {
                @$_SESSION["inlogError"] = "error";
                return json_encode(['error', 'Geen docent of student van het glr']);
            }
        }
    }

    $_SESSION["inlogError"] = "error";
    return json_encode(['error' => 'LDAP binding error']);
}