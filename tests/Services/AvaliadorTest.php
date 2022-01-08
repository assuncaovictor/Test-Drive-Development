<?php

namespace Assuncaovictor\Tests\Service;

use Assuncaovictor\Tdd\Model\Lance;
use Assuncaovictor\Tdd\Model\Leilao;
use Assuncaovictor\Tdd\Model\Usuario;
use Assuncaovictor\Tdd\Service\Avaliador;
use DomainException;
use PHPUnit\Framework\TestCase;

final class AvaliadorTest extends TestCase
{

    protected Avaliador $leiloeiro;

    public function setUp(): void
    {
        $this->leiloeiro = new Avaliador();
    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoAleatorio
     */
    public function testAvaliadorDeveEncontrarOMaiorOMenorValor(Leilao $leilao): void
    {
        // Executo o código a ser testado
        // Act - When
        $this->leiloeiro->avalia($leilao);
        $maiorValor = $this->leiloeiro->getMaiorValor();
        $menorValor = $this->leiloeiro->getMenorValor();

        // Verificar se a saida é a esperada
        // Assert - Then
        $maiorValorEsperado = 2900;
        $menorValorEsperado = 2000;

        self::assertEquals($maiorValor, $maiorValorEsperado);
        self::assertEquals($menorValor, $menorValorEsperado);
    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoAleatorio
     */
    public function testAvaliadorDeveEncontrarOs3MaioresValores(Leilao $leilao): void
    {
        $ednaldo = new Usuario('Ednaldo');
        $jose = new Usuario('José');

        $leilao->recebeLance(new Lance($ednaldo, 800));
        $leilao->recebeLance(new Lance($jose, 8700));

        // Executo o código a ser testado
        // Act - When
        $this->leiloeiro->avalia($leilao);
        $maioresValores = $this->leiloeiro->getMaioresValores();

        self::assertCount(3, $maioresValores);
        self::assertEquals([8700, 2900, 2500], array_map(function ($valor) {
            return $valor->getValor();
        }, $maioresValores));
    }

    public function testAvaliaLeilaoVazio()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar um leilão vazio');
        $this->leiloeiro = new Avaliador();
        $leilao = new Leilao('carro 0km');
        $this->leiloeiro->avalia($leilao);
    }

    /**
     * @dataProvider leilaoEmOrdemCrescente
     * @dataProvider leilaoEmOrdemDecrescente
     * @dataProvider leilaoAleatorio
     */
    public function testAvaliaLeilaoFinalizado(Leilao $leilao): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Não é possível avaliar um leilão finalizado');

        $leilao->finalizaLeilao();

        $leiloeiro = new Avaliador();
        $leiloeiro->avalia($leilao);
    }

    public function leilaoEmOrdemCrescente(): array
    {
        // Cofigurar o teste
        // Arrange - Given
        $leilao = new Leilao('Fiat uno');

        $maria = new Usuario('Maria Clara');
        $joao = new Usuario('Jão Mendes');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($ana, 2900));

        return [
            'Ordem crescente' => [$leilao],
        ];
    }

    public function leilaoEmOrdemDecrescente(): array
    {
        // Cofigurar o teste
        // Arrange - Given
        $leilao = new Leilao('Fiat uno');

        $maria = new Usuario('Maria Clara');
        $joao = new Usuario('Jão Mendes');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 2900));
        $leilao->recebeLance(new Lance($maria, 2500));
        $leilao->recebeLance(new Lance($joao, 2000));

        return [
            'Ordem decrescente' => [$leilao],
        ];
    }

    public function leilaoAleatorio(): array
    {
        // Cofigurar o teste
        // Arrange - Given
        $leilao = new Leilao('Fiat uno');

        $maria = new Usuario('Maria Clara');
        $joao = new Usuario('Jão Mendes');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 2900));
        $leilao->recebeLance(new Lance($joao, 2000));
        $leilao->recebeLance(new Lance($maria, 2500));

        return [
            'leilao aleatorio' => [$leilao],
        ];
    }
}
