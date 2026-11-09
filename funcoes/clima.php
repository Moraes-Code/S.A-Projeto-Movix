<?php
// Consulta a temperatura atual de Joinville na API Open-Meteo.

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
date_default_timezone_set('America/Sao_Paulo');

// Coordenadas de Joinville
$latitude = -26.3044;
$longitude = -48.8487;

// Endereço da API com os dados que queremos receber
$url = 'https://api.open-meteo.com/v1/forecast?latitude=' . $latitude .
       '&longitude=' . $longitude .
       '&current=temperature_2m,weather_code' .
       '&timezone=America%2FSao_Paulo';

// Faz a consulta usando cURL
$consulta = curl_init($url);
curl_setopt($consulta, CURLOPT_RETURNTRANSFER, true);
curl_setopt($consulta, CURLOPT_TIMEOUT, 10);

$resposta = curl_exec($consulta);
$codigo = curl_getinfo($consulta, CURLINFO_HTTP_CODE);

curl_close($consulta);

// Verifica se a API respondeu corretamente
if ($resposta === false || $codigo != 200) {
    http_response_code(502);
    echo json_encode([
        'ok' => false,
        'erro' => 'Não foi possível consultar o clima.'
    ]);
    exit;
}

// Converte a resposta JSON em um array PHP
$dados = json_decode($resposta, true);

// Verifica se os dados necessários existem
if (!isset($dados['current']['temperature_2m']) ||
    !isset($dados['current']['weather_code']) ||
    !isset($dados['current']['time'])) {

    http_response_code(502);
    echo json_encode([
        'ok' => false,
        'erro' => 'A resposta da API está incompleta.'
    ]);
    exit;
}

// Transforma o código do clima em uma descrição
$codigoClima = $dados['current']['weather_code'];

if ($codigoClima == 0) {
    $condicao = 'Céu limpo';
} elseif ($codigoClima == 1) {
    $condicao = 'Predominantemente limpo';
} elseif ($codigoClima == 2) {
    $condicao = 'Parcialmente nublado';
} elseif ($codigoClima == 3) {
    $condicao = 'Nublado';
} elseif ($codigoClima == 45 || $codigoClima == 48) {
    $condicao = 'Neblina';
} elseif ($codigoClima >= 51 && $codigoClima <= 57) {
    $condicao = 'Garoa';
} elseif ($codigoClima >= 61 && $codigoClima <= 67) {
    $condicao = 'Chuva';
} elseif ($codigoClima >= 80 && $codigoClima <= 82) {
    $condicao = 'Pancadas de chuva';
} elseif ($codigoClima >= 95 && $codigoClima <= 99) {
    $condicao = 'Tempestade';
} else {
    $condicao = 'Condição não identificada';
}

// Formata a data e o horário recebidos
$horario = date('d/m/Y H:i', strtotime($dados['current']['time']));

// Envia os dados para a página
echo json_encode([
    'ok' => true,
    'local' => 'Joinville, SC',
    'temperatura' => (float) $dados['current']['temperature_2m'],
    'condicao' => $condicao,
    'atualizado_em' => $horario
], JSON_UNESCAPED_UNICODE);
?>
