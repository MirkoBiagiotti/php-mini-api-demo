<?php
declare(strict_types=1);
namespace App;

use PDO;

final class ItemRepository {
    public function __construct(private PDO $pdo) {}

    public function all(): array {
        $stmt = $this->pdo->query('SELECT id, title, created_at FROM items ORDER BY id DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function create(string $title): int {
        $stmt = $this->pdo->prepare('INSERT INTO items (title) VALUES (:title)');
        $stmt->execute([':title'=>$title]);
        return (int)$this->pdo->lastInsertId();
    }
}