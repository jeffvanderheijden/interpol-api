<?php

include '_helpers/ldap.php';

function createSession($conn, $params) {
    if (isset($params['username']) && isset($params['password'])) {
        return json_decode(($params));
       return ldap($params['username'], $params['password']);
    } else {
        return json_encode(['error' => 'ID parameter missing']); 
    }
}