<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2011, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * This is the primary bootstrap file of your application, and is loaded immediately after the front
 * controller (`webroot/index.php`) is invoked. It includes references to other feature-specific
 * bootstrap files that you can turn on and off to configure the services needed for your
 * application.
 *
 * Besides global configuration of external application resources, these files also include
 * configuration for various classes to interact with one another, usually through _filters_.
 * Filters are Lithium's system of creating interactions between classes without tight coupling. See
 * the `Filters` class for more information.
 *
 * If you have other services that must be configured globally for the entire application, create a
 * new bootstrap file and `require` it here.
 *
 * @see lithium\util\collection\Filters
 */

if (extension_loaded('newrelic')) {
    newrelic_set_appname('GoDesigner2.0');
}

/**
 * The libraries file contains the loading instructions for all plugins, frameworks and other class
 * libraries used in the application, including the Lithium core, and the application itself. These
 * instructions include library names, paths to files, and any applicable class-loading rules. This
 * file also statically loads common classes to improve bootstrap performance.
 */
require __DIR__ . '/bootstrap/libraries.php';

/**
 * The error configuration allows you to use the filter system along with the advanced matching
 * rules of the `ErrorHandler` class to provide a high level of control over managing exceptions in
 * your application, with no impact on framework or application code.
 */
require __DIR__ . '/bootstrap/errors.php';


/**
 * This file defines bindings between classes which are triggered during the request cycle, and
 * allow the framework to automatically configure its environmental settings. You can add your own
 * behavior and modify the dispatch cycle to suit your needs.
 */
require __DIR__ . '/bootstrap/action.php';


/**
 * This file contains configurations for connecting to external caching resources, as well as
 * default caching rules for various systems within your application
 */
require __DIR__ . '/bootstrap/cache.php';

/**
 * Include this file if your application uses one or more database connections.
 */
require __DIR__ . '/bootstrap/connections.php';

/**
 * This file contains configuration for session (and/or cookie) storage, and user or web service
 * authentication.
 */
 require __DIR__ . '/bootstrap/session.php';

/**
 * This file contains your application's globalization rules, including inflections,
 * transliterations, localized validation, and how localized text should be loaded. Uncomment this
 * line if you plan to globalize your site.
 */
//require __DIR__ . '/bootstrap/g11n.php';

/**
 * This file contains configurations for handling different content types within the framework,
 * including converting data to and from different formats, and handling static media assets.
 */
require __DIR__ . '/bootstrap/media.php';

/**
 * This file configures console filters and settings, specifically output behavior and coloring.
 */
if (PHP_SAPI === 'cli') {
    require __DIR__ . '/bootstrap/console.php';
}

/**
 * This file contains custom validation rules for models
 */
require __DIR__ . '/bootstrap/validation.php';

define('SECOND', 1);
define('MINUTE', 60);
define('HOUR', 3600);
define('DAY', 86400);
define('WEEK', 604800);
define('MONTH', 2592000);
define('YEAR', 31536000);

define('REFERAL_DISCOUNT', 300);
define('REFERAL_AWARD', 500);

define('FEE_LOW', 0.395);
define('FEE_NORMAL', 0.345);
define('FEE_GOOD', 0.305);

define('FEE_LOW_MICRO', 500);
define('FEE_NORMAL_MICRO', 1750);
define('FEE_GOOD_MICRO', 2500);

define('COPY_BASE_PRICE', 7000);

define('WINS_FOR_VIEW', 1); // Designer`s wins for allow private pitches view

define('MYSQL_DATETIME_FORMAT', 'Y-m-d H:i:s');

date_default_timezone_set('Europe/Minsk');

/*
use lithium\action\Dispatcher;
use lithium\analysis\Logger;

Logger::config(array(
    'default' => array('adapter' => 'FirePhp')
));*/

require __DIR__ . '/bootstrap/logs.php';
require __DIR__ . '/bootstrap/debug.php';
require __DIR__ . '/bootstrap/sms.php';

/*
Dispatcher::applyFilter('_call', function($self, $params, $chain) {
    var_dump($params);
    if (isset($params['callable']->response)) {
        Logger::adapter('default')->bind($params['callable']->response);
    }
    return $chain->next($self, $params, $chain);
});*/
