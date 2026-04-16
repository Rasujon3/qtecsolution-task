<?php
try {
    $pdo = new PDO('sqlite::memory:');
    echo "SQLite Connection Successful!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
