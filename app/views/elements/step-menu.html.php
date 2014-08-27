<ul>
    <li class="first_li"><?=$this->html->link('<span>Обновления</span>', array('controller' => 'users', 'action' => 'office'), array('escape' => false)) ?></li>
    <li><?=$this->html->link('<span>Мои питчи</span>', array('controller' => 'users', 'action' => 'mypitches'), array('escape' => false)) ?></li>
    <!--li><a href="">Сообщения</a></li-->
    <li><a href="#">Профиль</a></li>
    <li><?=$this->html->link('<span>Решения</span>', array('controller' => 'users', 'action' => 'solutions'), array('escape' => false, 'class' => 'active')) ?></li>
    <li><?=$this->html->link('<span>Реквизиты</span>', array('controller' => 'users', 'action' => 'details'), array('escape' => false)) ?></li>
</ul>