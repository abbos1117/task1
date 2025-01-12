<?php

namespace Tests;

class TestConfig {
    const DB_FILE = __DIR__ . '/test.db';
    const SCHEMA_FILE = __DIR__ . '/schema.sql';
    
    public static function getConnection() {
        try {
            $pdo = new \PDO('sqlite:' . self::DB_FILE);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            
            $schema = file_get_contents(self::SCHEMA_FILE);
            $pdo->exec($schema);
            
            return $pdo;
        } catch (\PDOException $e) {
            throw new \Exception('Database connection failed: ' . $e->getMessage());
        }
    }
    
    public static function cleanupDatabase() {
        if (file_exists(self::DB_FILE)) {
            unlink(self::DB_FILE);
        }
    }
}
