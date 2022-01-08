<?php

namespace Assuncaovictor\Tdd\Model;

use DomainException;

class StatusFinalizado extends Status
{
    public function recebeLance(Lance $lance): void
    {
        throw new DomainException('Não é possível realizar um lance em um leilao finalizado');
    }

    public function finalizaLeilao(): void
    {
        throw new DomainException('O leilão já foi finalizado');
    }
}