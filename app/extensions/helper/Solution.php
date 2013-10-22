<?php

namespace app\extensions\helper;

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

}
