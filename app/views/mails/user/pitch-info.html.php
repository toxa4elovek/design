<?php
$category = \app\models\Category::first([
    'fields' => ['title'],
    'conditions' => [
        'id' => $pitch->category_id,
    ],
]);
echo mb_strtoupper($category->title . ', срок: ' . $pitch->startedHuman . ', гонорар: ' . (int) $pitch->price . ' Р.-', 'utf-8');
