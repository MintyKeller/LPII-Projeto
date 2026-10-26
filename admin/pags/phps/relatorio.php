<?php
require_once __DIR__ . '/../verifica_sessao.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/conexao.php';
require_once __DIR__ . '/../dompdf/autoload.inc.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

$titulo = trim($_GET['titulo'] ?? '');

// Consulta (com ou sem filtro)
$sql = "SELECT s.titulo, c.nome_show AS categoria, s.descricao, s.data_show,
               s.locals, s.preco, s.capacidade, s.ing_disponiveis
        FROM shows s
        LEFT JOIN categoria c ON c.id_categ = s.categ_id";

if ($titulo !== '') {
    $sql .= " WHERE s.titulo LIKE ?";
}
$sql .= " ORDER BY s.data_show ASC";

$stmt = mysqli_prepare($conexao, $sql);
if ($titulo !== '') {
    $like = "%" . $titulo . "%";
    mysqli_stmt_bind_param($stmt, "s", $like);
}
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

$html  = '<html><head><meta charset="utf-8">';
$html .= '<style>
    body { font-family: sans-serif; font-size: 12px; }
    h1 { font-size: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #ccc; padding: 6px; text-align: left; vertical-align: top; }
    th { background: #80ADFA; color: #fff; }
</style></head><body>';

$html .= '<h1>Relatório de Shows</h1>';
if ($titulo !== '') {
    $html .= '<p>Busca por: <strong>' . htmlspecialchars($titulo) . '</strong></p>';
}

if (mysqli_num_rows($resultado) === 0) {
    $html .= '<p>Nenhum show encontrado.</p>';
} else {
    $html .= '<table><thead><tr>
        <th>Título</th><th>Categoria</th><th>Descrição</th><th>Data</th>
        <th>Local</th><th>Preço</th><th>Capac.</th><th>Ingressos</th>
    </tr></thead><tbody>';

    while ($l = mysqli_fetch_assoc($resultado)) {
        $html.= '<tr>';
        $html.= '<td>' . htmlspecialchars($l['titulo']) . '</td>';
        $html.= '<td>' . htmlspecialchars($l['categoria'] ?? '-') . '</td>';
        $html.= '<td>' . htmlspecialchars($l['descricao']) . '</td>';
        $html.= '<td>' . date('d/m/Y', strtotime($l['data_show'])) . '</td>';
        $html.= '<td>' . htmlspecialchars($l['locals']) . '</td>';
        $html.= '<td>R$ ' . number_format($l['preco'], 2, ',', '.') . '</td>';
        $html.= '<td>' . (int)$l['capacidade'] . '</td>';
        $html.= '<td>' . (int)$l['ing_disponiveis'] . '</td>';
        $html.= '</tr>';
    }
    $html .= '</tbody></table>';
}
$html .= '</body></html>';

mysqli_stmt_close($stmt);
mysqli_close($conexao);

// Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape'); 
$dompdf->render();
$dompdf->stream("shows.pdf", ["Attachment" => false]);