<?php

namespace app\controllers;

use app\extensions\helper\Brief;
use app\extensions\helper\User;
use app\models\Wincomment;
use lithium\action\Response;
use lithium\storage\Session;

class WincommentsController extends AppController
{

    /**
     * Метод удаления комментария на завершающем этапе
     *
     * @return Response|string
     */
    public function delete()
    {
        $allowedSteps = [2, 3];
        if (!in_array((int) $this->request->query['step'], $allowedSteps)) {
            $step = 3;
        } else {
            $step = (int) $this->request->query['step'];
        }
        $comment = Wincomment::first($this->request->id);
        if ($comment && ($this->userHelper->isAdmin() || $this->userHelper->isCommentAuthor($comment->user_id))) {
            $comment->delete();
            if (is_null($this->request->env('HTTP_X_REQUESTED_WITH'))) {
                return $this->redirect('/users/step' . $step . '/' . $comment->solution_id);
            } else {
                return json_encode('true');
            }
        }
        return json_encode('false');
    }

    /**
     * Метод редактирования существующего комментария на завершающем этапе
     *
     * @return string
     */
    public function edit()
    {
        $comment = Wincomment::first($this->request->id);
        if ($comment && ($this->userHelper->isAdmin() || $this->userHelper->isCommentAuthor($comment->user_id))) {
            $comment->text = $this->request->data['text'];
            $comment->save();
            $comment = Wincomment::first($comment->id);
            $brief = new Brief();
            return html_entity_decode($brief->deleteHtmlTagsAndInsertHtmlLinkInTextAndMentions($comment->text));
        }
    }
}
