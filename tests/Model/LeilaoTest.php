<?php

namespace Assuncaovictor\Tests\Model;

use Assuncaovictor\Tdd\Model\Lance;
use Assuncaovictor\Tdd\Model\Leilao;
use Assuncaovictor\Tdd\Model\Usuario;
use PHPUnit\Framework\TestCase;

final class LeilaoTest extends TestCase
{
    public function testLeilaoNaoDeveAceitarMaisDe5Lances()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Um usuário não pode fazer mais de 5 lances');
        $leilao = new Leilao('Drone');

        $ana = new Usuario('Ana');
        $jonas = new Usuario('Jonas');

        for ($i=1; $i<=5; $i++) {
            $leilao->recebeLance(new Lance($ana, 500 * $i));
            $leilao->recebeLance(new Lance($jonas, 600 * $i));
        }

        $leilao->recebeLance(new Lance($ana, 10000));
    }

    public function testLeilaoNaoPodeTerSequenciaPeloMesmoUsuario()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Um usuário não pode fazer dois lances consecutivos');
        $leilao = new Leilao('Iphone 7');
        $ana = new Usuario('Ana');

        $leilao->recebeLance(new Lance($ana, 1700));
        $leilao->recebeLance(new Lance($ana, 1750));
    }

    public function testLeilaoNaoPodeSerFinalizadoDuasVezes()
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('O leilão já foi finalizado');

        $leilao = new Leilao('Fiat');
        $leilao->finalizaLeilao();
        $leilao->finalizaLeilao();
    }
    /**
     * @dataProvider leiloes
     * 
     * Testa os valores retornados pelo Leilao
     */
    public function testLancesFeitosEmUmLeilao(int $qtdLances, Leilao $leilao, array $valoresEsperados): void
    {
        self::assertCount($qtdLances, $leilao->getLances());
        array_map(function ($valorEsperado, $valorLeilao) {
            self::assertEquals($valorEsperado, $valorLeilao->getValor());
        }, $valoresEsperados, $leilao->getLances());
    }

    public function leiloes(): array
    {
        $leilaoComUmLance = new Leilao('GTX 1080ti');
        $leilaoComDoisLances = new Leilao('TV LED 72 polegadas');

        $victor = new Usuario('Victor');
        $lucas = new Usuario('Lucas');
        
        $leilaoComUmLance->recebeLance(new Lance($victor, 2000));

        $leilaoComDoisLances->recebeLance(new Lance($lucas, 2700));
        $leilaoComDoisLances->recebeLance(new Lance($victor, 5823));

         return [
             '1 lance' => [1, $leilaoComUmLance, [2000]],
             '2 lances' => [2, $leilaoComDoisLances, [2700, 5823]]
         ];
    }
}