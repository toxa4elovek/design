<?php

namespace app\models\behaviors\handlers;

class SetPermissionHandler extends \app\models\behaviors\handlers\StaticHandler
{

    public static function useHandler($behavior)
    {
        $behavior::applyFilter('afterSave', function ($self, $params, $chain) {
            if (isset($params['uploadedFile']['data'])) {
                if (!isset($params['uploadedFile']['data'][0])) {
                    if (!isset($params['uploadedFile']['attachInfo']['setPermission'])) {
                        $useroptions = [];
                    } else {
                        $useroptions = $params['uploadedFile']['attachInfo']['setPermission'];
                    }
                    $options = $useroptions + $self::$defaults['setPermission'];
                    if (file_exists($params['uploadedFile']['data']['newname'])) {
                        chmod($params['uploadedFile']['data']['newname'], $options['mode']);
                    }
                } else {
                    foreach ($params['uploadedFile']['data'] as &$file) {
                        if (!isset($params['uploadedFile']['attachInfo']['setPermission'])) {
                            $useroptions = [];
                        } else {
                            $useroptions = $params['uploadedFile']['attachInfo']['setPermission'];
                        }
                        $options = $useroptions + $self::$defaults['setPermission'];
                        if (file_exists($params['uploadedFile']['data']['newname'])) {
                            chmod($file['newname'], $options['mode']);
                        }
                    }
                }
            }
            return $chain->next($self, $params, $chain);
        });
    }
}
