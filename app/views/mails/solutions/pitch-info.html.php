<?php
$category = \app\models\Category::first(array(
    'fields' => array('title'),
    'conditions' => array(
        'id' => $pitch->category_id,
    ),
));
echo mb_strtoupper($category->title . ', срок: ' . $pitch->startedHuman . ', гонорар: ' . (int) $pitch->price . ' Р.-', 'utf-8');
?>
