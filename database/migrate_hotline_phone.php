<?php

/** Add hotline_phone column to general_settings. Run: php database/migrate_hotline_phone.php */

$pdo = new PDO('mysql:host=127.0.0.1;dbname=Ayoub;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
]);

$columnExists = (int) $pdo->query("
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = 'Ayoub'
      AND TABLE_NAME = 'general_settings'
      AND COLUMN_NAME = 'hotline_phone'
")->fetchColumn();

if ($columnExists === 0) {
    $pdo->exec("ALTER TABLE general_settings ADD COLUMN hotline_phone VARCHAR(20) NULL AFTER app_description");
    echo "hotline_phone column added.\n";
} else {
    echo "hotline_phone column already exists.\n";
}
