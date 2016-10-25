<?=$this->html->link('<span>Мои проекты</span>', '/users/mypitches', array('escape' => false, 'class' => ($this->_request->action == 'mypitches') ? 'active' : '')) ?>
<?=$this->html->link('<span>Настройки</span>', '/users/profile', array('escape' => false, 'class' => ($this->_request->action == 'profile') ? 'active' : '')) ?>
<?=$this->html->link('<span>Профиль</span>', '/users/preview/' . $this->user->getId(), array('escape' => false, 'class' => ($this->_request->action == 'preview') ? 'active' : '')) ?>
<?php
if($this->user->isSubscriptionActive()):?>
    <?=$this->html->link('<span>Абонентский кабинет</span>', '/users/subscriber', array('escape' => false, 'style' => 'width: 175px;', 'class' => ($this->_request->action == 'subscriber') ? 'active' : '')) ?>
    <?=$this->html->link('<span>Решения</span>', '/users/solutions', array('escape' => false, 'class' => (($this->_request->action == 'solutions') || ($this->_request->action == 'awarded') || ($this->_request->action == 'nominated'))? 'active' : 'ajaxoffice')) ?>

<?php else:?>
    <?=$this->html->link('<span>Решения</span>', '/users/solutions', array('escape' => false, 'class' => (($this->_request->action == 'solutions') || ($this->_request->action == 'awarded') || ($this->_request->action == 'nominated'))? 'active' : 'ajaxoffice')) ?>
    <?=$this->html->link('<span>Тест</span>', '/questions', array('escape' => false, 'class' => ($this->_request->action == 'questions') ? 'active' : '')) ?>
    <?php
endif;?>
<?php
//$referalUrl = '/users/referal';
//if($this->user->getAwardedSolutionNum() > 0) {
    $referalUrl = '/users/subscribers_referal';
//}
?>
<?=$this->html->link('<span>Пригласи друга</span>', $referalUrl, array('escape' => false, 'class' => (in_array($this->_request->action, ['referal', 'subscribers_referal'])) ? 'active' : '')) ?>
