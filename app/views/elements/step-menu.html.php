<ul>
    <li class="first_li"><?=$this->html->link('<span>Обновления</span>', ['controller' => 'users', 'action' => 'feed'], ['escape' => false]) ?></li>
    <li><?=$this->html->link('<span>Мои проекты</span>', ['controller' => 'users', 'action' => 'mypitches'], ['escape' => false]) ?></li>
    <li><?=$this->html->link('<span>Профиль</span>', 'https://godesigner.ru/users/preview/' . $this->user->getId(), ['escape' => false, 'class' => ($this->_request->action == 'preview') ? 'active' : '']) ?></li>
    <li><?=$this->html->link('<span>Решения</span>', ['controller' => 'users', 'action' => 'solutions'], ['escape' => false, 'class' => 'active']) ?></li>
    <li><?=$this->html->link('<span>Тест</span>', 'https://godesigner.ru/questions', ['escape' => false, 'class' => ($this->_request->action == 'questions') ? 'active' : '']) ?></li>
    <li>
        <?php
        //$referalUrl = '/users/referal';
        //if($this->user->getAwardedSolutionNum() > 0) {
            $referalUrl = '/users/subscribers_referal';
        //}
        ?>
        <?=$this->html->link('<span>Пригласи друга</span>', 'https://godesigner.ru' . $referalUrl, ['escape' => false, 'class' => (in_array($this->_request->action, ['referal', 'subscribers_referal'])) ? 'active' : '']) ?></li>
</ul>