<?php
    $shareText = 'Тест';
?>

<div id="popup-mypitches-true" style="display: none;">
    <h2>true!</h2>
    <div class="share-this">
        <span>Поделиться результатом:</span>
        <div style="">
            <div style="float:left;height:20px;margin-right: 20px;">
                <a href="#" class="post-to-facebook" data-share-text="<?php echo $shareText; ?>"><img src="/img/fb-test-share.png"></a>
            </div>
            <div style="float:left;height:20px;margin-right: 20px;">
                <div class="vk_share_button" style="display: inline-block;" data-share-text="<?php echo $shareText; ?>"></div>
            </div>
            <div style="float:left;height:20px;margin-right: 20px;" class="tw-share">
                <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.godesigner.ru/users/mypitches" data-text="<?php echo $shareText; ?>" data-lang="en" data-hashtags="Go_Deer" data-count="none">Tweet</a>
                <script>!function(d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (!d.getElementById(id)) {
                            js = d.createElement(s);
                            js.id = id;
                            js.src = "//platform.twitter.com/widgets.js";
                            fjs.parentNode.insertBefore(js, fjs);
                        }
                    }(document, "script", "twitter-wjs");</script>
            </div>
            <div style="float:left;height:20px;margin-right: 5px;" class="pin-share">
                <a href="//ru.pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fquestions%2Findex&description=<?php echo urlencode($shareText); ?>" data-pin-do="buttonPin" data-pin-config="none"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
            </div>
            <div style="clear:both;width:300px;height:1px;"></div>
        </div>
    </div>
    <div class="true-close"></div>
</div>