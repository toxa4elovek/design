<?php

namespace app\extensions\helper;

use \lithium\template\View;
use \app\extensions\helper\MoneyFormatter;

class PdfGetter extends \lithium\template\Helper
{

    public static function get($layout, $options)
    {
        $options['money'] = new MoneyFormatter();
        $view = new View([
            'paths' => [
                'template' => '{:library}/views/pdfs/{:template}.{:type}.php',
            ]
        ]);
        return $view->render('template', $options, ['template' => $layout]);
    }

    public static function findPdfDestination($dest)
    {
        switch (strtolower($dest)) {
            case 'download':
                $destination = 'd';
                break;
            case 'file':
                $destination = 'f';
                break;
            case 'stdout':
                $destination = 'i';
                break;
            case 'string':
                $destination = 's';
                break;
            default:
                $destination = 'd';
        }
        return $destination;
    }
}
