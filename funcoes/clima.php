<?php
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

date_default_timezone_set('America/Sao_Paulo');

$latitude = -26.3044;
$longitude = -48.8487;

$url = 'https://api.open-meteo.com/v1/forecast?latitude=' . $latitude .
    '&longitude=' . $longitude .
    '&current=temperature_2m,weather_code' .
    '&timezone=America%2FSao_Paulo';

$resposta = @file_get_contents($url);

if ($resposta === false) {
    http_response_code(502);
    echo json_encode(['ok' => false, 'erro' => 'Não foi possível consultar o clima.']);
    exit;
}

$dados = json_decode($resposta, true);

if (!isset($dados['current']['temperature_2m'], $dados['current']['weather_code'])) {
    http_response_code(502);
    echo json_encode(['ok' => false, 'erro' => 'Resposta inválida da API.']);
    exit;
}

$codigoClima = (int) $dados['current']['weather_code'];

if ($codigoClima == 0) {
    $condicao = 'Céu limpo';
} elseif ($codigoClima == 1) {
    $condicao = 'Parcialmente limpo';
} elseif ($codigoClima == 2) {
    $condicao = 'Nublado';
} elseif ($codigoClima == 3) {
    $condicao = 'Nublado';
} elseif ($codigoClima == 45 || $codigoClima == 48) {
    $condicao = 'Neblina';
} elseif ($codigoClima >= 51 && $codigoClima <= 57) {
    $condicao = 'Chuvisco';
} elseif ($codigoClima >= 61 && $codigoClima <= 67) {
    $condicao = 'Chuva';
} elseif ($codigoClima >= 80 && $codigoClima <= 82) {
    $condicao = 'Pancadas';
} elseif ($codigoClima >= 95 && $codigoClima <= 99) {
    $condicao = 'Tempestade';
} else {
    $condicao = 'Clima';
}

$horario = date('d/m/Y H:i');

echo json_encode([
    'ok' => true,
    'local' => 'Joinville, SC',
    'temperatura' => (float) $dados['current']['temperature_2m'],
    'condicao' => $condicao,
    'atualizado_em' => $horario
], JSON_UNESCAPED_UNICODE);
?>