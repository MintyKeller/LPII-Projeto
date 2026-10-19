<?php
require_once __DIR__ . "/../../admin/pags/phps/conexao.php";

$id = (int)($_GET["id"] ?? 0);

$stmt = mysqli_prepare($conexao,
    "SELECT s.*, c.nome_show AS categoria
     FROM shows s
     LEFT JOIN categoria c ON c.id_categ = s.categ_id
     WHERE s.id_show = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$show = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$show) {
    http_response_code(404);
    exit("Show não encontrado.");
}

$ativo   = strtotime($show['data_show']) > strtotime('today');
$temFoto = file_exists(__DIR__ . '/../../admin/pags/fotos/' . $id . '.jpg');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($show['titulo']) ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 antialiased min-h-screen flex flex-col">

    <header class="bg-[#C8DF9A] text-[#87C074] p-6">
        <h1 class="font-bold text-2xl text-center"><?= htmlspecialchars($show['titulo']) ?></h1>
    </header>

    <main class="flex-1 p-10 max-w-3xl mx-auto w-full flex flex-col gap-4">
        <a href="listar-e-buscar.php" class="text-blue-600 hover:underline font-semibold">Voltar</a>

        <?php if ($temFoto) { ?>
            <img src="../../admin/pags/fotos/<?= $id ?>.jpg"
                 alt="<?= htmlspecialchars($show['titulo']) ?>"
                 class="w-full max-h-96 object-cover rounded-2xl">
        <?php } ?>

        <span class="text-xs font-semibold text-[#87C074] uppercase">
            <?= htmlspecialchars($show['categoria'] ?? 'Sem categoria') ?>
        </span>
        <p class="text-gray-600"><?= nl2br(htmlspecialchars($show['descricao'])) ?></p>

        <ul class="text-gray-700 flex flex-col gap-1">
            <li>📅 <?= date('d/m/Y', strtotime($show['data_show'])) ?></li>
            <li>📍 <?= htmlspecialchars($show['locals']) ?></li>
            <li>💰 R$ <?= number_format($show['preco'], 2, ',', '.') ?></li>
            <li>🎟️ <?= (int)$show['ing_disponiveis'] ?> ingressos disponíveis</li>
        </ul>

        <?php if ($ativo && $show['ing_disponiveis'] > 0) { ?>
            <form action="comprar.php" method="POST"
                  class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col gap-3">
                <input type="hidden" name="id_show" value="<?= $id ?>">

                <label class="text-sm font-medium text-gray-700">Seu nome</label>
                <input type="text" name="comprador" required
                       class="border border-gray-300 rounded-lg p-2.5">

                <label class="text-sm font-medium text-gray-700">Seu e-mail</label>
                <input type="email" name="email" required
                       class="border border-gray-300 rounded-lg p-2.5">

                <label class="text-sm font-medium text-gray-700">CPF</label>
                <input type="text" name="cpf" required maxlength="14" placeholder="000.000.000-00"
                class="border border-gray-300 rounded-lg p-2.5">

                <label class="text-sm font-medium text-gray-700">Quantidade de ingressos</label>
                <input type="number" name="quantidade" value="1" min="1"
                       max="<?= (int)$show['ing_disponiveis'] ?>" required
                       class="border border-gray-300 rounded-lg p-2.5">

                <button type="submit"
                        class="bg-[#ffe285] hover:bg-[#FA9E5E] text-white font-bold py-2.5 rounded-xl transition-colors">
                    Comprar Ingresso
                </button>
            </form>
        <?php } else { ?>
            <p class="text-red-500 font-semibold">Ingressos indisponíveis para este show.</p>
        <?php } ?>
    </main>

    <footer class="p-6 bg-[#C8DF9A] text-[#87C074]">
        <p class="font-bold text-center">Talita de Souza Keller, Atividade LPII, 3I</p>
    </footer>
</body>
</html>