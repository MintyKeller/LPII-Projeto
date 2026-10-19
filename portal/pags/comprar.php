<?php
require_once __DIR__ . "/../../admin/pags/phps/conexao.php";

$id    = (int)($_POST["id_show"] ?? 0);
$qtd   = (int)($_POST["quantidade"] ?? 0);
$nome  = trim($_POST["comprador"] ?? "");
$email = trim($_POST["email"] ?? "");
$cpf   = trim($_POST["cpf"] ?? "");
$ok    = false;

mysqli_begin_transaction($conexao);
try {
    if ($id <= 0 || $qtd <= 0 || $nome === "" || $email === "" || $cpf === "") {
        throw new RuntimeException("Dados inválidos.");
    }

    $up = mysqli_prepare($conexao,
        "UPDATE shows SET ing_disponiveis = ing_disponiveis - ?
         WHERE id_show = ? AND ing_disponiveis >= ? AND data_show > CURDATE()");
    mysqli_stmt_bind_param($up, "iii", $qtd, $id, $qtd);
    mysqli_stmt_execute($up);
    if (mysqli_stmt_affected_rows($up) === 0) {
        throw new RuntimeException("Ingressos insuficientes ou show encerrado.");
    }

    $q = mysqli_prepare($conexao, "SELECT preco FROM shows WHERE id_show = ?");
    mysqli_stmt_bind_param($q, "i", $id);
    mysqli_stmt_execute($q);
    $preco = mysqli_fetch_assoc(mysqli_stmt_get_result($q))['preco'];
    $total = $preco * $qtd;

    $ins = mysqli_prepare($conexao,
        "INSERT INTO vendas (show_id, nome_comprador, email, cpf, qtd_ing, valor_total, data_compra)
         VALUES (?, ?, ?, ?, ?, ?, CURDATE())");
    mysqli_stmt_bind_param($ins, "isssid", $id, $nome, $email, $cpf, $qtd, $total);
    mysqli_stmt_execute($ins);

    mysqli_commit($conexao);
    $ok  = true;
    $msg = "Compra realizada com sucesso! Total: R$ " . number_format($total, 2, ',', '.');
} catch (RuntimeException $e) {
    mysqli_rollback($conexao);
    $msg = $e->getMessage();
} catch (Throwable $e) {
    mysqli_rollback($conexao);
    $msg = "Não foi possível concluir a compra. Tente novamente.";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compra</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="bg-white p-10 rounded-2xl shadow-sm border border-gray-100 text-center flex flex-col gap-4">
        <p class="text-xl font-bold <?= $ok ? 'text-[#87C074]' : 'text-red-500' ?>">
            <?= htmlspecialchars($msg) ?>
        </p>
        <a href="listar-e-buscar.php" class="text-blue-600 hover:underline font-semibold">Voltar aos shows</a>
    </div>
</body>
</html>