<?php

namespace app\extensions\helper;

use \app\extensions\helper\User as UserHelper;

class Solution extends \lithium\template\Helper
{

    public function renderImageUrl($images, $index=0)
    {
        if (isset($images[$index])) {
            $imageArray = $images[$index];
        } else {
            $imageArray = $images;
        }
        return $imageArray['weburl'];
    }

    public function getImageCount($images)
    {
        if (isset($images[0])) {
            return count($images);
        } else {
            return 1;
        }
    }

    public function getShortDescription($solution, $length = 100)
    {
        if (!empty($solution->description)) {
            if (mb_strlen($solution->description, 'UTF-8') > $length) {
                $res = mb_substr($solution->description, 0, $length - 1, 'UTF-8');
                $res .= '...';
                return $res;
            }
            return $solution->description;
        }
        return '';
    }

    /**
     * Метод определяет, можно ли показывать картинку/текст пользователю
     *
     * @param $solution
     * @param $size
     * @param $project
     * @param int $index
     * @return string
     */
    public function renderImageUrlRights($solution, $size, $project, $index=0)
    {
        $user = new UserHelper([]);
        if (((int) $project->category === 7) || ((int) $project->category_id === 20 && $project->isSubscriberProjectForCopyrighting())) {
            if ($user->isPitchOwner($project->user_id) || $user->isManagerOfProject($project->id) || $user->isExpert() || $user->isAdmin() || $user->isSolutionAuthor($solution->user_id)) {
                if (mb_strlen(trim($solution->description)) > 100) {
                    $description = mb_substr(trim($solution->description), 0, 100, 'UTF-8');
                } else {
                    $description = trim($solution->description);
                }
                return $description;
            } else {
                return '/img/copy-inv.png';
            }
        }
        if (($size === 'solution_galleryLargeSize') && (!isset($solution->images[$size]))) {
            return '/img/copy-inv.png';
        }
        $images = $solution->images[$size];
        if ((int) $project->private === 1) {
            if ($user->isPitchOwner($project->user_id) || $user->isManagerOfProject($project->id) || $user->isExpert() || $user->isAdmin() || $user->isSolutionAuthor($solution->user_id)) {
                return $this->renderImageUrl($images, $index);
            }
            return '/img/copy-inv.png';
        }
        return $this->renderImageUrl($images, $index);
    }
}
