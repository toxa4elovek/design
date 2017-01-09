<?php

namespace app\controllers;

use \app\models\Moderation;
use \app\models\Comment;
use \app\models\Solution;
use \lithium\storage\Session;

class ModerationsController extends AppController
{

    public function add()
    {
        $result = false;
        $currentUser = Session::read('user.id');
        if (isset($this->request->data) && ($this->userHelper->isAdmin())) {
            if (($this->request->data['model'] == 'comment') && ($comment = Comment::first($this->request->data['model_id']))) {
                $data = [
                    'model' => '\app\models\Comment',
                    'model_id' => $comment->id,
                    'model_user' => $comment->user_id,
                    'model_data' => serialize([
                        'created' => $comment->created,
                        'text' => $comment->text,
                ])];
                $pitch_id = $comment->pitch_id;
            }
            if (($this->request->data['model'] == 'solution') && ($solution = Solution::first($this->request->data['model_id']))) {
                $data = [
                    'model' => '\app\models\Solution',
                    'model_id' => $solution->id,
                    'model_user' => $solution->user_id,
                    'model_data' => serialize([
                        'created' => $solution->created,
                        'description' => $solution->description,
                        'image' => self::getThumbnail($solution),
                ])];
                $pitch_id = $solution->pitch_id;
                if ($this->request->data['penalty'] == 3) {
                    $data['pitch_id'] = $pitch_id;
                }
            }

            $data['user_id'] = $currentUser;
            $data['reason'] = $this->request->data['reason'];
            $data['penalty'] = $this->request->data['penalty'];
            $data['explanation'] = $this->request->data['explanation'];
            $data['created'] = date('Y-m-d H:i:s');
            $moderation = Moderation::create();
            $moderation->set($data);
            $moderation->save();
            $result = $moderation->id;
            if ($this->request->is('json')) {
                return json_encode($result);
            }
            return $this->redirect('/pitches/view/' . $pitch_id);
        }
        return json_encode($result);
    }

    private static function getThumbnail($solution)
    {
        if (isset($solution->images['solution_galleryLargeSize'][0])) {
            $image = $solution->images['solution_galleryLargeSize'][0];
        } else {
            $image = $solution->images['solution_galleryLargeSize'];
        }
        if (file_exists($image['filename'])) {
            $newFileName = LITHIUM_APP_PATH . '/webroot/solutions/deleted/' . pathinfo($image['filename'], PATHINFO_BASENAME);
            copy($image['filename'], $newFileName);
        } else {
            $newFileName = null;
        }
        return $newFileName;
    }
}
