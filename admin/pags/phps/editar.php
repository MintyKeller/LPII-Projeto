<?php
require_once __DIR__ . '/../verifica_sessao.php';
require_once __DIR__ . "/conexao.php";
require_once __DIR__ . "/func/mostrarCardShow.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = (int) $_POST["id_show"];
    $categ = (int) $_POST["categ_id"];
    $titulo = trim($_POST["titulo"]);
    $descricao = trim($_POST["descricao"]);
    $data = $_POST["data_show"];
    $local = trim($_POST["locals"]);
    $preco = (float) $_POST["preco"];
    $capacidade = (int) $_POST["capacidade"];
    $ingressos = (int) $_POST["ing_disponiveis"];

    $sql = "UPDATE shows
            SET categ_id = ?, titulo = ?, descricao = ?, data_show = ?,
                locals = ?, preco = ?, capacidade = ?, ing_disponiveis = ?
            WHERE id_show = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "issssdiii",
        $categ, $titulo, $descricao, $data, $local, $preco, $capacidade, $ingressos, $id);
    mysqli_stmt_execute($stmt);

    $msg = mysqli_stmt_errno($stmt) === 0
        ? "Show atualizado com sucesso!"
        : "Não foi possível atualizar o show.";

    echo "<script>
        alert(" . json_encode($msg) . ");
        window.location.href = '../editar-e-excluir.html';
    </script>";
    exit;
}


$categorias = mysqli_query($conexao, "SELECT id_categ, nome_show FROM categoria ORDER BY nome_show");

$titulo = trim($_GET["titulo"] ?? "");
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
        <a href="../editar-e-excluir.php" class="text-blue-600 hover:underline font-semibold">Voltar</a>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php if (mysqli_num_rows($resultado) === 0) { ?>
                <p class="text-gray-500 col-span-full text-center">Nenhum show encontrado com esse nome."</p>
            <?php } ?>

            <?php while ($show = mysqli_fetch_assoc($resultado)) { ?>
            <div class="card-editar relative group cursor-pointer"
                data-show="<?= htmlspecialchars(json_encode($show), ENT_QUOTES) ?>">

                <?php mostrarCardShow($show, false); ?>

                <div class="absolute inset-0 rounded-3xl bg-gray-900/0 group-hover:bg-gray-900/15 transition duration-200 pointer-events-none"></div>
            </div>
        <?php } ?>
        </div>
    </main>



    <footer class="p-6 bg-[#C8DF9A] text-[#87C074]">
        <p class="font-bold text-center">Talita de Souza Keller, Atividade LPII, 3I</p>
    </footer>

    <div id="modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/30 backdrop-blur-sm overflow-y-auto p-4">
    <div class="bg-white rounded-2xl shadow-xl p-8 max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Editar show</h2>

        <form method="POST" action="editar.php" class="flex flex-col gap-3">
            <input type="hidden" name="id_show" id="f-id">

            <label class="text-sm font-medium text-gray-700">Título
                <input type="text" name="titulo" id="f-titulo" required
                    class="mt-1 w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </label>

            <label class="text-sm font-medium text-gray-700">Descrição
                <textarea name="descricao" id="f-descricao" rows="3"
                    class="mt-1 w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </label>

            <label class="text-sm font-medium text-gray-700">Categoria
                <select name="categ_id" id="f-categ"
                    class="mt-1 w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <?php while ($c = mysqli_fetch_assoc($categorias)) { ?>
                        <option value="<?= $c['id_categ'] ?>"><?= htmlspecialchars($c['nome_show']) ?></option>
                    <?php } ?>
                </select>
            </label>

            <div class="grid grid-cols-2 gap-3">
                <label class="text-sm font-medium text-gray-700">Data
                    <input type="date" name="data_show" id="f-data" required
                        class="mt-1 w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </label>
                <label class="text-sm font-medium text-gray-700">Local
                    <input type="text" name="locals" id="f-local" required
                        class="mt-1 w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </label>
            </div>

            <div class="grid grid-cols-3 gap-3">
                <label class="text-sm font-medium text-gray-700">Preço
                    <input type="number" step="0.01" min="0" name="preco" id="f-preco" required
                        class="mt-1 w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </label>
                <label class="text-sm font-medium text-gray-700">Capacidade
                    <input type="number" min="0" name="capacidade" id="f-capacidade" required
                        class="mt-1 w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </label>
                <label class="text-sm font-medium text-gray-700">Ingressos
                    <input type="number" min="0" name="ing_disponiveis" id="f-ingressos" required
                        class="mt-1 w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </label>
            </div>

            <div class="flex gap-3 justify-end mt-2">
                <button type="button" id="cancelar"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2.5 px-6 rounded-lg transition duration-200">
                    Cancelar
                </button>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-lg transition duration-200">
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('modal');

    function fecharModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.querySelectorAll('.card-editar').forEach(card => {
        card.addEventListener('click', () => {
            const show = JSON.parse(card.dataset.show);

            document.getElementById('f-id').value = show.id_show;
            document.getElementById('f-titulo').value = show.titulo;
            document.getElementById('f-descricao').value = show.descricao;
            document.getElementById('f-categ').value = show.categ_id;
            document.getElementById('f-data').value = show.data_show;
            document.getElementById('f-local').value = show.locals;
            document.getElementById('f-preco').value = show.preco;
            document.getElementById('f-capacidade').value = show.capacidade;
            document.getElementById('f-ingressos').value  = show.ing_disponiveis;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    document.getElementById('cancelar').addEventListener('click', fecharModal);
</script>


</body>
</html>