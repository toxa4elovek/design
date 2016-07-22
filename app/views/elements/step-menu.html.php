<ul>
    <li class="first_li"><?=$this->html->link('<span>Обновления</span>', array('controller' => 'users', 'action' => 'feed'), array('escape' => false)) ?></li>
    <li><?=$this->html->link('<span>Мои проекты</span>', array('controller' => 'users', 'action' => 'mypitches'), array('escape' => false)) ?></li>
    <li><?=$this->html->link('<span>Профиль</span>', 'https://www.godesigner.ru/users/preview/' . $this->user->getId(), array('escape' => false, 'class' => ($this->_request->action == 'preview') ? 'active' : '')) ?></li>
    <li><?=$this->html->link('<span>Решения</span>', array('controller' => 'users', 'action' => 'solutions'), array('escape' => false, 'class' => 'active')) ?></li>
    <li><?=$this->html->link('<span>Тест</span>', 'https://www.godesigner.ru/questions', array('escape' => false, 'class' => ($this->_request->action == 'questions') ? 'active' : '')) ?></li>
    <li>
        <?php
        $referalUrl = '/users/referal';
        if($this->user->getAwardedSolutionNum() > 0) {
            $referalUrl = '/users/subscribers_referal';
        }?>
        <?=$this->html->link('<span>Пригласи друга</span>', 'https://www.godesigner.ru' . $referalUrl, array('escape' => false, 'class' => (in_array($this->_request->action, ['referal', 'subscribers_referal'])) ? 'active' : '')) ?></li>
</ul>