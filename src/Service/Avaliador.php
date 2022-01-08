<?php

namespace Assuncaovictor\Tdd\Service;

use Assuncaovictor\Tdd\Model\Lance;
use Assuncaovictor\Tdd\Model\Leilao;
use Assuncaovictor\Tdd\Model\StatusFinalizado;
use DomainException;

class Avaliador
{
    private float $maiorValor = -INF;
    private float $menorValor = INF;
    private array $maioresValores;
    
    public function avalia(Leilao $leilao): void
    {
        $lances = $leilao->getLances();
        if ($leilao->getStatusDoLeilao() === StatusFinalizado::class) {
            throw new DomainException('Não é possível avaliar um leilão finalizado');
        }

        if (empty($lances)) {
            throw new DomainException('Não é possível avaliar um leilão vazio');
        }

        foreach ($lances as $lance) {
            $valorLance = $lance->getValor();
            if ($valorLance > $this->maiorValor) {
                $this->maiorValor = $valorLance;
            } 
            
            if ($valorLance < $this->menorValor) {
                $this->menorValor = $valorLance;
            }

            usort($lances, function (Lance $lance1, Lance $lance2) {
                return $lance2->getValor() - $lance1->getValor();
            });

            $this->maioresValores = array_slice($lances, 0, 3);
        }
    }

    public function getMaiorValor(): float
    {
        return $this->maiorValor;
    }

    public function getMenorValor(): float
    {
        return $this->menorValor;
    }

    /** @return Lance[] */
    public function getMaioresValores(): array
    {
        return $this->maioresValores;
    }
}