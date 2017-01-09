<?php

namespace app\tests\cases\models;

use app\extensions\tests\AppUnit;
use app\models\Note;
use app\models\Pitch;

class NoteTest extends AppUnit
{

    public function setUp()
    {
        $this->rollUp(['Pitch', 'Note']);
    }

    public function tearDown()
    {
        $this->rollDown(['Pitch', 'Note']);
    }

    public function testAdd()
    {
        $projectId = 1;
        // первоначальное состояние
        $project = Pitch::first($projectId);
        $this->assertEqual(0, $project->status);
        $this->assertFalse(Note::first(['conditions' => ['pitch_id' => $projectId]]));

        // добавляем заметку о возврате
        Note::addRefundNote($project);
        $project = Pitch::first($projectId);
        $this->assertEqual(2, $project->status);
        $note = Note::first(['conditions' => ['pitch_id' => $projectId]]);
        $this->assertTrue($note);
        $this->assertEqual(2, $note->status);

        // Повторное добавление не должно приводить к изменениям
        Note::addRefundNote($project);
        $project = Pitch::first($projectId);
        $this->assertEqual(2, $project->status);
        $note = Note::first(['conditions' => ['pitch_id' => 2]]);
        $this->assertTrue($note);
        $this->assertEqual(2, $note->status);
    }

    public function testRevert()
    {
        $projectId = 1;
        $project = Pitch::first($projectId);
        $this->assertFalse(Note::revertNoteToDefault($projectId));
        Note::addRefundNote($project);
        $this->assertTrue(Note::revertNoteToDefault($projectId));

        $project = Pitch::first($projectId);
        $this->assertEqual(2, $project->status);
        $note = Note::first(['conditions' => ['pitch_id' => $projectId]]);
        $this->assertTrue($note);
        $this->assertEqual(0, $note->status);

        $projectId = 3;
        $this->assertFalse(Note::revertNoteToDefault($projectId));
    }
}
