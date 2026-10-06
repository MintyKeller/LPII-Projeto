<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . "/../../admin/pags/phps/conexao.php";
require_once __DIR__ . "/../../admin/pags/phps/func/mostrarCardShow.php";

$sql = "SELECT s.*, c.nome_show AS categoria
        FROM shows s
        LEFT JOIN categoria c ON c.id_categ = s.categ_id
        ORDER BY s.data_show";
$resultado = mysqli_query($conexao, $sql);
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

                    <div>
                        <a href="#"
                            class="px-4 py-2 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">Forms</a>
                    </div>

                    <div>
                        <a href="#"
                            class="px-4 py-2 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">x</a>
                    </div>

                </nav>
            </div>

        </div>

        <div id="area" class="flex-1 flex flex-col h-full overflow-y-auto">

            <header class="bg-[#C8DF9A] text-[#87C074] p-6 flex flex-col gap-4 flex-shrink-0">
                <h1 class="font-bold text-2xl text-center">Detalhes dos Shows</h1>
                <nav class="flex justify-center gap-20">
                    <a href="../portal.html" class="hover:underline font-semibold">Home</a>
                    <a href="#" class="hover:underline font-semibold">Forms</a>
                </nav>
            </header>

            <main class="flex-1 flex flex-col gap-2">

                <div id="shows" class=" grid grid-cols-1 md:grid-cols-3 gap-6 mb-5 mt-5 m-10">
                    <?php if (mysqli_num_rows($resultado) === 0) { ?>
                    <p class="text-gray-500 col-span-full text-center">Nenhum show cadastrado ainda.</p>
                    <?php } ?>

                    <?php while ($show = mysqli_fetch_assoc($resultado)) {
                    mostrarCardShow($show);
                    } ?>
                </div>
                   
                

            </main>


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