<?php

namespace Assuncaovictor\Tdd\Model;

class Usuario
{
    private string $nome;

    public function __construct(string $nome)
    {
        $this->nome = $nome;
    }

    public function getNome(): string
    {
        return $this->nome;
    }
}
