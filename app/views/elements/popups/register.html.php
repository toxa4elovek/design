<div id="popup-register" style="display: none;">
    <h2>Спасибо за регистрацию!</h2>
    <p class="regular">Мы очень рады, что вы решили стать частью нашего креативного сообщества. Через 10 дней с момента регистрации вы сможете выкладывать свои идеи.</p>
    <p>Пока вы можете принять участие либо в <a href="<?=count($freePitch)>0 ? '/pitches/details/'.$freePitch->id : 'https://godesigner.ru/posts/view/192'?>">бесплатных проектах</a>, либо пройти <a href="/questions">тест на профпригодность</a>. Это уменьшит срок ожидания на 5 дней!</p>
    <p>Такие меры созданы для того, чтобы обезопасить дизайнеров от мошенничества и недобросовестных клиентов. Спасибо за понимание и творческих успехов!</p>
    <div class="wrapper" style="text-align: center;"><a href="/questions" class="button third">Пройти тест</a></div>
    <div class="gotest-close"></div>
</div>

<div id="popup-after-facebook" style="display: none;">
    <h2>Ваше призвание</h2>
    <?php echo $this->form->create($user, array('action' => 'setStatus', 'id' => 'setStatus'));?>
    <label><?=$this->form->radio('who_am_i_fb', array('value' => 'client', 'class' => 'radio-input'))?>Я — Заказчик</label>
    <label><?=$this->form->radio('who_am_i_fb', array('value' => 'company', 'class' => 'radio-input'))?>Я — Юр. лицо</label>
    <label><?=$this->form->radio('who_am_i_fb', array('value' => 'designer', 'class' => 'radio-input'))?>Я — Дизайнер</label>
	<div class="wrapper" style="text-align: center;">
	   <input type="submit" class="button third" value="Отправить">
	</div>
    <?=$this->form->end() ?>
    <div class="gotest-close"></div>
</div>

<div id="popup-email-warning" style="display: none;">
    <h2>Спасибо за регистрацию!</h2>
    <p class="regular">К сожалению, владельцы эл.адресов с нижеперечислен&shy;ными доменами испытывают трудности с получением писем и уведомлений от GoDesigner:</p>
    <ul>
		<li>@mail.ru</li>
		<li>@inbox.ru</li>
		<li>@list.ru</li>
		<li>@bk.ru</li>
	</ul>
    <p>В связи с чем мы просим вас по возможности указать другой email. Спасибо за понимание!</p>
    <div class="wrapper" style="text-align: center;"><a href="#" class="button third gotest-close" style="width:190px">Закрыть</a></div>
</div>