<div class="wrapper">
<?php if($this->user->getId()):?>
<?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header'))?>
<?php else:?>
    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>
<?php endif?>
	<div class="middle">
		<div class="middle_inner" style="padding-top: 45px;">
            <div class="content group">
                        <div id="ap_content_top" style="padding:0;">
                        	<div id="ap_content_top_l">
                                <img src="/img/special_offer.png" alt="Специальное предложение" style="margin-top:0"/>
                                <div id="ap_content_top_l_img"><img src="/img/vernem_dengi_feb-01.png" style="margin-top:0"/></div>

                                <p class="regular">Мы вернем вам деньги, если по истечении срока предложенные идеи не понравятся*. Комментируйте решения, активно взаимодействуйте с дизайнерами, выставляйте рейтинг и вы обязательно получите то, что хотели**. </p>

<p class="regular">Во время работы, пожалуйста, <a href="http://godesigner.copiny.com/" target="_blank">оставьте свой отзыв</a> или совет по улучшению сервиса, если:</p>
<ul class="special-unnumbered" style="">
<li class="regular">вы столкнулись с проблемой;</li>
<li class="regular">есть замечания по дизайну или работе сервиса;</li>
<li class="regular">вам или нам чего-то не хватает на сайте</li>
</ul>


<p class="regular">Попробуйте наш сервис первыми, и ваш дизайн станет лицом уникального стартапа.</p>

<p style="font-size:11px;text-shadow:-1px 0 0 #FFFFFF;">* — Мы возвращаем сумму, указанную в поле “Сумма вознаграждения дизайнеру”. Мы не возвращаем сбор сайта Go Designer (14,5% – 24,5%), а также стоимости дополнительных опций ”Рекламный кейс”, “Экспертное мнение”, “Заполнить бриф” и ”Прокачать бриф”, если они были заказаны. Решение о возврате необходимо принять в течение 3 рабочих дней после окончания срока проекта и оставить комментарий в галерее решений, объяснив дизайнерам, что эти идеи вам не подходят.</p>
<p style="font-size:11px;text-shadow:-1px 0 0 #FFFFFF;">** — Возврат средств возможен тогда, когда вы активно взаимодействовали с креативщиками на протяжении всего конкурса. Подробнее <a href="https://www.godesigner.ru/answers/view/71" target="_blank">тут</a>.</p>
                            </div>
                            <div id="ap_content_top_r" style="width: 274px; margin-top: 70px;">
                            	<div id="ap_content_r_1" style="padding-left:90px" class="regular">
                                    <h2>Возникли вопросы?</h2>
                                    Если вы не можете найти ответ на свой <span style="white-space: nowrap;">вопрос — напишите нам</span>. Мы постараемся ответить в течение 24 часов по рабочим дням.
                                    <?=$this->html->link('<img src="/img/send-email.png">', 'Pages::contacts', array('escape' => false))?>
                                </div>
                            </div>
                        </div>
                    
            </div><!-- /content -->		
		</div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->			
	</div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->style(array('/howitworks', '/about_project', '/special'), array('inline' => false))?>