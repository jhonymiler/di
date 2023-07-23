<?php

namespace Tests\Unit\Core;

class Cliente
{
    public function __construct(
        private Db $db
    ) {
    }

    public function getClienteData(): void
    {
        // Aqui você pode usar $this->db para buscar os dados do usuário.
        echo 'Jonatas Miler de Oliveira <br>';
    }
}
