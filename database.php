<?php
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $servername = "localhost";
        $username = "u521641956_erpts_username"; // Your database username
        $password = "Erpts_password123"; // Your database password
        $dbname = "u521641956_erpts"; // Your database name
        
        // Create connection
        $this->connection = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->connection;
    }
}
?>
