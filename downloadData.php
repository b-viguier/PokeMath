<?php

const ROOT_URL = 'https://www.pokemon.com';
const IMG_ROOT_URL = 'https://assets.pokemon.com/assets/cms2/img/pokedex/full/';
const PAGE_DIR = __DIR__ . '/pages/';
const IMG_DIR = __DIR__ . '/images/';

$pokemonList = file(__DIR__ . '/extract/list.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($pokemonList as $pokemonLine) {
    [$url, $id, $name] = explode(',', $pokemonLine);
    $id = str_pad("$id", 3, '0', STR_PAD_LEFT);
    $pageUrl = ROOT_URL.$url;
    $imageUrl = IMG_ROOT_URL . "$id.png";
    echo "[$id] $name ($pageUrl) ($imageUrl)\n";

    $dstPage = PAGE_DIR . $id . '.html';
    echo "$dstPage\t";
    if(file_exists($dstPage)) {
        echo "[EXISTS]\n";
    } else {
        file_put_contents($dstPage, file_get_contents($pageUrl));
        echo "[OK]\n";
    }

    $dstImg = IMG_DIR . $id . '.png';
    echo "$dstImg\t";
    if(file_exists($dstImg)) {
        echo "[EXISTS]\n";
    } else {
        file_put_contents($dstImg, file_get_contents($imageUrl));
        echo "[OK]\n";
    }

}