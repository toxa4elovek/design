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

}
