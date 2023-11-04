<?php

class Database
{
    private static $instance = null;
    private $connection;
    private $host = "localhost";
    private $database = "trisakay2";
    private $username = "root";
    private $password = "";

    private function __construct()
    {
        $dsn = "mysql:host={$this->host};dbname={$this->database}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
            // Disable emulated prepared statements
        ];

        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function executeQuery($sql, $params = [])
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchSingleRow($stmt)
    {
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

$database = Database::getInstance();

// Usage example:
// $stmt = $database->executeQuery("SELECT * FROM your_table");
// $row = $database->fetchSingleRow($stmt);
?>
