<?php

namespace Core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct()
    {
        $host = getenv('DB_HOST') ?: 'db';
        $port = getenv('DB_PORT') ?: '5432';
        $db   = getenv('DB_NAME') ?: 'subtracker_db';
        $user = getenv('DB_USER') ?: 'postgres';
        $pass = getenv('DB_PASSWORD') ?: 'secretpassword';

        $dsn = "pgsql:host=$host;port=$port;dbname=$db";

        try {
            $this->connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}