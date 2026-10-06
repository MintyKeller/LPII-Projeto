<?php
require_once __DIR__ . "/conexao.php";
require_once __DIR__ . "/func/mostrarCardShow.php";

$sql = "SELECT s.*, c.nome_show AS categoria
        FROM shows s
        LEFT JOIN categoria c ON c.id_categ = s.categ_id
        ORDER BY s.data_show";
$resultado = mysqli_query($conexao, $sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de shows</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 antialiased min-h-screen flex flex-col">

    <header class="bg-[#C8DF9A] text-[#87C074] p-6">
        <h1 class="font-bold text-2xl text-center">Todos os shows</h1>
    </header>

    <main class="flex-1 p-10 flex flex-col gap-6">
        <a href="../inserir-e-listar.php" class="text-blue-600 hover:underline font-semibold">Voltar</a>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php if (mysqli_num_rows($resultado) === 0) { ?>
                <p class="text-gray-500 col-span-full text-center">Nenhum show cadastrado ainda.</p>
            <?php } ?>

            <?php while ($show = mysqli_fetch_assoc($resultado)) {
                mostrarCardShow($show, false);
            } ?>
        </div>
    </main>

    <footer class="p-6 bg-[#C8DF9A] text-[#87C074]">
        <p class="font-bold text-center">Talita de Souza Keller, Atividade LPII, 3I</p>
    </footer>
</body>
</html>