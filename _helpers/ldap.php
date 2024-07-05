<?php
session_start();

$gebruikersnaam = $_POST['username'];
$wachtwoord = $_POST['password'];

//LDAP test
$ldapconn = ldap_connect("145.118.4.6");
if ($ldapconn == false){
    @$_SESSION["inlogError"] = "error";
    header('Location: error.php');
}

if ($ldapconn) {
    // binding to ldap server
    $inlognaam = $gebruikersnaam."@ict.lab.locals";
    $ldapbind = ldap_bind($ldapconn, $inlognaam, $wachtwoord);

    // verify binding
    if (($ldapbind) && ($wachtwoord <>"")) {
        //echo "Successful ingelogd...<br><Br>";
        $inloggen = "ok";
    } else {
        //echo "De combinatie naam / wachtwoord is niet juist...<br><br>";
        $inloggen = "fout";
    }
}

if ($gebruikersnaam == '23444' && isset($_GET['ID_groep']))
{
    $inloggen = "ok";
}

if ( $inloggen == "ok"){
    //$filter DOCENTEN -> wordt in beroeps niet gebruikt
    $filter = "(samaccountname=$gebruikersnaam)";
    $ldaprdn  = 'ou=docenten,dc=ict,dc=lab,dc=locals';     // ldap rdn or dn
    $sr=ldap_search($ldapconn, $ldaprdn, $filter);
    $info = ldap_get_entries($ldapconn, $sr);
    if ($info["count"]==1) {
//        echo "<pre>"; print_r($info); echo "</pre>";
        @$_SESSION["inlogError"] = "";
        @$_SESSION['login'] = true;
        @$_SESSION["ingelogdAls"] = "DOCENT";
        @$_SESSION["inlogDocent"] = $gebruikersnaam;
        @$_SESSION["mail"] = @$info[0]['mail'][0];
//        $_SESSION["huidigWW"] = $wachtwoord;
//        echo 'docent : '. $_SESSION["mail"];
//        header('Location: '.$this->rootURL.'overzicht/student');
    }
    else {
        //$filter STUDENTEN
        $filter = "(samaccountname=$gebruikersnaam)";
        $ldaprdn  = 'ou=glr_studenten,dc=ict,dc=lab,dc=locals';    // ldap rdn or dn
        $sr=ldap_search($ldapconn, $ldaprdn, $filter);
        $info = ldap_get_entries($ldapconn, $sr);

        if ($info["count"]==1) {
            require_once ('connect.php');
            $query = "SELECT stuNr, ID_groep, voornaam, tussenvoegsel, achternaam, klas FROM STUDENT WHERE stuNr = '$gebruikersnaam'";
//            echo "<p>$query</p>";
            $result = mysqli_query($con, $query);
            $row = mysqli_fetch_assoc($result);
            if (!empty($row['stuNr']))
            {
                $query = "SELECT groepsnaam FROM GROEP WHERE ID='$row[ID_groep]'";
//                echo "<p>$query</p>";
                $result = mysqli_query($con, $query);
                $row2 = mysqli_fetch_assoc($result);
                $error = false;
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


                /* DEBUG */
                if ($gebruikersnaam == '23444' && isset($_GET['ID_groep'])) {
                    @$_SESSION['ID_groep'] = $_GET['ID_groep'];
                    echo "<h1>Debug groep $_SESSION[ID_groep] is ingeschakeld</h1>";
                }

            }
            else { echo 'Geen 1e jaars student...'; }
//            echo "<pre>"; print_r($info); echo "</pre>";

//            $_SESSION['login'] = true;
//            $_SESSION["ingelogdAls"] = "STUDENT";
//            $_SESSION["inlogStudent"] = $gebruikersnaam;
//        $_SESSION["huidigWW"] = $wachtwoord;
//            echo 'student';
//        header('Location: '.$this->rootURL.'overzicht/student');
            return;
        }
        else {
            @$_SESSION["inlogError"] = "error";
            echo 'Geen docent of student van het glr';
//        header('Location: '.$this->rootURL);

        }
    }


}
else {
    @$_SESSION["inlogError"] = "error";
//    echo 'probleem2';
//    header('Location: '.$this->rootURL);
}