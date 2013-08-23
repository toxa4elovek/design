<?php

namespace app\extensions\helper;

use \lithium\template\View;
use \app\extensions\helper\MoneyFormatter;

class PdfGetter extends \lithium\template\Helper {

    public static function get($layout, $options) {
        $options['money'] = new MoneyFormatter();
        $view = new View(array(
		    'paths' => array(
		        'template' => '{:library}/views/pdfs/{:template}.{:type}.php',
		    )
		));
        return $view->render('template', $options, array('template' => $layout));
    }
}