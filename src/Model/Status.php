<?php

namespace Assuncaovictor\Tdd\Model;

abstract class Status
{
    protected Leilao $leilao;

    public function __construct(Leilao $leilao)
    {
        $this->leilao = $leilao;
    }

    abstract public function recebeLance(Lance $lance): void;
    abstract public function finalizaLeilao(): void;
}