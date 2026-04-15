<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/auth.php';

logout_user();
set_flash('success', 'Sessao encerrada com sucesso.');
redirect_to('../render/home.php');
