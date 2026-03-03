<?php

namespace Core;

use PDO;

abstract class Repository
{
    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function beginTransaction(string $isolationLevel = 'READ COMMITTED'): void
    {
        $this->db->exec("SET TRANSACTION ISOLATION LEVEL {$isolationLevel}");
        $this->db->beginTransaction();
    }

    public function commit(): void
    {
        $this->db->commit();
    }

    public function rollBack(): void
    {
        $this->db->rollBack();
    }
}