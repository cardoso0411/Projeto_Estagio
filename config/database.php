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

function statement_select_all(mysqli_stmt $statement): array
{
    $metadata = $statement->result_metadata();

    if ($metadata === false) {
        return [];
    }

    $row = [];
    $fields = [];
    $bindValues = [];

    while ($field = $metadata->fetch_field()) {
        $fields[] = $field->name;
        $row[$field->name] = null;
        $bindValues[] = &$row[$field->name];
    }

    call_user_func_array([$statement, 'bind_result'], $bindValues);

    $results = [];

    while ($statement->fetch()) {
        $current = [];

        foreach ($fields as $fieldName) {
            $current[$fieldName] = $row[$fieldName];
        }

        $results[] = $current;
    }

    $statement->free_result();

    return $results;
}

function statement_select_one(mysqli_stmt $statement): ?array
{
    $rows = statement_select_all($statement);

    return $rows[0] ?? null;
}
