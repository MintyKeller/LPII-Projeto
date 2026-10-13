<?php
require_once __DIR__ . "/conexao.php";
require_once __DIR__ . "/func/mostrarCardShow.php";


if (isset($_POST["id_excluir"])) {
    $id = (int) $_POST["id_excluir"];

    $stmt = mysqli_prepare($conexao, "DELETE FROM shows WHERE id_show = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

   $msg = mysqli_stmt_affected_rows($stmt) > 0
        ? "Show excluído com sucesso!"
        : "Não foi possível excluir o show.";

    echo "<script>
        alert(" . json_encode($msg) . ");
        window.location.href = '../editar-e-excluir.html';
    </script>";
    exit;
}

$titulo = trim($_POST["titulo"] ?? "");
$busca  = "%" . $titulo . "%";

$sql = "SELECT s.*, c.nome_show AS categoria
        FROM shows s
        LEFT JOIN categoria c ON c.id_categ = s.categ_id
        WHERE s.titulo LIKE ?
        ORDER BY s.data_show";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "s", $busca);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);


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
        <h1 class="font-bold text-2xl text-center">Resultados para "<?= htmlspecialchars($titulo) ?>"</h1>
    </header>

    <main class="flex-1 p-10 flex flex-col gap-6">
        <a href="../editar-e-excluir.html" class="text-blue-600 hover:underline font-semibold">Voltar</a>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php if (mysqli_num_rows($resultado) === 0) { ?>
                <p class="text-gray-500 col-span-full text-center">Nenhum show encontrado com esse nome."</p>
            <?php } ?>
            

            <?php while ($show = mysqli_fetch_assoc($resultado)) { ?>
                <div class="card-excluir relative group cursor-pointer"
                data-id="<?= $show['id_show'] ?>"
                data-titulo="<?= htmlspecialchars($show['titulo']) ?>">

            <?php mostrarCardShow($show, false); ?>
                    <div class="absolute inset-0 rounded-2xl bg-red-900/0 group-hover:bg-red-900/20 transition duration-200 pointer-events-none"></div>
                </div>
            <?php } ?>



        </div>
    </main>


    <div id="modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/30 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl p-8 max-w-md w-full mx-4 text-center">
            <h2 class="text-xl font-bold text-gray-800 mb-2">Você tem certeza que quer excluir esse show?</h2>
            <p id="modal-titulo" class="text-lg font-semibold text-red-600 mb-6"></p>

            <form method="POST" action="excluir.php" class="flex gap-4 justify-center">
                <input type="hidden" name="id_excluir" id="modal-id">

                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-6 rounded-lg transition duration-200">
                    Sim
                </button>

                <a href="../editar-e-excluir.html"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2.5 px-6 rounded-lg transition duration-200">
                    Não
                </a>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modal');

        document.querySelectorAll('.card-excluir').forEach(card => {
            card.addEventListener('click', () => {
                document.getElementById('modal-titulo').textContent = card.dataset.titulo;
                document.getElementById('modal-id').value = card.dataset.id;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });
    </script>


    <footer class="p-6 bg-[#C8DF9A] text-[#87C074]">
        <p class="font-bold text-center">Talita de Souza Keller, Atividade LPII, 3I</p>
    </footer>
</body>
</html>