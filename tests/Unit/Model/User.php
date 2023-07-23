<?php

namespace Tests\Unit\Model;

use Tests\Unit\Core\Logger;
use Tests\Unit\Database\MysqlDb;



class User
{
    private MysqlDb $db;

    public function __construct(MysqlDb $db)
    {
        $this->db = $db;
    }

    public function getDb()
    {
        return $this->db;
    }

    public function getUserData()
    {
        // Aqui você pode usar $this->db para buscar os dados do usuário.
        return 'User data from MySQL';
    }

    public function logUserData(Logger $logger)
    {
        $userData = $this->getUserData();
        $logger->log($userData);
    }
}
