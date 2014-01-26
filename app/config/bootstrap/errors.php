<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

use \lithium\core\ErrorHandler;
use \lithium\analysis\Logger;
use lithium\action\Response;
use lithium\net\http\Media;
use \lithium\template\View;
use \lithium\core\Environment;

/**
 * Then, set up a basic logging configuration that will write to a file.
 */
Logger::config(array('error' => array('adapter' => 'File')));

/**
 * Configure an error page renderer function that we can use to render 404 and 500 error pages (for
 * this part to work, you need to create errors/404.html.php and errors/500.html.php in your views/
 * directory).
 */
$render = function($template, $content) {
    $view = new View(array(
        'paths' => array(
            'template' => '{:library}/views/{:controller}/{:template}.{:type}.php',
            'layout'   => '{:library}/views/layouts/{:layout}.{:type}.php',
        )
    ));
    echo $view->render('all', compact('content'), compact('template') + array(
        'controller' => 'errors',
        'layout' => 'default',
        'type' => 'html'
    ));
};

ErrorHandler::config(array(
    array(
        'type' => 'Exception',
        'message' => "/(^Template not found|^Controller '\w+' not found|^Action '\w+' not found)/",
        'handler' => function($info) use ($render) {
            $render('404', $info);
        }
    ),
    array(
        'type' => 'Exception',
        'handler' => function($info) use ($render) {
            Logger::write('error', "{$info['file']} : {$info['line']} : {$info['message']}");
            $render('500', $info);
        }
    )
));

ErrorHandler::apply('lithium\action\Dispatcher::run', array(), function($info, $params) {
	$response = new Response(array(
		'request' => $params['request'],
		'status' => $info['exception']->getCode()
	));
    if (Environment::is('production')) {
        Media::render($response, compact('info', 'params'), array(
            'library' => true,
            'controller' => '_errors',
            'template' => '404',
            'layout' => 'default',
            'request' => $params['request']
        ));
    }else {
        Media::render($response, compact('info', 'params'), array(
            'library' => true,
            'controller' => '_errors',
            'template' => 'development',
            'layout' => 'default',
            'request' => $params['request']
        ));
    }
	return $response;
});

//ErrorHandler::run();

?>