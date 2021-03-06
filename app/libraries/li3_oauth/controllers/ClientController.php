<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2009, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

namespace li3_oauth\controllers;

use \li3_oauth\models\Consumer;
use \lithium\storage\Session;

class ClientController extends \lithium\action\Controller
{

    public $publicActions = [
        'index',
        'authorize',
        'access',
        'login',
    ];
}
