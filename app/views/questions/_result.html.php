<section class="howitworks quiz result">
    <?php $bestResult = false; $secondResult = false; ?>
    <h1><?php echo 'Пройти тест на профпригодность'; ?></h1>
    <p>Результат: <?=$result['correct']?> из <?=$result['total']?></p>
    <?php if ($result['percent'] >= 0 && $result['percent'] < 70):
        $shareText = 'Тест «Какой ты дизайнер на самом деле» показал, что я дворник, совсем не дизайнер!';
        $bigImage = 'http://www.godesigner.ru/img/questions/dvornik.png';
        $squareImage = 'http://www.godesigner.ru/img/questions/dvornik_fb.png';
        $shareImage = 'http://www.godesigner.ru/img/questions/dvornik_468_246.png';
        ?>
        <h2 class="largest-header-blog">Вы — дворник!</h2>
        <p style="margin-bottom: 46px;">Вам можно выметать улицы, по ходу создавая различные фигуры. Это ваши последователи выстригают НЛО-образные круги на полях.</p>
        <img src="<?=$bigImage?>" alt="Вы — дворник!">
    <?php elseif ($result['percent'] >= 70 && $result['percent'] < 80):
        $shareText = 'Тест «Какой ты дизайнер на самом деле» показал, что я маляр, лучший кандидат в команду Тома Сойера!';
        $bigImage = 'http://www.godesigner.ru/img/questions/malyar.png';
        $squareImage = 'http://www.godesigner.ru/img/questions/malyar_fb.png';
        $shareImage = 'http://www.godesigner.ru/img/questions/malyar_468_246.png';
        ?>
        <h2 class="largest-header-blog">Вы — маляр!</h2>
        <p style="margin-bottom: 46px;">Непыльная работёнка, вы лучший кандидат в команду Тома Сойера. <br>Хорошая занятость и сдельная зарплата вам обеспечены.</p>
        <img src="<?=$bigImage?>" alt="Вы — маляр!">
    <?php elseif ($result['percent'] >= 80 && $result['percent'] < 90):
        $shareText = 'Тест «Какой ты дизайнер на самом деле» показал, что я Большой мастер, и выше только бог!';
        $bigImage = 'http://www.godesigner.ru/img/questions/master.png';
        $squareImage = 'http://www.godesigner.ru/img/questions/master_fb.png';
        $shareImage = 'http://www.godesigner.ru/img/questions/master_468_246.png'; ?>
        <?php $secondResult = true; ?>
        <h2 class="largest-header-blog">Вы — большой мастер!</h2>
        <p style="margin-bottom: 46px;">Большой мастер — почётное звание и выше только бог. </p>
        <img src="<?=$bigImage?>" alt="Вы — большой мастер!">
    <?php else:
        $shareText = 'Тест «Какой ты дизайнер на самом деле» показал, что я Аполлон, бог искусств!';
        $bigImage = 'http://www.godesigner.ru/img/questions/apollo.png';
        $squareImage = 'http://www.godesigner.ru/img/questions/apollo_fb.png';
        $shareImage = 'http://www.godesigner.ru/img/questions/apollo_468_246.png'; ?>
        <?php $bestResult = true; ?>
        <h2 class="largest-header-blog">Вы — Аполлон!</h2>
        <p style="margin-bottom: 46px;">Вы эталон, бог искусств. Достаточно быть собой.</p>
        <img src="<?=$bigImage?>" alt="Вы — Аполлон!">
    <?php endif; ?>
    <div class="share-this">
        <span>Поделиться результатом:</span>
        <div style="">
            <div style="float:left;height:20px;margin-right: 20px;">
                <a href="#" class="post-to-facebook" data-share-text="<?php echo $shareText; ?>" data-share-image="<?php echo $shareImage; ?>"><img src="/img/fb-test-share.png"></a>
            </div>

            <div style="float:left;height:20px;margin-right: 20px;">
                <div class="vk_share_button" style="display: inline-block;" data-share-text="<?php echo $shareText; ?>" data-share-image="<?php echo $shareImage; ?>"></div>
            </div>

            <div style="float:left;height:20px;margin-right: 20px;" class="tw-share">
                <a href="https://twitter.com/share" class="twitter-share-button" data-url="http://www.godesigner.ru/questions/index" data-text="<?php echo $shareText; ?>" data-lang="en" data-hashtags="Go_Deer" data-count="none">Tweet</a>
                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </div>

            <div style="float:left;height:20px;margin-right: 5px;" class="pin-share">
                <a href="//ru.pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.godesigner.ru%2Fquestions%2Findex&media=<?php echo urlencode($squareImage); ?>&description=<?php echo urlencode($shareText); ?>" data-pin-do="buttonPin" data-pin-config="none"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
            </div>

            <div style="clear:both;width:300px;height:1px;"></div>
        </div>
    </div>
    <?php if (($firstTime) and ($this->user->isLoggedIn())): ?>
        <?php if ($bestResult): ?>
            <?php
                $old = false;
                $datetime1 = new DateTime();
                $datetime2 = new DateTime(date('Y-m-d H:i:s', (strtotime($result['user_created']) + 5 * DAY)));
                $interval = $datetime2->diff($datetime1);
                if(($interval->y > 0) or ($interval->m > 0) or ($interval->d > 10)) {
                    $old = true;
                }
                $remain = $interval->format('%d дн. %h ч. %i мин.');
            ?>
            <p style="width: 650px;">Oh my God! Вам самое место на платформе <a href="/pitches" target="_blank">GoDesigner</a>!<?php if(!$old):?><br>Ваш аккаунт будет активирован через <?=$remain?> (срок сокращен на 5 дн.). Подробнее <a href="http://www.godesigner.ru/answers/view/96" target="_blank">тут</a><?php endif?></p>
        <?php elseif ($secondResult): ?>
            <?php
            $old = false;
            $datetime1 = new DateTime();
            $datetime2 = new DateTime(date('Y-m-d H:i:s', (strtotime($result['user_created']) + 5 * DAY)));
            $interval = $datetime2->diff($datetime1);
            if(($interval->y > 0) or ($interval->m > 0) or ($interval->d > 10)) {
                $old = true;
            }
            $remain = $interval->format('%d дн. %h ч. %i мин.');
            ?>
            <p style="width: 600px;">Вы - большой мастер, вам самое место на платформе <a href="/pitches" target="_blank">GoDesigner</a>!<?php if(!$old):?><br>Ваш аккаунт будет активирован через <?=$remain?> (срок сокращен на 5 дн.). Подробнее <a href="http://www.godesigner.ru/answers/view/96" target="_blank">тут</a><?php endif?></p>

        <?php else: ?>
            <?php
                $old = false;
                $datetime1 = new DateTime();
                $datetime2 = new DateTime(date('Y-m-d H:i:s', (strtotime($result['user_created']) + 10 * DAY)));
                $interval = $datetime2->diff($datetime1);
                if(($interval->y > 0) or ($interval->m > 0) or ($interval->d > 10)) {
                    $old = true;
                }
                $remain = $interval->format('%d дн. %h ч. %i мин.');
            ?>
            <p style="width: 600px;">Этого,  однако, недостаточно для участия на платформе <a href="/pitches" target="_blank">GoDesigner</a>, <br> поэтому мы просим вас подтянуть профессиональные навыки! Помните, что на сокращение сроков активации влияет только первое прохождение теста. <?php if(!$old):?><br>Ваш аккаунт будет активирован через <?=$remain?><?php endif?>. Подробнее <a href="http://www.godesigner.ru/answers/view/96" target="_blank">тут</a></p>
        <?php endif; ?>
    <?php else: ?>
        <?php if ($bestResult): ?>
            <p style="width: 600px;">Oh my God! Вам самое место на платформе <a href="/pitches" target="_blank">GoDesigner!</a></p>
        <?php elseif ($secondResult): ?>
            <p style="width: 600px;">Вы - большой мастер, вам самое место на платформе <a href="/pitches" target="_blank">GoDesigner</a>!</p>
        <?php else: ?>
            <p style="width: 600px;">Этого,  однако, недостаточно для участия на платформе <a href="/pitches" target="_blank">GoDesigner</a>, <br> поэтому мы просим вас подтянуть профессиональные навыки!</p>
        <?php endif; ?>
    <?php endif; ?>
</section>
