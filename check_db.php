<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Default connection: " . config('database.default') . PHP_EOL;
try {
    echo "Connected database: " . DB::connection()->getDatabaseName() . PHP_EOL;
    $items = \App\Models\InventoryItem::all();
    echo "Item count: " . $items->count() . PHP_EOL;
    foreach ($items as $i) {
        echo $i->name . ': ' . $i->available_quantity . '/' . $i->total_quantity . ' (borrowed: ' . $i->borrowed_quantity . ')' . PHP_EOL;
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
