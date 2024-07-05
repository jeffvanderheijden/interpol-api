<?php
function createSession($conn, $params) {
    if (isset($params['username']) && isset($params['password'])) {
       return json_encode(['message', 'received username and password']);
    } else {
        return json_encode(['error' => 'ID parameter missing']); 
    }
}