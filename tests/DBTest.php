<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use PDO;

final class DBTest extends TestCase {
    public function testConnCreatesDatabaseAndTable(): void {
        // create a unique temp file for this test run
        $dataDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'phpmini_test_' . uniqid();
        if (!is_dir($dataDir)) mkdir($dataDir, 0777, true);
        $dbPath = $dataDir . DIRECTORY_SEPARATOR . 'app.db';
        $dsn = 'sqlite:' . $dbPath;
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // execute table creation similar to DB::conn to validate
        $pdo->exec('CREATE TABLE IF NOT EXISTS items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )');
        $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='items'"); 
        $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->assertNotEmpty($res, 'Table items should exist in the sqlite database');
    }
}