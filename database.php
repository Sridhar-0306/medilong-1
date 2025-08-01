<?php
/**
 * Database Connection Class
 * Secure PDO connection to FreeSQLDatabase
 */

require_once 'config.php';

class Database {
    private $connection;
    private $host = DB_HOST;
    private $port = DB_PORT;
    private $dbname = DB_NAME;
    private $username = DB_USER;
    private $password = DB_PASS;
    
    /**
     * Create secure database connection
     */
    public function connect() {
        $this->connection = null;
        
        try {
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->dbname . ";charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
            ];
            
            $this->connection = new PDO($dsn, $this->username, $this->password, $options);
            
        } catch(PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            throw new Exception("Database connection failed. Please try again later.");
        }
        
        return $this->connection;
    }
    
    /**
     * Execute query with prepared statements
     */
    public function query($sql, $params = []) {
        try {
            if (!$this->connection) {
                $this->connect();
            }
            
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
            
        } catch(PDOException $e) {
            error_log("Database Query Error: " . $e->getMessage());
            error_log("SQL: " . $sql);
            throw new Exception("Database operation failed. Please try again.");
        }
    }
    
    /**
     * Get single record
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }
    
    /**
     * Get multiple records
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Get last inserted ID
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
    
    /**
     * Test database connection
     */
    public function testConnection() {
        try {
            $this->connect();
            $stmt = $this->query("SELECT 1 as test");
            $result = $stmt->fetch();
            return $result['test'] == 1;
        } catch(Exception $e) {
            return false;
        }
    }
}

?>