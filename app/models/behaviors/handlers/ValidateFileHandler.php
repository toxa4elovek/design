<?php

namespace app\models\behaviors\handlers;

class ValidateFileHandler extends \app\models\behaviors\handlers\StaticHandler
{

    public static function useHandler($behavior)
    {
        $behavior::applyFilter('afterSave', function ($self, $params, $chain) {
            if ((isset($params['uploadedFile']['data'])) && (!isset($params['uploadedFile']['data'][0]))) {
                if (!isset($params['uploadedFile']['attachInfo']['validateFile'])) {
                    $useroptions = [];
                } else {
                    $useroptions = $params['uploadedFile']['attachInfo']['validateFile'];
                }
                if (isset($self::$defaults['validateFile'])) {
                    $options = $useroptions + $self::$defaults['validateFile'];
                } else {
                    $options = $useroptions;
                }

                $fileinfo = pathinfo($params['uploadedFile']['data']['name']);

                if ((isset($options['extensionForbid'])) && (!empty($options['extensionForbid'])) && (in_array($fileinfo['extension'], $options['extensionForbid'])) && (is_array($options['extensionForbid'])) && (!empty($options['extensionForbid']))) {
                    unset($params['uploadedFile']['data']);
                }
            } else {
                foreach ($params['uploadedFile']['data'] as &$file) {
                    if (!isset($params['uploadedFile']['attachInfo']['validateFile'])) {
                        $useroptions = [];
                    } else {
                        $useroptions = $params['uploadedFile']['attachInfo']['validateFile'];
                    }
                    if (isset($self::$defaults['validateFile'])) {
                        $options = $useroptions + $self::$defaults['validate'];
                    } else {
                        $options = $useroptions;
                    }

                    $fileinfo = pathinfo($file['name']);

                    if ((isset($options['extensionForbid'])) && (!empty($options['extensionForbid'])) && (in_array($fileinfo['extension'], $options['extensionForbid'])) && (is_array($options['extensionForbid'])) && (!empty($options['extensionForbid']))) {
                        unset($file);
                    }
                }
            }
            return $chain->next($self, $params, $chain);
        });
    }
}
