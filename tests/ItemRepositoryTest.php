<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use App\ItemRepository;

final class ItemRepositoryTest extends TestCase {
    public function testCreateAndList(): void {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE items (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT NOT NULL, created_at TEXT)');
        $repo = new ItemRepository($pdo);
        $id = $repo->create('test');
        $this->assertIsInt($id);
        $all = $repo->all();
        $this->assertCount(1, $all);
        $this->assertSame('test', $all[0]['title']);
    }
}