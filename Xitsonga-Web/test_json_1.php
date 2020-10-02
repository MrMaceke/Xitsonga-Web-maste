<?php

error_reporting(E_ERROR);
ini_set('display_errors', 1);
set_time_limit(5000);

$booksJson = json_decode(file_get_contents("migration/books.json"));
$contributorsJson = json_decode(file_get_contents("migration/contributors.json"));
$islandsJson = json_decode(file_get_contents("migration/islands.json"));
$levelsJson = json_decode(file_get_contents("migration/levels.json"));
$pagesJson = json_decode(file_get_contents("migration/pages.json"));

$books = array();
$contributors = array();
$islands = array();
$levels = array();
$pages = array();

foreach ($booksJson->data as $key => $value) {
    $id = trim($value->key);
    
    $books[$id] = $value;
}

foreach ($contributorsJson->data as $key => $value) {
    $id = trim($value->key);
    
    $contributors[$id] = $value;
}

foreach ($islandsJson->data as $key => $value) {
    $id = trim($value->key);
    
    $islands[$id] = $value;
}

foreach ($levelsJson->data as $key => $value) {
    $id = trim($value->key);
    
    $levels[$id] = $value;
}

foreach ($levelsJson->data as $key => $value) {
    $id = trim($value->key);
    
    $levels[$id] = $value;
}

foreach ($pagesJson->data as $key => $value) {
    $id = trim($value->key);
    $bookKey = trim($value->bookKey);
    
    $pages[$bookKey][$id] = $value;
}

$firebase = array(
    "books"=> $books,
    "pages"=> $pages,
    "contributors"=> $contributors,
    "levels"=> $levels,
    "islands"=> $islands
);

echo json_encode($firebase); 