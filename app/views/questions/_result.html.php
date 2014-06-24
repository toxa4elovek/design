<section class="howitworks quiz result">
    <?php $bestResult = false; ?>
    <h1><?php echo 'Пройти тест на профпригодность'; ?></h1>
    <p>Результат: <?=$result['correct']?> из <?=$result['total']?></p>
    <?php if ($result['percent'] >= 0 && $result['percent'] < 70):
        $shareText = 'Тест «Какой ты дизайнер на самом деле» показал, что я дворник, совсем не дизайнер!';
        $shareImage = 'http://www.godesigner.ru/img/icon_512.png'; ?>
        <h2 class="largest-header-blog">Вы — дворник!</h2>
        <p>Вам можно выметать улицы, по ходу, создавая различные фигуры. Это ваши последователи выстригают нло-образные круги на полях.</p>
    <?php elseif ($result['percent'] >= 70 && $result['percent'] < 80):
        $shareText = 'Тест «Какой ты дизайнер на самом деле» показал, что я маляр, лучший кандидат в команду Тома Сойера!';
        $shareImage = 'http://www.godesigner.ru/img/icon_512.png'; ?>
        <h2 class="largest-header-blog">Вы — маляр!</h2>
        <p>Непыльная работёнка, вы лучший кандидат в команду Тома Сойера. <br>Хорошая занятость и сдельная зарплата вам обеспечены.</p>
    <?php elseif ($result['percent'] >= 80 && $result['percent'] < 90):
        $shareText = 'Тест «Какой ты дизайнер на самом деле» показал, что я Большой мастер, и выше только бог!';
        $shareImage = 'http://www.godesigner.ru/img/icon_512.png'; ?>
        <h2 class="largest-header-blog">Вы — большой мастер!</h2>
        <p>Большой мастер — почётное звание и выше только бог. </p>
    <?php else:
        $shareText = 'Тест «Какой ты дизайнер на самом деле» показал, что я Аполлон, бог искусств!';
        $shareImage = 'http://www.godesigner.ru/img/icon_512.png'; ?>
        <?php $bestResult = true; ?>
        <h2 class="largest-header-blog">Вы — Аполлон!</h2>
        <p>Вы эталон, бог искусств. Достаточно быть собой.</p>
    <?php endif; ?>
    <div class="share-this">
        <span>Поделиться результатом:</span>
        <div style="">
            <div style="float:left;height:20px;margin-right: 5px;">
                <a href="#" class="post-to-facebook" data-share-text="<?php echo $shareText; ?>" data-share-image="<?php echo $shareImage; ?>"><img src="/img/fb-test-share.png"></a>
            </div>

            <div style="float:left;height:20px;margin-right: 5px;">
                <div class="vk_share_button" style="display: inline-block;" data-share-text="<?php echo $shareText; ?>" data-share-image="<?php echo $shareImage; ?>"></div>
            </div>

            <div style="float:left;height:20px;margin-right: 5px;">
                <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.godesigner.ru/questions/index" data-text="<?php echo $shareText; ?>" data-lang="en" data-hashtags="Go_Deer" data-count="none">Tweet</a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </div>

            <div style="float:left;height:20px;margin-right: 5px;">
                <a href="//ru.pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fquestions%2Findex&media=<?php echo urlencode($shareImage); ?>&description=<?php echo urlencode($shareText); ?>" data-pin-do="buttonPin" data-pin-config="none"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
            </div>

            <div style="clear:both;width:300px;height:1px;"></div>
        </div>
    </div>
    <?php if ($firstTime): ?>
        <?php if ($bestResult): ?>
            <?php
                $datetime1 = new DateTime();
                $datetime2 = new DateTime(date('Y-m-d H:i:s', (strtotime($result['user_created']) + 5 * DAY)));
                $interval = $datetime2->diff($datetime1);
                $remain = $interval->format('%d дн. %h ч. %i мин.');
            ?>
            <p style="width: 600px;">Oh my God! Вам самое место на платформе GoDesigner!<br>Ваш аккаунт будет активирован через <?=$remain?> (срок сокращен на 5 дн.)</p>
        <?php else: ?>
            <?php
                $datetime1 = new DateTime();
                $datetime2 = new DateTime(date('Y-m-d H:i:s', (strtotime($result['user_created']) + 10 * DAY)));
                $interval = $datetime2->diff($datetime1);
                $remain = $interval->format('%d дн. %h ч. %i мин.');
            ?>
            <p style="width: 600px;">Этого,  однако, недостаточно для участия на платформе GoDesigner, <br> поэтому мы просим вас подтянуть профессиональные навыки! <br>Ваш аккаунт будет активирован через <?=$remain?></p>
        <?php endif; ?>
    <?php endif; ?>
</section>
