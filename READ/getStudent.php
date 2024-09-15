<?php

function getStudent() {
    if (!isset($_SESSION['info'])) {
        return json_encode(['No student data found in session.']);
    } else {
        return json_encode($_SESSION['info']);
    }
}