<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once "conexao.php";
require_once "func/mostrarCardShow.php";

$titulo = $_POST["titulo"];
$descricao = $_POST["descricao"];
$data_show = $_POST["data_show"];
$locais = $_POST["locais"];
$preco = $_POST["preco"];
$capacidade = $_POST["capacidade"];
$ing_disponiveis = $_POST["ing_disponiveis"];
$imagem = $_POST["imagem"];


$categ_id = $_POST["categ_id"];

if ($categ_id === "nova") {
    $nome = trim($_POST["nova_categoria"]);
    $stmt = mysqli_prepare($conexao, "INSERT INTO categoria (nome_show) VALUES (?)");
    mysqli_stmt_bind_param($stmt, "s", $nome);
    mysqli_stmt_execute($stmt);
    $categ_id = mysqli_insert_id($conexao);
}


$sql = "INSERT INTO shows
        (categ_id, titulo, descricao, data_show, locals, preco, capacidade, ing_disponiveis, imagem)
        VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conexao, $sql);
//i i s s s s d i i s
mysqli_stmt_bind_param(
    $stmt,
    "issssdiis",
    $categ_id,
    $titulo,
    $descricao,
    $data_show,
    $locais,
    $preco,
    $capacidade,
    $ing_disponiveis,
    $imagem
);

mysqli_stmt_execute($stmt);

$id_novo = mysqli_insert_id($conexao);

$sql = "SELECT s.*, c.nome_show AS categoria
        FROM shows s
        LEFT JOIN categoria c ON c.id_categ = s.categ_id
        WHERE s.id_show = ?";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_novo);
mysqli_stmt_execute($stmt);
$show = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show cadastrado</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 antialiased min-h-screen flex flex-col">

    <header class="bg-[#C8DF9A] text-[#87C074] p-6">
        <h1 class="font-bold text-2xl text-center">Show adicionado com sucesso! ✅</h1>
    </header>

    <main class="flex-1 flex flex-col items-center gap-6 p-10">
        <p class="text-gray-600">Foi assim que ele ficou no sistema:</p>

        <div class="w-full max-w-sm">
            <?php mostrarCardShow($show, false); ?>
        </div>

        <div class="flex gap-4">
            <a href="../inserir-e-listar.php"
               class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition">Cadastrar outro show</a>
            <a href="../../area.html"
               class="bg-white border border-gray-300 text-gray-700 font-semibold py-2.5 px-4 rounded-lg hover:bg-gray-50 transition">Voltar ao admin</a>
        </div>
    </main>

    <footer class="p-6 bg-[#C8DF9A] text-[#87C074]">
        <p class="font-bold text-center">Talita de Souza Keller, Atividade LPII, 3I</p>
    </footer>
</body>
</html>