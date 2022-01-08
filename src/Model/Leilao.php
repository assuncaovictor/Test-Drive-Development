<?php

namespace Assuncaovictor\Tdd\Model;

class Leilao
{
    public array $lances;
    private string $descricao;
    public Status $statusDoLeilao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
        $this->statusDoLeilao = new StatusAberto($this);
    }

    public function recebeLance(Lance $lance): void
    {
        $this->statusDoLeilao->recebeLance($lance, $this);
    }

    public function finalizaLeilao(): void
    {
        $this->statusDoLeilao->finalizaLeilao();
    }

    public function getLances(): array
    {
        return $this->lances;
    }

    public function getStatusDoLeilao(): string
    {
        return get_class($this->statusDoLeilao);
    }
}
