<?php

set_time_limit(5000000);

error_reporting(E_ALL);
ini_set('display_errors', 1);

function cleanSentence($string) {
    $string = str_ireplace("“", "", $string);
    $string = str_ireplace('"', "", $string);
    $string = str_ireplace("”", "", $string);
    return $string;
}

$phrases_file = file_get_contents("./tensor/bible_verse.tsv");
$phrases = explode("\n", $phrases_file);

$count = 0;
foreach ($phrases as $key => $phrasePair) {
    $xitsonga = trim(explode("\t", $phrasePair)[0]);
    $english = trim(explode("\t", $phrasePair)[1]);
    
    if($xitsonga == "" || $english == "") {
        continue;
    }

    file_put_contents('tensor/xitsonga_train.txt', trim($xitsonga) . "\n", FILE_APPEND);
    file_put_contents('tensor/english_train.txt', trim($english) . "\n", FILE_APPEND);  

    $count ++;
}