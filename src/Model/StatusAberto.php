<?php

namespace Assuncaovictor\Tdd\Model;

use DomainException;

class StatusAberto extends Status
{
    public function recebeLance(Lance $lance): void
    {
        $usuario = $lance->getUsuario();

        if (!empty($this->leilao->lances) && 
        !$this->usuarioDiferente($lance)) {
            throw new DomainException('Um usuário não pode fazer dois lances consecutivos');
        }

        if ($this->quantidadeLances($usuario) === 5){
            throw new DomainException('Um usuário não pode fazer mais de 5 lances');
        }

        $this->leilao->lances[] = $lance;
    }
    
    public function finalizaLeilao(): void
    {
        $this->leilao->statusDoLeilao = new StatusFinalizado($this->leilao);
    }

    private function usuarioDiferente(Lance $lance): bool
    {
        $ultimoUsuario = $this->leilao->lances[array_key_last($this->leilao->lances)]->getUsuario();
        return $ultimoUsuario !== $lance->getUsuario();
    }

    private function quantidadeLances(Usuario $usuario): int  
    {
        return array_reduce($this->leilao->lances, function(int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
            if ($lanceAtual->getUsuario() === $usuario) {
                return $totalAcumulado + 1;
            }
            return $totalAcumulado;
        }, 0); 
    }
}