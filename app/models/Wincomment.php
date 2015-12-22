<?php

namespace app\models;

class Wincomment extends AppModel
{

    public $belongsTo = array('Solution', 'User');

    protected static $_behaviors = array(
        'UploadableWincomment'
    );

    public static $attaches = array('file' => array(
        'moveFile' => array('preserveFileName' => false, 'path' => '/webroot/files/'),
    ));

    public static function _init()
    {
        parent::__init();
        self::applyFilter('find', function ($self, $params, $chain) {
            $result = $chain->next($self, $params, $chain);
            if (is_object($result)) {
                $addMentionLink = function ($record) {
                    if (isset($record->text)) {
                        $record->text = nl2br($record->text);
                        $solutionId = $record->solution_id;
                        $record->text = preg_replace('/@([^@]*? [^@]\.)(,?)/u', '<a href="#" class="mention-link" data-comment-to="$1">@$1$2</a>', strip_tags($record->text, '<br><a>'));
                    }
                    return $record;
                };

                $addOriginalText = function ($record) {
                    if (isset($record->text)) {
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
        self::applyFilter('createComment', function ($self, $params, $chain) {
            $change = array(4, 5);
            $admin = 108;
            if (in_array($params['user_id'], $change)) {
                $params['user_id'] = $admin;
            }
            return $chain->next($self, $params, $chain);
        });
    }

    public static function createComment($data)
    {
        return static::_filter(__FUNCTION__, $data, function ($self, $params) {
            $comment = $self::create();
            $comment->set($params);
            $comment->created = date('Y-m-d H:i:s');
            $comment->save();
            $params['id'] = $comment->id;
            return $params;
        });
    }
}
