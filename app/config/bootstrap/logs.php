<?php
use lithium\analysis\Logger;

Logger::config([
    'simple' => ['adapter' => 'File'],
    'masterbank' => [
        'adapter' => 'File',
        'priority' => ['info'],
        'file' => function ($data, $config) { return "masterbank.log"; },
    ],
    'paymaster' => [
        'adapter' => 'File',
        'priority' => ['info'],
        'file' => function ($data, $config) { return "paymaster.log"; },
    ],
    'payanyway' => [
        'adapter' => 'File',
        'priority' => ['info'],
        'file' => function ($data, $config) { return "payanyway.log"; },
    ],
    'payture' => [
        'adapter' => 'File',
        'priority' => ['info'],
        'file' => function ($data, $config) { return "payture.log"; },
    ],
    'vklog' => [
        'adapter' => 'File',
        'priority' => ['info'],
        'file' => function ($data, $config) { return "vklog.log"; },
    ],
    'solution' => [
        'adapter' => 'File',
        'priority' => ['info'],
        'file' => function ($data, $config) { return "solution_select.log"; },
    ],
    'deleted_solutions' => [
        'adapter' => 'File',
        'priority' => ['info'],
        'file' => function ($data, $config) { return "deleted_solutions.log"; },
    ]
]);
