<?php

namespace Tests\Unit\Database;

use Tests\Unit\Core\Db;

class MysqlDb
{
    public function __construct(private Db $db)
    {
    }
    public function getDb()
    {
        return $this->db;
    }
}
