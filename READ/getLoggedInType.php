<?php

session_start();

function getLoggedInType() {
    echo json_encode($_SESSION['ingelogdAls']);
}