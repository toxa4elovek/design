<?php

require 'SpellChecker.php';
require 'YandexSpell.php';

$transObject = new YandexSpell();

$data = '';
switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $data = $_GET;
        break;
    case 'POST':
        $data =& $_POST;
        break;
}
$array = explode(' ', trim($data['text']));
$result = $transObject->checkWords('ru', $array);
echo json_encode($result);