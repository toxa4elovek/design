<?php

namespace app\extensions\helper;

use \app\extensions\helper\User as UserHelper;

class Solution extends \lithium\template\Helper {

    function renderImageUrl($images, $index=0) {
        if(isset($images[$index])) {
            $imageArray = $images[$index];
        }else {
            $imageArray = $images;
        }
        return $imageArray['weburl'];
    }

    function getImageCount($images){
        if(isset($images[0])) {
            return count($images);
        }else{
            return 1;
        }
    }

    function getShortDescription($solution, $length = 100) {
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

    function renderImageUrlRights($solution, $size, $pitch, $index=0) {
        if ($pitch->category == 7) {
            return '/img/copy-inv.png';
        }
        $images = $solution->images[$size];
        if ($pitch->private == 1) {
            $user = new UserHelper();
            if ($user->isPitchOwner($pitch->user_id) || $user->isExpert() || $user->isAdmin() || $user->isSolutionAuthor($solution->user_id)) {
                return $this->renderImageUrl($images, $index);
            }
            return '/img/copy-inv.png';
        }
        return $this->renderImageUrl($images, $index);
    }

}
