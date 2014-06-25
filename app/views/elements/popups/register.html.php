<div id="popup-register" style="display: none;">
    <h2>Спасибо за регистрацию!</h2>
    <p class="regular">Мы очень рады, что вы решили стать частью нашего креативного сообщества. Через 10 дней с момента регистрации вы сможете выкладывать свои идеи.</p>
    <p>Пока вы можете принять участие либо в бесплатных проектах, либо пройти <a href="/questions">тест на профпригодность</a>. Это уменьшит срок ожидания на 5 дней!</p>
    <p>Такие меры созданы для того, чтобы обезопасить дизайнеров от мошенничества и недобросовестных клиентов. Спасибо за понимание и творческих успехов!</p>
    <div class="wrapper" style="text-align: center;"><a href="/questions" class="button third">Пройти тест</a></div>
    <div class="gotest-close"></div>
</div>

<div id="popup-after-facebook" style="display: none;">
    <h2>Ваше призвание</h2>
    <?php echo $this->form->create($user, array('action' => 'setStatus', 'id' => 'setStatus'));?>
    <label><?=$this->form->radio('who_am_i_fb', array('value' => 'client', 'class' => 'radio-input'))?>Я — Заказчик</label>
    <label><?=$this->form->radio('who_am_i_fb', array('value' => 'designer', 'class' => 'radio-input'))?>Я — Дизайнер</label>
	<div class="wrapper" style="text-align: center;">
	   <input type="submit" class="button third" value="Отправить">
	</div>
    <?=$this->form->end() ?>
    <div class="gotest-close"></div>
</div>
