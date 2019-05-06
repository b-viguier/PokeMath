<?php

$pdo = new PDO('sqlite:db/database');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$updateStmt = $pdo->prepare(
    'UPDATE pokemon SET rank = :rank WHERE id = :id'
);

$rank = 1;
foreach ($pdo->query("SELECT id, name FROM pokemon WHERE level = 1") as [$id, $name]) {
    echo "[$id] $name : $rank\n";
    $updateStmt->execute([
        'id' => $id,
        'rank' => $rank++
    ]);
}
