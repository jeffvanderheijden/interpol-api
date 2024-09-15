<?php

function getStudent() {
    if (!isset($_SESSION['info'])) {
        return json_encode(['No student data found in session.']);
    } else {
        var_dump($_SESSION['info']);
        return json_encode($_SESSION['info']);
    }
}