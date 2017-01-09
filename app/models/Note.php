<?php
namespace app\models;

use lithium\data\entity\Record;

/**
 * Класс заметки, которая прилагается к проекту
 * @package app\models
 */
class Note extends AppModel
{

    public $belongsTo = ['Pitch'];

    /**
     * Метод помечает проект, для которого оформлен возврат
     *
     * @param Record $project объект проекта
     * @return bool
     */
    public static function addRefundNote(Record $project)
    {
        if (!$note = self::first(['conditions' => ['pitch_id' => $project->id]])) {
            $note = self::create();
        }
        if ($note->status != 2) {
            $note->set([
                'pitch_id' => $project->id,
                'status' => 2
            ]);
            $note->save();
        }
        if ($project->status != 2) {
            $project->status = 2;
            $project->save();
        }
        return true;
    }

    /**
     * Метод отменяет пометку возвата/завершения
     *
     * @param $project_id
     * @return bool
     */
    public static function revertNoteToDefault($project_id)
    {
        if (($note = self::first(['conditions' => ['pitch_id' => $project_id]])) && ($note->status != 0)) {
            $note->status = 0;
            return $note->save();
        }
        return false;
    }
}
