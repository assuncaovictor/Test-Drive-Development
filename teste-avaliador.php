<?php

use Assuncaovictor\Tdd\Model\Lance;
use Assuncaovictor\Tdd\Model\Leilao;
use Assuncaovictor\Tdd\Model\Usuario;
use Assuncaovictor\Tdd\Service\Avaliador;

require __DIR__ . '/vendor/autoload.php';

// Cofigurar o teste
// Arrange - Given
$leilao = new Leilao('Fiat uno');

$maria = new Usuario('Maria Clara');
$joao = new Usuario('Jão Mendes');

$leilao->recebeLance(new Lance($joao, 2000));
$leilao->recebeLance(new Lance($maria, 2500));

$leiloeiro = new Avaliador();

// Executo o código a ser testado
// Act - When
$leiloeiro->avalia($leilao);

$maiorValor = $leiloeiro->getMaiorValor();

// Verificar se a saida é a esperada
// Assert - Then
$valorEsperado = 2500;

if ($maiorValor == $valorEsperado) {
    echo "TESTE OK";
} else {
    echo "TESTE FALHOU";
}