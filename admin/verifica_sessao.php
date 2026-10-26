<?php
$tempo_limite = 60; 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$script   = $_SERVER['SCRIPT_NAME'];
$pos      = strpos($script, '/admin/');
$urlAdmin = $pos !== false ? substr($script, 0, $pos + 7) : './';
if (!isset($_SESSION['usuario_id'])) {
    header("Location: {$urlAdmin}login.php");
    exit();
}
if (isset($_SESSION['ultimo_acesso']) && (time() - $_SESSION['ultimo_acesso']) > $tempo_limite) {
    session_unset();
    session_destroy();
    header("Location: {$urlAdmin}login.php?erro=expirada");
    exit();
}
$_SESSION['ultimo_acesso'] = time();
