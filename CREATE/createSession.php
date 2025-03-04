<?php

include '_helpers/ldap.php';

function createSession($params) {
    if (isset($params['username']) && isset($params['password'])) {
       return ldap($params['username'], $params['password']);
    } else {
        return json_encode(['error' => 'ID parameter missing']);
    }
}