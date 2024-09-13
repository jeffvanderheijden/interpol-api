<?php

session_start();

function getLoggedInType() {
    if (!isset($_SESSION['ingelogdAls'])) {
        return json_encode(['Not logged in']);
    } else {
        return json_encode($_SESSION['ingelogdAls']);
    }
}