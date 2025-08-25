<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
use App\ItemRepository;
use PDO;

final class ItemRepositoryEdgeCaseTest extends TestCase {
    public function testCreateWithSpecialCharactersAndLongTitle(): void {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE items (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT NOT NULL, created_at TEXT)');
        $repo = new ItemRepository($pdo);

        $longTitle = str_repeat('A', 1000);
        $id1 = $repo->create($longTitle);
        $this->assertIsInt($id1);

        $special = "Titolo con caratteri æøå ü ñ \" ' \\ \n";
        $id2 = $repo->create($special);
        $this->assertIsInt($id2);

        $all = $repo->all();
        $this->assertCount(2, $all);
        $this->assertSame($longTitle, $all[1]['title']); // inserted first, ordering DESC in all()
        $this->assertSame($special, $all[0]['title']);
    }

    public function testCreateRejectsEmptyTitleBehavior(): void {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec('CREATE TABLE items (id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT NOT NULL, created_at TEXT)');
        $repo = new ItemRepository($pdo);

        // The repository does not validate title; creating with empty string should still insert.
        $id = $repo->create('');
        $this->assertIsInt($id);
        $all = $repo->all();
        $this->assertCount(1, $all);
        $this->assertSame('', $all[0]['title']);
    }
}