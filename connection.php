<?php
try {
    // Database connection settings
    $db = new PDO("mysql:host=localhost;dbname=room-booking", "root", "");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
