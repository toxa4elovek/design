<?php
use lithium\analysis\Logger;

Logger::config(array(
    'simple' => array('adapter' => 'File'),
    'masterbank' => array(
        'adapter' => 'File',
        'priority' => array('info'),
        'file' => function($data, $config) { return "masterbank.log"; },
    ),
    'paymaster' => array(
        'adapter' => 'File',
        'priority' => array('info'),
        'file' => function($data, $config) { return "paymaster.log"; },
    ),
    'payanyway' => array(
        'adapter' => 'File',
        'priority' => array('info'),
        'file' => function($data, $config) { return "payanyway.log"; },
    ),
));
