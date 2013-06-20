<ul>
    <li class="first_li"><?=$this->html->link('Обновления', array('controller' => 'users', 'action' => 'office'), array('escape' => false, 'class' => 'active')) ?></li>
    <li><?=$this->html->link('Мои питчи', array('controller' => 'users', 'action' => 'mypitches'), array('escape' => false)) ?></li>
    <!--li><a href="">Сообщения</a></li-->
    <li><a href="#">Профиль</a></li>
    <li><?=$this->html->link('Решения<span></span>', array('controller' => 'users', 'action' => 'solutions'), array('escape' => false, 'class' => 'active')) ?></li>
    <li><?=$this->html->link('Реквизиты<span></span>', array('controller' => 'users', 'action' => 'details'), array('escape' => false)) ?></li>
</ul>