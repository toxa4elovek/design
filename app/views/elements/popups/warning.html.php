<div id="popup-final-step" class="popup-final-step" style="display:none;">
    <h3>Убедитесь в правильном выборе!</h3>
    <p>Эта процедура является окончательной, и в дальнейшем вы не сможете изменить своё мнение. Пожалуйста, убедитесь ещё раз в верности вашего решения. Вы уверены, что победителем проекта становится <a id="winner-user-link" href="#" target="_blank"></a> c решением <a id="winner-num" href="#" target="_blank"></a>?</p><br/>
    <p>Помните, что у вас также есть возможность выбрать <span id="roman">II</span> победителя. Подробнее <a title="Как выбрать второго победителя?" href="https://godesigner.ru/answers/view/97">тут</a>.</p>
    <div class="portfolio_gallery" style="width:200px;margin-bottom:5px;">
        <ul class="list_portfolio">
            <li>
                <div id="replacingblock">
                    <a href="#"><img alt="" src="#"></a>
                    <div class="photo_opt">
                        <span class="rating_block"><img alt="" src="/img/0-rating.png"></span>
                        <span class="like_view" style="margin-top:1px;"><img class="icon_looked" alt="" src="/img/looked.png"><span>0</span>
                            <a data-id="57" class="like-small-icon" href="#"><img alt="" src="/img/like.png"></a><span>0</span></span>
                        <span class="bottom_arrow"><a class="solution-menu-toggle" href="#"><img alt="" src="/img/marker5_2.png"></a></span>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="final-step-nav wrapper"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="confirmWinner" value="Да, подтвердить"></div>
</div>

<div id="popup-final-step-multi" class="popup-final-multi" style="display:none;">
    <h3 style="margin: 25px 0 0.6em;">Убедитесь в правильном выборе <?= $pitchesCount + 2 ?>-ого победителя!</h3>
    <p>Сейчас вы будете направлены на страницу оплаты. Помните, что выкупить решение можно только при первоначальном размере вознаграждения. <br />Стоимость дополнительных опций, не взимается, если таковы были заказаны.</p>
    <br />
    <p>После завершения оплаты вы не сможете изменить своё мнение. Пожалуйста, убедитесь еще раз в верности вашего решения. Вы уверены, что <?= $pitchesCount + 2 ?> победителем проекта становится <a id="winner-user-link-multi" href="#" target="_blank"></a> с решением <a id="winner-num-multi" href="#" target="_blank"></a>?</p>
    <div class="portfolio_gallery" style="width:200px;margin-bottom:5px;">
        <ul class="list_portfolio">
            <li>
                <div id="multi-replacingblock">
                    <a href="#"><img alt="" src="#"></a>
                    <div class="photo_opt">
                        <span class="rating_block"><img alt="" src="/img/0-rating.png"></span>
                        <span class="like_view" style="margin-top:1px;"><img class="icon_looked" alt="" src="/img/looked.png"><span>0</span>
                            <a data-id="57" class="like-small-icon" href="#"><img alt="" src="/img/like.png"></a><span>0</span></span>
                        <span class="bottom_arrow"><a class="solution-menu-toggle" href="#"><img alt="" src="/img/marker5_2.png"></a></span>
                    </div>
                </div>
            </li>
        </ul>
    </div>
    <div class="final-step-nav wrapper"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="confirmWinner-multi" value="Да, подтвердить"></div>
</div>

<div id="popup-warning" class="popup-warn generic-window" style="display:none">
    <p style="margin-top:120px;">Вы можете пожаловаться, если обнаружены грубые высказывания, реклама, спам, контент для взрослых, ссылки на работы, сделки вне Go Designer, копирование чужой работы или плагиат. В последнем случае важно предоставить ссылку на оригинал. Важно однако учитывать, что в проекте с одним брифом некоторая степень похожести работ допускается. Подробнее <a href="https://godesigner.ru/answers/view/38" target="_blank">тут</a></p>
    <p>Пожалуйста, прокомментируйте суть жалобы:</p>
    <textarea id="warn-solution" class="placeholder" placeholder="ВАША ЖАЛОБА"></textarea>
    <div class="final-step-nav wrapper" style="margin-top:20px;"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="sendWarn" value="Да, подтвердить"></div>
</div>

<div id="popup-warning-comment" class="popup-warn generic-window" style="display:none">
    <p style="margin-top:120px;">Вы можете пожаловаться, если обнаружены грубые высказывания, реклама, спам, контент для взрослых, ссылки на работы, сделки вне Go Designer, копирование чужой работы или плагиат. В последнем случае важно предоставить ссылку на оригинал. Важно однако учитывать, что в проекте с одним брифом некоторая степень похожести работ допускается. Подробнее <a href="https://godesigner.ru/answers/view/38" target="_blank">тут</a></p>
    <p>Пожалуйста, прокомментируйте суть жалобы:</p>
    <textarea id="warn-comment" class="placeholder" placeholder="ВАША ЖАЛОБА"></textarea>
    <div class="final-step-nav wrapper" style="margin-top:20px;"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="sendWarnComment" value="Да, подтвердить"></div>
</div>
<!-- Moderation Popups -->
<?php if ($this->user->isAdmin()): ?>
    <?= $this->view()->render(['element' => 'moderation']) ?>
<?php endif; ?>

<div id="popup-warning-thanks" class="popup-warn generic-window" style="display:none">
    <p>Спасибо за то, что помогаете сделать нашу платформу лучше!<br>
        Ваша жалоба принята и будет обязательно рассмотрена в порядке очереди в течение рабочего дня. Команда GoDesigner, однако, оставляет за собой право не удовлетворять жалобу без объяснения причин.</p>
</div>

<div id="popup-need-wait" style="display: none;">
    <h2>Упс!</h2>
    <p>Вы сможете предложить идею в этом проекте через:</p>
    <h2><?php echo ($interval = $this->user->designerTimeRemain()) ? $interval->format('%d дн. %H:%I:%S') : ''; ?></h2>
    <p>Мы очень рады, что вы решили стать частью нашего креативного сообщества. Однако предлагать решения вы сможете только через 10 дней с момента регистрации.</p>
    <p>Пока вы можете принять участие в наших <a href="<?= count($freePitch) > 0 ? '/pitches/details/' . $freePitch->id : 'https://godesigner.ru/posts/view/192' ?>">бесплатных проектах</a>. Вы также можете пройти <a href="/questions">тест на профпригодность</a>, что уменьшит срок ожидания на 5 дней!</p>
    <p>Такие меры созданы для того, чтобы обезопасить дизайнеров от мошенничества и недобросовестных клиентов. Спасибо за понимание и творческих успехов!</p>
    <div class="gotest-close"></div>
</div>

<div id="popup-need-confirm-email" style="display: none;">
    <h2>Упс!</h2>
    <p>Мы очень рады, что вы решили стать частью нашего креативного сообщества. Однако ваша регистрация не завершена полностью &mdash; необходимо подтвердить ваш электронный почтовый ящик.</p>
    <p>Чтобы это сделать, нажмите на соответствующую ссылку в письме &laquo;Активация аккаунта на сайте Godesigner.ru&raquo;, которые пришло вам на ящик электронной почты, указанный вами при регистрации.</p>
    <p>Если вы не видите этого письма, проверьте папку &laquo;спам&raquo; или вы можете <a id="resend" href="#">получить это письмо еще раз.</a></p>
    <p class="regular" id="mailsent" style="display:none;font-weight: bold;"><br>Письмо было отправлено вам на почту!</p>
    <div class="gotest-close"></div>
</div>

<div id="popup-hint-select-winner" style="display:none;">
    <h2>Как выбрать победителя:</h2>
    <a href="/answers/view/70" target="_blank"><img src="/img/gallery/how-select-winner.png" alt="Как выбрать победителя"></a>
</div>