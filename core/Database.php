<?php

namespace MVC\Core;

use MVC\Core\Singleton;
use PDO;
use PDOException;
use Exception;

/**
 * Database connection using Singleton pattern.
 */
class Database extends Singleton
{
    private ?PDO $connection = null;

    /**
     * Initializes the database connection.
     *
     * @throws Exception If the connection fails.
     */
    protected function __construct()
    {
        $config = Config::getInstance();

        $host = $config->get('DB_HOST');
        $dbname = $config->get('DB_NAME');
        $user = $config->get('DB_USER');
        $password = $config->get('DB_PASS');
        $charset = $config->get('DB_CHARSET');

        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

        try {
            $this->connection = new PDO($dsn, $user, $password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT         => true,
            ]);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    /**
     * Returns the PDO connection instance.
     *
     * @return PDO
     */
    public static function getConnection(): PDO
    {
        return Database::getInstance()->connection;
    }
}
