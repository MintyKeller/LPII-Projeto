<?php require_once __DIR__ . '/../verifica_sessao.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-50 antialiased">
    <div id="conteudo" class="flex w-screen h-screen overflow-hidden gap-2">

        <div id="side-bar"
            class="w-64 bg-[#80ADFA] p-6 flex flex-col justify-between flex-shrink-0 transition-all duration-300">
            <div>

                <button id="toggleSidebar"
                    class="absolute left-1 top-6 bg-white text-[#87C074] px-3 py-1 rounded-md shadow-sm font-bold hover:bg-gray-50 cursor-pointer transition mr-10 ml-0">
                    ☰
                </button>
                <div class="h-15 "></div>
                <nav class="flex flex-col gap-3">

                    <div>
                        <a href="../area.html"
                            class="px-4 py-2 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">Home</a>
                    </div>

                    <div>
                        <a href="inserir-e-listar.php"
                            class="px-4 py-2 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">Inserir
                            e Listar </a>
                    </div>

                    <div>
                        <a href="editar-e-excluir.html"
                            class="px-4 py-2 rounded-lg text-white/80 hover:bg-white/10 hover:text-white transition">Edição
                            e Exclusão</a>
                    </div>

                </nav>
            </div>

        </div>

        <div id="area" class="flex-1 flex flex-col h-full overflow-y-auto">

            <header class="bg-[#C8DF9A] text-[#87C074] p-6 flex flex-col gap-4 flex-shrink-0">
                <h1 class="font-bold text-2xl text-center">Login e Logout</h1>
                <nav class="flex justify-center gap-20">
                    <a href="../area.html" class="hover:underline font-semibold">Home</a>
                </nav>
            </header>

            <main class="flex-1 flex flex-col gap-2">

                <div id="form" class="h-100 p-10">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 max-w-md">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Sessão ativa</h2>

                        <p class="text-gray-700">Olá, <strong>
                                <?= htmlspecialchars($_SESSION['usuario_nome']) ?>
                            </strong>!</p>
                        <p class="text-gray-700 mt-2">Última atividade registrada:
                            <strong>
                                <?= date('H:i:s', $_SESSION['ultimo_acesso']) ?>
                            </strong>
                        </p>
                        <p class="text-gray-500 text-sm mt-4">
                            A sessão expira após
                            <?= (int) $tempo_limite ?> segundos sem atividade.
                            Cada página que você abre renova o tempo.
                        </p>

                        <a href="../logout.php"
                            class="inline-block mt-6 bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-6 rounded-lg transition duration-200">
                            Sair
                        </a>
                    </div>
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

<!---INSERT INTO usuarios (nome, email, senha) VALUES ('admin', 'admin@email.com', '123'); -->