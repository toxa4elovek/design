<?=$this->html->link('<span>Обновления</span>', array('controller' => 'users', 'action' => 'feed'), array('escape' => false, 'class' => ($this->_request->action == 'feed') ? 'active' : 'ajaxoffice')) ?>
<?=$this->html->link('<span>Мои питчи</span>', array('controller' => 'users', 'action' => 'mypitches'), array('escape' => false, 'class' => ($this->_request->action == 'mypitches') ? 'active' : 'ajaxoffice')) ?>
<?=$this->html->link('<span>Профиль</span>', array('controller' => 'users', 'action' => 'profile'), array('escape' => false, 'class' => ($this->_request->action == 'profile') ? 'active' : 'ajaxoffice')) ?>
<?=$this->html->link('<span>Решения</span>', array('controller' => 'users', 'action' => 'solutions'), array('escape' => false, 'class' => ($this->_request->action == 'solutions') ? 'active' : 'ajaxoffice')) ?>
<?=$this->html->link('<span>Реквизиты</span>', array('controller' => 'users', 'action' => 'details'), array('escape' => false, 'class' => ($this->_request->action == 'details') ? 'active' : 'ajaxoffice')) ?>
<?=$this->html->link('<span>Пригласи друга</span>', array('controller' => 'users', 'action' => 'referal'), array('escape' => false, 'class' => ($this->_request->action == 'referal') ? 'active' : 'ajaxoffice')) ?>
