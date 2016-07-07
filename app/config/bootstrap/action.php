<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * This file contains a series of method filters that allow you to intercept different parts of
 * Lithium's dispatch cycle. The filters below are used for on-demand loading of routing
 * configuration, and automatically configuring the correct environment in which the application
 * runs.
 *
 * For more information on in the filters system, see `lithium\util\collection\Filters`.
 *
 * @see lithium\util\collection\Filters
 */

use lithium\core\Libraries;
use lithium\net\http\Router;
use lithium\core\Environment;
use lithium\action\Dispatcher;
use lithium\action\Response;
use lithium\security\Auth;
use \lithium\storage\Session;

/**
 * This filter intercepts the `run()` method of the `Dispatcher`, and first passes the `'request'`
 * parameter (an instance of the `Request` object) to the `Environment` class to detect which
 * environment the application is running in. Then, loads all application routes in all plugins,
 * loading the default application routes last.
 *
 * Change this code if plugin routes must be loaded in a specific order (i.e. not the same order as
 * the plugins are added in your bootstrap configuration), or if application routes must be loaded
 * first (in which case the default catch-all routes should be removed).
 *
 * If `Dispatcher::run()` is called multiple times in the course of a single request, change the
 * `include`s to `include_once`.
 *
 * @see lithium\action\Request
 * @see lithium\core\Environment
 * @see lithium\net\http\Router
 */
Dispatcher::applyFilter('run', function($self, $params, $chain) {
	Environment::set($params['request']);

	foreach (array_reverse(Libraries::get()) as $name => $config) {
		if ($name === 'lithium') {
			continue;
		}
		$file = "{$config['path']}/config/routes.php";
		file_exists($file) ? include $file : null;
	}

	return $chain->next($self, $params, $chain);
});


/**
* This filters checks user's session and denies access to non-public urls
*/
Dispatcher::applyFilter('_callable', function($self, $params, $chain) {
    // Mobile Detect
    $controller = strtolower($params['params']['controller']);
    if($controller == 'undefined') {
        header('Location: /news');
        die();
    }
    require_once(LITHIUM_APP_PATH . '/' . 'libraries' . '/' . 'Mobile-Detect/Mobile_Detect.php');
    $mobileDetect = new Mobile_Detect;
    $bypass = false;
    if((isset($params['request']->query)) && (isset($params['request']->query['mobile'])) && ($params['request']->query['mobile'] == 'true')) {
        setcookie('bypassmobile', 1, time() + (90 * DAY));
        $bypass = true;
    }
    if((isset($_COOKIE['bypassmobile'])) && ($_COOKIE['bypassmobile'] == 1)) {
        $bypass = true;
    }

    if (($mobileDetect->isMobile() && !$mobileDetect->isTablet()) && (!$bypass)) {
        $goMobile = 'https://m.godesigner.ru';
        $routes = array(
            'pages' => array(
                'home' => '',
            ),
            'pitches' => array(
                'index' => '',
                'view' => 'pitches/view', // id
                'details' => 'pitches/details', // id
                'viewsolution' => 'viewsolution' // id
            ),
            'requests' => array(
                'sign' => 'requests/sign', // id
            ),
            'users' => array(
                'login' => 'users/sign_in',
                'registration' => 'users/register',
                'view' => 'users/view', // id
            ),
        );
        $controller = strtolower($params['params']['controller']);
        $action = strtolower($params['params']['action']);
        if (isset($routes[$controller][$action])) {
            $goMobile .= '/' . $routes[$controller][$action];
            if (isset($params['params']['id'])) {
                $goMobile .= '/' . $params['params']['id'];
            }
            return function() use ($goMobile) { return new Response(array('location' => $goMobile)); };
        }
    }

    $ctrl = $chain->next($self, $params, $chain);
    if((Auth::check('user')) || (($params['params']['controller'] == 'lithium\test\Controller')) || (isset($ctrl->publicActions) && in_array($params['params']['action'], $ctrl->publicActions))) {
        if (extension_loaded ('newrelic')) {
            newrelic_name_transaction ($params['params']['controller'] . '/' . $params['params']['action']);
        }
        return $ctrl;
    }

    return function() use ($params) {
        $needWrite = true;
        if ($params['request']->type() == 'json') {
            $needWrite = false;
        }
        if (($params['params']['controller'] == 'Pitchfiles') && (($params['params']['action'] == 'index') || ($params['params']['action'] == 'download'))) {
            $needWrite = false;
        }
        if ($needWrite) {
            $path = '/'. $params['request']->url;
            if(Environment::is('development')) {
                $path = '/'. $params['request']->url;
            }
            Session::write('redirect', $path);
        }
        return new Response(compact('request') + array('location' => '/users/login'));
    };
});

?>