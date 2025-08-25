<?php
declare(strict_types=1);
namespace App;

use PDO;

final class DB {
    public static function conn(): PDO {
        $dir = __DIR__ . '/../data';
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        $dsn = 'sqlite:' . $dir . '/app.db';
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // init schema
        $pdo->exec('CREATE TABLE IF NOT EXISTS items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        )');
        return $pdo;
    }
}