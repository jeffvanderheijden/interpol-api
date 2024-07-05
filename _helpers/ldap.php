<?php
session_start();

function ldap($gebruikersnaam, $wachtwoord) {
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
            return json_encode('message', 'Inloggen gelukt');
            @$_SESSION["inlogError"] = "";
            @$_SESSION['login'] = true;
            @$_SESSION["ingelogdAls"] = "DOCENT";
            @$_SESSION["inlogDocent"] = $gebruikersnaam;
            @$_SESSION["mail"] = @$info[0]['mail'][0];
        } else {
            //$filter STUDENTEN
            $filter = "(samaccountname=$gebruikersnaam)";
            $ldaprdn = 'ou=glr_studenten,dc=ict,dc=lab,dc=locals'; // ldap rdn or dn
            $sr = ldap_search($ldapconn, $ldaprdn, $filter);
            $info = ldap_get_entries($ldapconn, $sr);
            if ($info["count"] == 1) {
                require_once ('connection.php');
                // TODO Rewrite query
                $query = "SELECT stuNr, ID_groep, voornaam, tussenvoegsel, achternaam, klas FROM STUDENT WHERE stuNr = '$gebruikersnaam'";
    //          echo "<p>$query</p>";
                $result = mysqli_query($con, $query);
                $row = mysqli_fetch_assoc($result);
                if (!empty($row['stuNr'])) {
                    // TODO Rewrite query
                    $query = "SELECT groepsnaam FROM GROEP WHERE ID='$row[ID_groep]'";
    //              echo "<p>$query</p>";
                    $result = mysqli_query($con, $query);
                    $row2 = mysqli_fetch_assoc($result);
                    @$_SESSION["inlogError"] = "";
                    @$_SESSION['login'] = true;
                    @$_SESSION['ingelogdAls'] = 'STUDENT';
                    @$_SESSION['stu'] = $row['stuNr'];
                    @$_SESSION["inlogStudent"] = $gebruikersnaam; // dubbel
                    @$_SESSION['ID_groep'] = $row['ID_groep'];
                    @$_SESSION['groepsnaam'] = $row2['groepsnaam'];
                    @$_SESSION['voornaam'] = $row['voornaam'];
                    @$_SESSION['tussenvoegsel'] = $row['tussenvoegsel'];
                    @$_SESSION['achternaam'] = $row['achternaam'];
                    @$_SESSION['klas'] = $row['klas'];
                }
                return json_encode('error', 'Geen 1e jaars student...');
            }
            else {
                @$_SESSION["inlogError"] = "error";
                return json_encode('error', 'Geen docent of student van het glr');
            }
        }
    } else {
        @$_SESSION["inlogError"] = "error";
        return json_encode('error', 'LDAP binding error');
    }
}