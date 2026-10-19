<?php

require_once __DIR__ . "/../../admin/pags/phps/conexao.php";
$categorias = mysqli_query($conexao, "SELECT id_categ, nome_show FROM categoria ORDER BY nome_show");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Público</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-50 antialiased">
    <div id="conteudo" class="flex w-screen h-screen overflow-hidden gap-2">

        <div id="side-bar"
            class="w-64 bg-[#80ADFA] p-6 flex flex-col justify-between flex-shrink-0 transition-all duration-300">
            <div>

                <button id="toggleSidebar"
                    class="absolute left-1 top-6 bg-white text-[#87C074] px-3 py-1 rounded-md shadow-sm font-bold hover:bg-gray-50 cursor-pointer transition mr-10 ml-1 ">
                    ☰
                </button>
                <div class="h-15 "></div>
                <nav class="flex flex-col gap-3">
                    <div>
                        <a href="../portal.html"
                            class="px-4 py-2 rounded-lg bg-white/20 text-white font-medium hover:bg-white/30 transition">Home</a>
                    </div>

                </nav>
            </div>

        </div>

        <div id="area" class="flex-1 flex flex-col h-full overflow-y-auto">

            <header class="bg-[#C8DF9A] text-[#87C074] p-6 flex flex-col gap-4 flex-shrink-0">
                <h1 class="font-bold text-2xl text-center">Listar e Buscar</h1>
                <nav class="flex justify-center gap-20">
                    <a href="../portal.html" class="hover:underline font-semibold">Home</a>
                </nav>
            </header>

                        <div id="listar" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 m-10">

                        <h1 class="text-2xl font-bold text-gray-800 mb-6">Insira o nome para procurar show, ou apenas clique no botão para listar todos:</h1>

                        <form action="phps/buscar.php" method="GET" class="flex flex-col gap-4">
                            <div class="flex flex-col gap-1">
                                <select name="categoria" class="border border-gray-300 rounded-lg p-2.5">
                                    <option value="0">Todas as categorias</option>
                                    <?php while ($c = mysqli_fetch_assoc($categorias)) { ?>
                                        <option value="<?= $c['id_categ'] ?>"><?= htmlspecialchars($c['nome_show']) ?></option>
                                    <?php } ?>
                                </select>
                                <label for="titulo_busca" class="text-sm font-medium text-gray-700">Título do show</label>
                                <input type="text" id="titulo_busca" name="titulo" placeholder="Nome do show"
                                    class="border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>

                            <button type="submit"
                                class="mt-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-200">
                                Buscar por Nome
                            </button>

                             <a href="phps/listar.php"
                            class="text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-200">
                            Listar Todos
                            </a>
                        </form>

                    </div>


            <footer class="p-6 bg-[#C8DF9A] text-[#87C074] flex-shrink-0 mt-auto">
                <p class="font-bold text-center">Talita de Souza Keller, Atividade LPII, 3I</p>
            </footer>
        </div>

    </div>


    <script>
        const sidebar = document.getElementById('side-bar');
        const toggleBtn = document.getElementById('toggleSidebar');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-ml-50');
        });
    </script>
</body>

</html>