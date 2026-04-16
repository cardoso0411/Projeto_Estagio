<?php

declare(strict_types=1);

require_once __DIR__ . '/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function redirect_to(string $path): never
{
    header('Location: ' . $path);
    exit;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function get_flash(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function current_user(): ?array
{
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool
{
    return current_user() !== null;
}

function is_company(): bool
{
    return current_user()['tipo'] ?? '' === 'empresa';
}

function is_student(): bool
{
    return current_user()['tipo'] ?? '' === 'aluno';
}

function require_login(): void
{
    if (!is_logged_in()) {
        set_flash('error', 'Faca login para acessar essa area.');
        redirect_to('../render/login.php');
    }
}

function require_company(): void
{
    require_login();

    if (!is_company()) {
        set_flash('error', 'Acesso restrito a empresas.');
        redirect_to('../render/login.php');
    }
}

function require_student(): void
{
    require_login();

    if (!is_student()) {
        set_flash('error', 'Acesso restrito a alunos.');
        redirect_to('../render/login.php');
    }
}

function login_user(array $user): void
{
    $_SESSION['user'] = [
        'id' => (int) $user['id'],
        'nome' => $user['nome'],
        'email' => $user['email'],
        'tipo' => $user['tipo'],
        'curso' => $user['curso'] ?? '',
        'cidade' => $user['cidade'] ?? '',
        'ra' => $user['ra'] ?? '',
    ];
}

function logout_user(): void
{
    unset($_SESSION['user']);
}
