<?php

const PAGE_DIR = __DIR__.'/pages/';

$pdo = new PDO('sqlite:db/database');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $pdo->prepare(
    'INSERT OR REPLACE INTO pokemon (id, name, path, level) VALUES (:id, :name, :path, :level)'
);

$pokemonList = file(__DIR__.'/extract/list.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($pokemonList as $pokemonLine) {
    [, $id, $name] = explode(',', $pokemonLine);
    $id = str_pad("$id", 3, '0', STR_PAD_LEFT);

    // Special evolution cases…
    if (in_array($id, [267, 269])) {
        continue;
    }

    $path = PAGE_DIR."$id.html";

    echo "[$id] $path\n";

    $dom = new DOMDocument();
    $dom->loadHTMLFile($path, LIBXML_NOERROR);
    $xpath = new DOMXPath($dom);

    $path = '/';
    $found = false;
    for ($level = 0; $level < 5 && !$found; ++$level) {
        $listIndex = $level + 1;
        $otherId = ltrim(trim($xpath->evaluate("/html/body/div[4]/section[4]/div/ul/li[$listIndex]/a/h3/span/text()")[0]->textContent ?? ''), '#');
        if ($otherId === '') {
            // Case of multi branches
            for ($subIndex = 1; $subIndex < 10 && !$found; ++$subIndex) {
                $otherId = ltrim(trim($xpath->evaluate("/html/body/div[4]/section[4]/div/ul/li[$listIndex]/ul/li[$subIndex]/a/h3/span/text()")[0]->textContent), '#');
                if ($otherId == $id) {
                    $path .= "$otherId/";
                    $found = true;
                }
            }
            if (!$found) {
                throw new Exception("[$id] Not found in evolution branche");
            }

        } else {
            //Standard case
            $path .= "$otherId/";
            if ($otherId == $id) {
                $found = true;
            }
        }
    }


    $stmt->execute([
        'id' => $id,
        'name' => $name,
        'path' => $path,
        'level' => $level,
    ]);

}

