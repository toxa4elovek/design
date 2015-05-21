<ul>
    <li class="first_li"><?=$this->html->link('<span>Обновления</span>', array('controller' => 'users', 'action' => 'feed'), array('escape' => false)) ?></li>
    <li><?=$this->html->link('<span>Мои проекты</span>', array('controller' => 'users', 'action' => 'mypitches'), array('escape' => false)) ?></li>
    <li><?=$this->html->link('<span>Профиль</span>', 'http://www.godesigner.ru/users/preview/' . $this->user->getId(), array('escape' => false, 'class' => ($this->_request->action == 'preview') ? 'active' : '')) ?></li>
    <li><?=$this->html->link('<span>Решения</span>', array('controller' => 'users', 'action' => 'solutions'), array('escape' => false, 'class' => 'active')) ?></li>
    <li><?=$this->html->link('<span>Тест</span>', 'http://www.godesigner.ru/questions', array('escape' => false, 'class' => ($this->_request->action == 'questions') ? 'active' : '')) ?></li>
    <li><?=$this->html->link('<span>Пригласи друга</span>', 'http://www.godesigner.ru/users/referal', array('escape' => false, 'class' => ($this->_request->action == 'referal') ? 'active' : '')) ?></li>
</ul>