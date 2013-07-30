<?php

namespace app\models;

class Wincomment extends \app\models\AppModel {

    public $belongsTo = array('Solution', 'User');

    //public static $fileModel = 'app\models\Pitchfile';

    protected static $_behaviors = array(
        'UploadableWincomment'
    );

    public static $attaches = array('file' => array(
        /*'validate' => array('uploadedOnly' => true),*/
        'moveFile' => array('preserveFileName' => true, 'path' => '/webroot/files/'),
        /*'setPermission' => array('mode' => 0766),*/
    ));

    public static function createComment($data) {
        return static::_filter(__FUNCTION__, $data, function($self, $params) {
            $comment = $self::create();
            $comment->set($params);
            $comment->created = date('Y-m-d H:i:s');
            $comment->save();
            $params['id'] = $comment->id;
            return $params;
        });
    }

    public static function __init() {
        parent::__init();

        self::applyFilter('find', function($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            if(is_object($result)) {
                $addMentionLink = function($record) {
                    if(isset($record->text)) {
                        $record->text = nl2br($record->text);
                        $solutionId = $record->solution_id;
                        $record->text = preg_replace('/@([^@]*? [^@]\.)(,?)/u', '<a href="#" class="mention-link" data-comment-to="$1">@$1$2</a>', strip_tags($record->text, '<br><a>'));
                    }
                    return $record;
                };

                $addOriginalText = function($record){
                    if(isset($record->text)) {
                        $record->originalText = $record->text;
                    }
                    return $record;
                };

                if (get_class($result) == 'lithium\data\entity\Record') {
                    $result = $addOriginalText($result);
                    $result = $addMentionLink($result);
                } else {
                    foreach ($result as $foundItem) {
                        $foundItem = $addOriginalText($foundItem);
                        $foundItem = $addMentionLink($foundItem);
                    }
                }
            }
            return $result;
        });
    }
}