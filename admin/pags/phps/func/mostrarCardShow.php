<?php
function mostrarCardShow(array $show, bool $comBotao = true)
{
    $titulo    = htmlspecialchars($show['titulo']);
    $categoria = htmlspecialchars($show['categoria'] ?? 'Sem categoria');
    $descricao = htmlspecialchars($show['descricao']);
    $local     = htmlspecialchars($show['locals']);
    $imagem    = htmlspecialchars($show['imagem']);
    $data      = date('d/m/Y', strtotime($show['data_show']));
    $preco     = number_format($show['preco'], 2, ',', '.');
    $ingressos = (int) $show['ing_disponiveis'];
?>
    <div class="show bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between hover:shadow-md transition-shadow">
        <div>
            <?php if ($imagem) { ?>
                <img src="<?= $imagem ?>" alt="<?= $titulo ?>" class="w-full h-40 object-cover rounded-xl mb-4">
            <?php } ?>

            <span class="text-xs font-semibold text-[#87C074] uppercase"><?= $categoria ?></span>
            <h1 class="text-xl font-bold text-gray-800 mb-2"><?= $titulo ?></h1>
            <p class="text-gray-500 text-sm mb-4"><?= $descricao ?></p>

            <ul class="text-gray-600 text-sm flex flex-col gap-1">
                <li>📅 <?= $data ?></li>
                <li>📍 <?= $local ?></li>
                <li>💰 R$ <?= $preco ?></li>
                <li>🎟️ <?= $ingressos ?> ingressos disponíveis</li>
            </ul>
        </div>

        <?php if ($comBotao) { ?>
            <button class="w-full bg-[#ffe285] hover:bg-[#FA9E5E] text-white font-medium py-2.5 px-4 rounded-xl transition-colors mt-4">
                <p class="font-bold">Comprar</p>
            </button>
        <?php } ?>
    </div>
<?php
}