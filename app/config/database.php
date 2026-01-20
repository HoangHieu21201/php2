<?php 
    return [
        'host' => $_ENV['HOST'] ?: '127.0.0.1',
        'database' => $_ENV['DB_DATABASE'] ?: 'mvc_php2',
        'username' => $_ENV['DB_USERNAME'] ?: 'root',
        'password' => $_ENV['DB_PASSWORD'] ?: ''
    ]
?>