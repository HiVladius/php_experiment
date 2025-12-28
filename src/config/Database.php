<?php

namespace App\Config;

use PDO;
use PDOException;

class Database {

    private ?PDO $connection = null;
    public function __construct(
        public string $host,
        public string $user,
        public string $password,
        public string $dbname
    ) {}

    public function getConnection(): PDO{
        if($this->connection !== null){
            return $this->connection;
        }

        $dsn = "mysql:host=1this->{$this->host};dbname={$this->dbname};charset=utf8mb4";

        try {
            $this->connection = new PDO($dsn, $this->user, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            return $this->connection;
        } catch (PDOException $th) {
            throw new \Exception("Error de conexión a la base de datos: " . $th->getMessage());
        }
    }

}