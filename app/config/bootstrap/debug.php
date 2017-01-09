<?php
use lithium\data\Connections;
use app\extensions\helper\User;
use app\extensions\storage\Rcache;

if (PHP_SAPI === 'cli') {
    return;
}

$userHelper = new User;

function microTimeFloat()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
/*
if ($userHelper->isAdmin() || (isset($_GET['profile']) && $_GET['profile'] == 'true') || ($_SERVER['SERVER_ADDR'] == '127.0.0.1')) {
    $sessionHelper = new \lithium\storage\Session();
    if (preg_match('@^/test/@', $_SERVER['REQUEST_URI'])) {
        $connection = Connections::get('test');
    } else {
        $connection = Connections::get('default');
    }
    $connection->applyFilter('_execute', function ($self, $params, $chain) use ($sessionHelper) {
        if (!$currentQueryLog = $sessionHelper->read('debug.queries')) {
            $currentQueryLog = [];
            $sessionHelper->write('debug.queries', $currentQueryLog);
        }
        $beforeQueryTimeStamp = microTimeFloat();
        $result = $chain->next($self, $params, $chain);
        $afterQueryTimeStamp = microTimeFloat();
        $diff = $afterQueryTimeStamp - $beforeQueryTimeStamp;
        $currentQueryLog[] = [
            'type' => 'sql',
            'query' => $params['sql'],
            'timestamp' => $beforeQueryTimeStamp,
            'elapsed_time' => $diff
        ];
        $sessionHelper->write('debug.queries', $currentQueryLog);
        return $result;
    });

    $actions = ['read', 'write', 'delete', 'ttl', 'exists'];
    Rcache::applyFilter($actions, function ($self, $params, $chain) use ($sessionHelper) {
        if (!$currentQueryLog = $sessionHelper->read('debug.queries')) {
            $currentQueryLog = [];
            $sessionHelper->write('debug.queries', $currentQueryLog);
        }
        $beforeQueryTimeStamp = microTimeFloat();
        $result = $chain->next($self, $params, $chain);
        $afterQueryTimeStamp = microTimeFloat();
        $diff = $afterQueryTimeStamp - $beforeQueryTimeStamp;
        $currentQueryLog[] = [
            'type' => 'redis',
            'query' => $params['operation'] . ' - '. $params['key'],
            'timestamp' => $beforeQueryTimeStamp,
            'elapsed_time' => $diff
        ];
        $sessionHelper->write('debug.queries', $currentQueryLog);
        return $result;
    });

}
*/
