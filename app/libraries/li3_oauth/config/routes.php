<?php

use \lithium\net\http\Router;

/*Router::connect('/oauth', array(
    'library' => 'li3_oauth', 'controller' => 'li3_oauth.server', 'action' => 'account'
        ));*/
/*Router::connect('/twitter/{:action}/{:args}', array(
    'library' => 'li3_oauth', 'controller' => 'li3_oauth.tweet', 'action' => 'index'
));
 */
Router::connect('/vkontakte/{:action}/{:args}', [
    'library' => 'li3_oauth', 'controller' => 'li3_oauth.vkontakte', 'action' => 'index'
]);
Router::connect('/facebook/{:action}/{:args}', [
    'library' => 'li3_oauth', 'controller' => 'li3_oauth.facebook', 'action' => 'index'
]);
/*
Router::connect('/mailru/{:action}/{:args}', array(
    'library' => 'li3_oauth', 'controller' => 'li3_oauth.mailru', 'action' => 'index'
));
/*Router::connect('/oauth/{:action}/{:args}', array(
    'library' => 'li3_oauth', 'controller' => 'li3_oauth.server', 'action' => 'index'
));*/;
