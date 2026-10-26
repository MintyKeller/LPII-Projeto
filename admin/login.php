<?php
session_start();
require_once __DIR__ . '/pags/phps/conexao.php';
$erro = '';
if (isset($_GET['erro']) && $_GET['erro'] === 'expirada') {
    $erro = 'Sua sessão expirou por inatividade. Faça login novamente.';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $sql  = "SELECT id_usuario, nome_usuario FROM usuarios WHERE email_usuario = ? AND senha = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $email, $senha);
    mysqli_stmt_execute($stmt);
    $usuario = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    if ($usuario) {
        session_regenerate_id(true);
        $_SESSION['usuario_id']    = $usuario['id_usuario'];
        $_SESSION['usuario_nome']  = $usuario['nome_usuario'];
        $_SESSION['ultimo_acesso'] = time(); // horário inicial de login
        header('Location: ./area.php');
        exit();
    }
    $erro = 'E-mail ou senha incorretos!';
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Área Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-50 antialiased min-h-screen flex flex-col">

    <header class="bg-[#C8DF9A] text-[#87C074] p-6">
        <h1 class="font-bold text-2xl text-center">Área Admin</h1>
    </header>

    <main class="flex-1 flex items-center justify-center p-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 w-full max-w-sm">

            <h2 class="text-2xl font-bold text-gray-800 mb-6">Entrar</h2>

            <?php if ($erro) { ?>
                <p class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded-lg p-3"><?= htmlspecialchars($erro) ?></p>
            <?php } ?>

            <form method="POST" action="login.php" class="flex flex-col gap-4">
                <div class="flex flex-col gap-1">
                    <label for="email" class="text-sm font-medium text-gray-700">E-mail</label>
                    <input type="email" id="email" name="email" required
                        class="border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex flex-col gap-1">
                    <label for="senha" class="text-sm font-medium text-gray-700">Senha</label>
                    <input type="password" id="senha" name="senha" required
                        class="border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <button type="submit"
                    class="mt-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-200">
                    Entrar
                </button>
            </form>
        </div>
    </main>

    <footer class="p-6 bg-[#C8DF9A] text-[#87C074]">
        <p class="font-bold text-center">Talita de Souza Keller, Atividade LPII, 3I</p>
    </footer>
</body>

</html>
