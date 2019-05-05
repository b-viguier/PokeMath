<?php

const ROOT_URL = 'https://pokemondb.net';
const PAGE_DIR = __DIR__.'/movePages/';

$matchingList = file(__DIR__.'/extract/matching.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$matching = array_map(function ($line) {
    return explode(',', $line);
}, $matchingList);
$matching = array_column($matching, 1, 0);

$pokemonList = file(__DIR__.'/extract/list.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($pokemonList as $pokemonLine) {
    [$url, $id, $name] = explode(',', $pokemonLine);
    $id = str_pad("$id", 3, '0', STR_PAD_LEFT);

    if (!isset($matching[$id])) {
        echo "[$id] not found\n";
        continue;
    }

    $pageUrl = ROOT_URL.$matching[$id];
    $dstPage = PAGE_DIR.$id.'.html';
    echo "$dstPage\t";
    if (file_exists($dstPage)) {
        echo "[EXISTS]\n";
    } else {
        file_put_contents($dstPage, file_get_contents($pageUrl));
        echo "[OK]\n";
    }
}