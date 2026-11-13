<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

require_once __DIR__ . '/../infra/conexao.php';

$centro = ['lat' => -26.3044, 'lng' => -48.8487];

$areas = [
    'centro' => ['lat' => -26.3044, 'lng' => -48.8487],
    'norte' => ['lat' => -26.2795, 'lng' => -48.8582],
    'sul' => ['lat' => -26.3302, 'lng' => -48.8431],
    'leste' => ['lat' => -26.3048, 'lng' => -48.8076],
    'oeste' => ['lat' => -26.3099, 'lng' => -48.9157],
    'aeroporto' => ['lat' => -26.2256, 'lng' => -48.7957],
];

$palavrasChave = [
    'centro' => ['centro', 'central', 'estação central', 'estacao central', 'joinville'],
    'norte' => ['norte', 'km 10', 'km 15', 'km 20', 'km 25'],
    'sul' => ['sul', 'km 30', 'km 35', 'km 40'],
    'leste' => ['leste', 'km 45', 'km 50', 'trevo', 'terminal'],
    'oeste' => ['oeste', 'km 05', 'km 60', 'bairro'],
    'aeroporto' => ['aeroporto', 'aeroporto regional', 'pista'],
];

function localizarCoordenadas(string $textoLocalizacao, int $indice, array $areas, array $palavrasChave): array
{
    $texto = mb_strtolower($textoLocalizacao, 'UTF-8');

    foreach ($palavrasChave as $nomeArea => $palavras) {
        foreach ($palavras as $palavra) {
            if (mb_strpos($texto, $palavra, 0, 'UTF-8') !== false) {
                return $areas[$nomeArea];
            }
        }
    }

    $chaves = array_keys($areas);
    $chave = $chaves[$indice % count($chaves)];

    return $areas[$chave];
}

$sql = "SELECT id, codigo, nome, localizacao, status FROM sensores ORDER BY nome ASC";
$resultado = mysqli_query($conexao, $sql);

if ($resultado && mysqli_num_rows($resultado) > 0) {
    $sensores = [];
    $indice = 0;

    while ($sensor = mysqli_fetch_assoc($resultado)) {
        $coordenadas = localizarCoordenadas($sensor['localizacao'] ?? '', $indice, $areas, $palavrasChave);

        $sensores[] = [
            'id' => (int) $sensor['id'],
            'codigo' => $sensor['codigo'],
            'nome' => $sensor['nome'],
            'localizacao' => $sensor['localizacao'],
            'status' => $sensor['status'] ?? 'Online',
            'lat' => (float) $coordenadas['lat'],
            'lng' => (float) $coordenadas['lng'],
        ];

        $indice++;
    }
} else {
    $sensores = [
        ['id' => 1, 'codigo' => 'S-001', 'nome' => 'Sensor Centro', 'localizacao' => 'Centro', 'status' => 'Online', 'lat' => -26.3044, 'lng' => -48.8487],
        ['id' => 2, 'codigo' => 'S-002', 'nome' => 'Sensor Norte', 'localizacao' => 'KM 15', 'status' => 'Atenção', 'lat' => -26.2795, 'lng' => -48.8582],
        ['id' => 3, 'codigo' => 'S-003', 'nome' => 'Sensor Sul', 'localizacao' => 'KM 35', 'status' => 'Offline', 'lat' => -26.3302, 'lng' => -48.8431],
    ];
}

echo json_encode([
    'ok' => true,
    'centro' => $centro,
    'sensores' => $sensores,
], JSON_UNESCAPED_UNICODE);
