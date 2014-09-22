<script>
    var showSocialPopup = false;
    var needSocialWrite = false;
    var showMobilePopup = false;
    var showMailPopup = true;
    <?php $showSocialPopup = false; ?>
    <?php if ($this->user->getId()):?>
    <?php if (!$this->user->isSocialNetworkUser()):?>
    <?php if (!isset($_COOKIE['scl']) || $_COOKIE['scl'] == ''):?>
    <?php setcookie('scl', '1', strtotime('+6 month'), '/');?>
    needSocialWrite = 1;
    <?php elseif ($_COOKIE['scl'] == '1'):?>
    <?php setcookie('scl', '2', strtotime('+6 month'), '/');?>
    needSocialWrite = 2;
    showSocialPopup = true;
    <?php $showSocialPopup = true; ?>
    <?php else:?>
    needSocialWrite = 2;
    <?php endif;?>
    <?php elseif ($this->user->isSocialNetworkUser()):?>
    <?php if (!isset($_COOKIE['scl']) || $_COOKIE['scl'] == ''):?>
    <?php setcookie('scl', '2', strtotime('+6 month'), '/');?>
    needSocialWrite = 2;
    showSocialPopup = true;
    <?php $showSocialPopup = true; ?>
    <?php elseif ($_COOKIE['scl'] == '1'):?>
    <?php setcookie('scl', '2', strtotime('+6 month'), '/');?>
    needSocialWrite = 2;
    showSocialPopup = true;
    <?php $showSocialPopup = true; ?>
    <?php else:?>
    needSocialWrite = 2;
    <?php endif;?>
    <?php else:?>
    <?php setcookie('scl', '2', strtotime('+6 month'), '/');?>
    <?php endif?>
    <?php else:?>
    <?php if (!isset($_COOKIE['scl']) || $_COOKIE['scl'] == ''):?>
    <?php setcookie('scl', '1', strtotime('+6 month'), '/');?>
    <?php elseif ($_COOKIE['scl'] == '1'):?>
    <?php setcookie('scl', '2', strtotime('+6 month'), '/');?>
    showSocialPopup = true;
    <?php $showSocialPopup = true; ?>
    <?php endif;?>
    <?php endif?>

    <?php if ($showSocialPopup === false): ?>
    <?php if (!isset($_COOKIE['mbl']) || $_COOKIE['mbl'] == ''): ?>
    <?php setcookie('mbl', '1', strtotime('+6 month'), '/'); ?>
    showMobilePopup = true;
    <?php endif; ?>
    <?php endif; ?>
    <?php if ($this->user->getId() && strtotime($this->user->getCreatedDate()) < strtotime('2014-09-01') && (!isset($_COOKIE['mail']) || $_COOKIE['mail'] == '')): ?>
    <?php setcookie('mail', '1', strtotime('+6 month'), '/');?>
    showMailPopup = true;
    <?php endif; ?>
</script>