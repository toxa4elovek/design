<?php $bestResult = false; $secondResult = false; ?>
<?php
    if($result['percent'] >= 80 && $result['percent'] < 90) {
        $secondResult = true;
    }
    if($result['percent'] >= 90) {
        $bestResult = true;
    }
?>
<?php if (($firstTime) and ($this->user->isLoggedIn())): ?>
    <?php if ($bestResult):
        $old = false;
        $datetime1 = new DateTime();
        $datetime2 = new DateTime(date('Y-m-d H:i:s', (strtotime($result['user_created']) + 5 * DAY)));
        $interval = $datetime2->diff($datetime1);
        if(($interval->y > 0) or ($interval->m > 0) or ($interval->d > 10)) {
            $old = true;
        }
        $remain = $interval->format('%d дн. %h ч. %i мин.');

        $helpText = '<br>Oh my God! Вам самое место на платформе <a href="/pitches" target="_blank">GoDesigner</a>!';
        if(!$old):
            //$helpText .= '<br>Ваш аккаунт будет активирован через ' . $remain . ' (срок сокращен на 5 дн.). Подробнее <a href="http://godesigner.ru/answers/view/96" target="_blank">тут</a>';
        endif;
        $addonText = '<p style="width: 650px">Поделитесь результатом с друзьями, и мы сократим ваш срок активации<br>
на 5 дней, и тогда вы сможете принимать участие в проектах совсем скоро! Внимание, наличие активной ссылки в социальных сетях будет проверено!</p>';
    elseif ($secondResult): ?>
        <?php
        $old = false;
        $datetime1 = new DateTime();
        $datetime2 = new DateTime(date('Y-m-d H:i:s', (strtotime($result['user_created']) + 5 * DAY)));
        $interval = $datetime2->diff($datetime1);
        if(($interval->y > 0) or ($interval->m > 0) or ($interval->d > 10)) {
            $old = true;
        }
        $remain = $interval->format('%d дн. %h ч. %i мин.');

        $helpText = '<br>Вам самое место на <a href="/pitches" target="_blank">GoDesigner</a>!';
        if(!$old):
            //$helpText .= '<br>Ваш аккаунт будет активирован через ' . $remain . ' (срок сокращен на 5 дн.). Подробнее <a href="http://godesigner.ru/answers/view/96" target="_blank">тут</a>';
        endif;
        $addonText = '<p style="width: 650px">Поделитесь результатом с друзьями, и мы сократим ваш срок активации<br>
на 5 дней, и тогда вы сможете принимать участие в проектах совсем скоро! Внимание, наличие активной ссылки в социальных сетях будет проверено!</p>';
    else: ?>
        <?php
        $old = false;
        $datetime1 = new DateTime();
        $datetime2 = new DateTime(date('Y-m-d H:i:s', (strtotime($result['user_created']) + 10 * DAY)));
        $interval = $datetime2->diff($datetime1);
        if(($interval->y > 0) or ($interval->m > 0) or ($interval->d > 10)) {
            $old = true;
        }
        $remain = $interval->format('%d дн. %h ч. %i мин.');

        $helpText = '<br>Этого,  однако, недостаточно для участия на платформе <a href="/pitches" target="_blank">GoDesigner</a>, поэтому мы просим вас подтянуть профессиональные навыки! Помните, что на сокращение сроков активации влияет только первое прохождение теста.';
        if(!$old):
            $helpText .= '<br>Ваш аккаунт будет активирован через ' . $remain;
        endif;
        $helpText .= 'Подробнее <a href="https://godesigner.ru/answers/view/96" target="_blank">тут</a>';
        $addonText = '<p style="width: 650px;">К сожалению, вы уже упустили шанс сократить срок активации, завалив первую попытку. Вы сможете принимать участие в проектах через ' . $remain . '.</p>';
    endif; ?>
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


        if ($bestResult):
        $helpText = '<br>Oh my God! Вам самое место на платформе <a href="/pitches" target="_blank">GoDesigner!</a>';
    elseif ($secondResult):
        $helpText = '<br>Вам самое место на <a href="/pitches" target="_blank">GoDesigner</a>!';
    else:
        $helpText = '';
        if(!$old):
            $helpText = '<br>Этого,  однако, недостаточно для участия на платформе <a href="/pitches" target="_blank">GoDesigner</a>, поэтому мы просим вас подтянуть профессиональные навыки!';
            $addonText = '<p style="">К сожалению, вы уже упустили шанс сократить срок активации, завалив первую попытку.';
            $addonText .= ' Вы сможете принимать участие в проектах через ' . $remain . '.';
            $addonText .= '</p>';
        endif;

    endif; ?>
<?php endif; ?>

<section class="howitworks quiz result">
    <h1><?php echo 'Пройти тест на профпригодность'; ?></h1>
    <p>Результат: <?=$result['correct']?> из <?=$result['total']?></p>
    <?php if ($result['percent'] >= 0 && $result['percent'] < 70):
        $shareText = 'Тест «Какой ты дизайнер на самом деле» показал, что я дворник, совсем не дизайнер!';
        $bigImage = 'https://godesigner.ru/img/questions/dvornik.png';
        $squareImage = 'https://godesigner.ru/img/questions/dvornik_fb.png';
        $shareImage = 'https://godesigner.ru/img/questions/dvornik_468_246.png';
        $urlParam = 'dvornik';
        ?>
        <h2 class="largest-header-blog">Вы — дворник!</h2>
        <p style="margin-bottom: 46px;">Вам можно выметать улицы, по ходу создавая различные фигуры. Это ваши последователи выстригают НЛО-образные круги на полях. <?php echo $helpText;?></p>
        <?php
        echo $addonText;
        $fullimagetag = '<img src="' . $bigImage . '" alt="Вы — дворник!">';
        ?>
    <?php elseif ($result['percent'] >= 70 && $result['percent'] < 80):
        $shareText = 'Тест «Какой ты дизайнер на самом деле» показал, что я маляр, лучший кандидат в команду Тома Сойера!';
        $bigImage = 'https://godesigner.ru/img/questions/malyar.png';
        $squareImage = 'https://godesigner.ru/img/questions/malyar_fb.png';
        $shareImage = 'https://godesigner.ru/img/questions/malyar_468_246.png';
        $urlParam = 'malyar';
        ?>
        <h2 class="largest-header-blog">Вы — маляр!</h2>
        <p style="margin-bottom: 46px;">Непыльная работёнка, вы лучший кандидат в команду Тома Сойера. <br>Хорошая занятость и сдельная зарплата вам обеспечены. <?php echo $helpText;?></p>
        <?php echo $addonText;
        $fullimagetag = '<img src="' . $bigImage . '" alt="Вы — маляр!">';
        ?>
    <?php elseif ($result['percent'] >= 80 && $result['percent'] < 90):
        $shareText = 'Тест «Какой ты дизайнер на самом деле» показал, что я Большой мастер, и выше только бог!';
        $bigImage = 'https://godesigner.ru/img/questions/master.png';
        $squareImage = 'https://godesigner.ru/img/questions/master_fb.png';
        $shareImage = 'https://godesigner.ru/img/questions/master_468_246.png';
        $urlParam = 'master';
        ?>
        <?php $secondResult = true; ?>
        <h2 class="largest-header-blog">Вы — большой мастер!</h2>
        <p style="margin-bottom: 46px;">Большой мастер — почётное звание и выше только бог.  <?php echo $helpText;?></p>
        <?php echo $addonText;
        $fullimagetag = '<img src="' . $bigImage . '" alt="Вы — большой мастер!">';
        ?>
    <?php else:
        $shareText = 'Тест «Какой ты дизайнер на самом деле» показал, что я Аполлон, бог искусств!';
        $bigImage = 'https://godesigner.ru/img/questions/apollo.png';
        $squareImage = 'https://godesigner.ru/img/questions/apollo_fb.png';
        $shareImage = 'https://godesigner.ru/img/questions/apollo_468_246.png';
        $urlParam = 'apollo';
        ?>
        <?php $bestResult = true; ?>
        <h2 class="largest-header-blog">Вы — Аполлон!</h2>
        <p style="margin-bottom: 46px;">Вы эталон, бог искусств. Достаточно быть собой. <?php echo $helpText;?></p>
        <?php echo $addonText;
        $fullimagetag = '<img src="' . $bigImage . '" alt="Вы — Аполлон!">';
        ?>
    <?php endif; ?>

    <div class="share-this">
        <div style="display: block; float: left; margin-top: 8px; margin-bottom: 10px;">
            <div class="social-likes" data-counters="no" data-title="<?= $shareText ?>" data-url="https://godesigner.ru/questions?result=<?= $urlParam ?>" style="padding-left: 230px;">
                <div onclick="activate(this);" <?php if(!is_null($test)):?>data-testid="<?= $test->id ?>"<?php endif?> style="margin: 7px 0 0 9px;" class="activate-user facebook" data-image="<?=$shareImage ?>" title="Поделиться ссылкой на Фейсбуке">SHARE</div>
                <div onclick="activate(this);" <?php if(!is_null($test)):?>data-testid="<?= $test->id ?>"<?php endif?> style="margin: 7px 0 0 7px;" class="activate-user twitter" data-via="Go_Deer">TWITT</div>
                <div onclick="activate(this);" <?php if(!is_null($test)):?>data-testid="<?= $test->id ?>"<?php endif?> style="margin: 7px 0 0 7px;" class="activate-user vkontakte" data-image="<?=$shareImage ?>" title="Поделиться ссылкой во Вконтакте">SHARE</div>
                <div onclick="activate(this);" <?php if(!is_null($test)):?>data-testid="<?= $test->id ?>"<?php endif?> style="margin: 7px 0 0 7px;" class="activate-user pinterest" data-media="<?=$shareImage ?>" title="Поделиться картинкой на Пинтересте">PIN</div>
            </div>
        </div>
        <div style="clear:both;width:300px;height:1px;"></div>
    </div>
    <?php echo $fullimagetag ?>
</section>
