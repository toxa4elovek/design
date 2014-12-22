<?=$this->html->link('<span>Новости</span>', 'http://www.godesigner.ru/news/', array('escape' => false, 'class' => ($this->_request->action == 'feed') ? 'active' : 'ajaxoffice')) ?>
<?=$this->html->link('<span>Мои питчи</span>', 'http://www.godesigner.ru/users/mypitches', array('escape' => false, 'class' => ($this->_request->action == 'mypitches') ? 'active' : 'ajaxoffice')) ?>
<?=$this->html->link('<span>Профиль</span>', 'http://www.godesigner.ru/users/profile', array('escape' => false, 'class' => ($this->_request->action == 'profile') ? 'active' : 'ajaxoffice')) ?>
<?=$this->html->link('<span>Решения</span>', 'http://www.godesigner.ru/users/solutions', array('escape' => false, 'class' => ($this->_request->action == 'solutions') ? 'active' : 'ajaxoffice')) ?>
<?=$this->html->link('<span>Реквизиты</span>', 'http://www.godesigner.ru/users/details', array('escape' => false, 'class' => ($this->_request->action == 'details') ? 'active' : 'ajaxoffice')) ?>
<?=$this->html->link('<span>Пригласи друга</span>', 'http://www.godesigner.ru/users/referal', array('escape' => false, 'class' => ($this->_request->action == 'referal') ? 'active' : 'ajaxoffice')) ?>
