<?php

/** Add cancellation_reason column to orders. Run: php database/migrate_cancellation_reason.php */

$pdo = new PDO('mysql:host=127.0.0.1;dbname=Ayoub;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$columnExists = (int) $pdo->query("
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = 'Ayoub'
      AND TABLE_NAME = 'orders'
      AND COLUMN_NAME = 'cancellation_reason'
")->fetchColumn();

if ($columnExists === 0) {
    $pdo->exec('ALTER TABLE orders ADD COLUMN cancellation_reason TEXT NULL AFTER cancelled_at');
    echo "cancellation_reason column added.\n";
} else {
    echo "cancellation_reason column already exists.\n";
}
