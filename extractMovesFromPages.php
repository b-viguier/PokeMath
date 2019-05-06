<?php

const PAGE_DIR = __DIR__.'/movePages/';

$pdo = new PDO('sqlite:db/database');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$updateStmt = $pdo->prepare(
    'UPDATE pokemon SET move1 = :move1, move2 = :move2, move3 = :move3 WHERE id = :id'
);

// Load translations
$dom = new DOMDocument();
$dom->loadHTMLFile(__DIR__.'/extract/movesEn-Fr.html', LIBXML_NOERROR);
$xpath = new DOMXPath($dom);
$translations = [];

for ($i = 1; $i < 675; ++$i) {
    $mvEN = trim(
        $xpath->evaluate(
            "/html/body/tbody/tr[$i]/td[2]/a/span/text()"
        )[0]->textContent ?? $xpath->evaluate(
            "/html/body/tbody/tr[$i]/td[2]/text()"
        )[0]->textContent ?? '');

    $mvFR = trim(
        $xpath->evaluate(
            "/html/body/tbody/tr[$i]/td[5]/text()"
        )[0]->textContent ?? '');

    $translations[$mvEN] = $mvFR;
}


foreach ($pdo->query("SELECT id, name FROM pokemon WHERE level = 1") as [$id, $name]) {
    $id = str_pad($id, 3, '0', STR_PAD_LEFT);

    // Special evolution cases…
    if (in_array($id, [267, 269])) {
        continue;
    }

    // Not enough attacks
    if (in_array($id, [132, 201, 235, 789])) {
        continue;
    }

    $path = PAGE_DIR."$id.html";

    echo "[$id] $path\n";
    echo "$name: ";

    $dom = new DOMDocument();
    $dom->loadHTMLFile($path, LIBXML_NOERROR);
    $xpath = new DOMXPath($dom);


    $selectedMoves = [];
    foreach (['tab-moves-17', 'tab-moves-16'] as $moveTab) {
        for ($moveId = 1; count($selectedMoves) < 3 && $moveId <= 10; ++$moveId) {
            $row = $moveId;
            foreach ($xpath->evaluate(
                "//div[@id='$moveTab']//a[contains(@class,'ent-name')]/text()") as $text) {
                $moveNameEN = trim($text->textContent);

                if ($moveNameEN == '') {
                    break;
                }
                if (!isset($translations[$moveNameEN])) {
                    echo "[$moveNameEN] Translation not found\n";
                    exit;
                }

                $selectedMoves[$moveNameEN] = $translations[$moveNameEN];
                if (count($selectedMoves) >= 3) {
                    break;
                }
            }
        }
    }
    echo implode(', ', $selectedMoves).PHP_EOL;
    if (count($selectedMoves) < 3) {
        echo implode(', ', array_keys($selectedMoves)).PHP_EOL;
        die("Not enough attacks…\n");
    }

    [$move1, $move2, $move3] = array_values($selectedMoves);

    $updateStmt->execute([
            'id' => $id,
            'move1' => $move1,
            'move2' => $move2,
            'move3' => $move3,
        ]);
}

