<?php

namespace app\models\behaviors\handlers;

class ValidateHandler extends \app\models\behaviors\handlers\StaticHandler
{
    
    public static function useHandler($behavior)
    {
        $behavior::applyFilter('afterSave', function ($self, $params, $chain) {
            if ((isset($params['uploadedFile']['data'])) && (!isset($params['uploadedFile']['data'][0]))) {
                if (!isset($params['uploadedFile']['attachInfo']['validate'])) {
                    $useroptions = [];
                } else {
                    $useroptions = $params['uploadedFile']['attachInfo']['validate'];
                }
                $options = $useroptions + $self::$defaults['validate'];
                
                $fileinfo = pathinfo($params['uploadedFile']['data']['name']);

                if ((isset($options['extension'])) && (!empty($options['extension'])) && (!in_array($fileinfo['extension'], $options['extension'])) && (is_array($options['extension'])) && (!empty($options['extension']))) {
                    unset($params['uploadedFile']['data']);
                }
                
                if ($options['uploadedOnly']) {
                    if (!is_uploaded_file($params['uploadedFile']['data']['tmp_name'])) {
                        unset($params['uploadedFile']['data']);
                    }
                }
            } else {
                foreach ($params['uploadedFile']['data'] as &$file) {
                    if (!isset($params['uploadedFile']['attachInfo']['validate'])) {
                        $useroptions = [];
                    } else {
                        $useroptions = $params['uploadedFile']['attachInfo']['validate'];
                    }
                    $options = $useroptions + $self::$defaults['validate'];

                    $fileinfo = pathinfo($file['name']);

                    if ((isset($options['extension'])) && (!empty($options['extension'])) && (!in_array($fileinfo['extension'], $options['extension'])) && (is_array($options['extension'])) && (!empty($options['extension']))) {
                        unset($file);
                    }

                    if ($options['uploadedOnly']) {
                        if (!is_uploaded_file($file['tmp_name'])) {
                            unset($file);
                        }
                    }
                }
            }
            return $chain->next($self, $params, $chain);
        });
    }
}
