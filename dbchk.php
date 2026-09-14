<?php
$pdo = new PDO('sqlite:C:/xampp/htdocs/sipibs/database/database.sqlite');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
echo "== borrowings ==\n";
foreach ($pdo->query("SELECT id, user_id, inventory_item_id, quantity, status, borrow_date, due_date FROM borrowings") as $r) {
    echo json_encode($r) . "\n";
}
echo "== users ==\n";
foreach ($pdo->query("SELECT id, name, identity_number, email FROM users") as $r) {
    echo json_encode($r) . "\n";
}
echo "== returns cols ==\n";
foreach ($pdo->query("PRAGMA table_info(returns)") as $r) {
    echo $r['name'] . " (" . $r['type'] . " notnull=" . $r['notnull'] . ") pkey=" . $r['pk'] . "\n";
}
