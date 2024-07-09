<?php

session_start();

function getLoggedInType() {
    return json_encode($_SESSION['ingelogdAls']);
}