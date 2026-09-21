<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\InventoryItem;

foreach (InventoryItem::all() as $item) {
    echo $item->name . ' | ' . $item->photo . PHP_EOL;
}
