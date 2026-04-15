<?php

declare(strict_types=1);

function database(): mysqli
{
    static $connection = null;

    if ($connection instanceof mysqli) {
        return $connection;
    }

    $host = getenv('DB_HOST') ?: '127.0.0.1';
    $user = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '8972013';
    $database = getenv('DB_NAME') ?: 'estagio';
    $port = (int) (getenv('DB_PORT') ?: 8000);

    $connection = new mysqli($host, $user, $password, $database, $port);

    if ($connection->connect_error) {
        throw new RuntimeException('Nao foi possivel conectar ao banco de dados.');
    }

    $connection->set_charset('utf8mb4');

    return $connection;
}
