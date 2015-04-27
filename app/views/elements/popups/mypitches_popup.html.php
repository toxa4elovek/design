<?php
    $shareText = 'Мой новый заказ на лучший дизайн. Следите и помогайте с выбором!';
?>

<div id="popup-mypitches-true" style="display: none;">
    <h2>Расскажите всем,<br /> что вы создали<br /> такой замечательный проект!</h2>
    <p style="text-align:center">Поделитесь с друзьями новым проектом, и это поможет привлечь больше дизайнеров.</p>
    <div class="share-this" style="margin-left:100px;">
        <div style="">
            <div style="float:left;height:20px;margin-right: 20px;">
                <a href="#" class="post-to-facebook" data-share-text="<?php echo $shareText; ?>"><img src="/img/fb-test-share.png"></a>
            </div>
            <div style="float:left;height:20px;margin-right: 20px;">
                <div class="vk_share_button" style="display: inline-block;" data-share-text="<?php echo $shareText; ?>"></div>
            </div>
            <div style="float:left;height:20px;margin-right: 20px;" class="tw-share">
                <a href="https://twitter.com/share" class="twitter-share-button" data-url="" data-text="<?php echo $shareText; ?>" data-lang="en" data-hashtags="Go_Deer" data-count="none">Tweet</a>
            </div>
            <div style="clear:both;width:300px;height:1px;"></div>
        </div>
    </div>
    <div id="popup-social-nest"></div>
    <div class="true-close"></div>
</div>
<script type="text/javascript" id="share-vk" src="http://vk.com/js/api/share.js?90"></script>