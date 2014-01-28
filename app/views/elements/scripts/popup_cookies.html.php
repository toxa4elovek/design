<script>
    var showSocialPopup = false;
    var needSocialWrite = false;
    <?php if ($this->user->getId()):?>
    <?php if (!$this->user->isSocialNetworkUser()):?>
    <?php if (!isset($_COOKIE['scl']) || $_COOKIE['scl'] == ''):?>
    <?php setcookie('scl', '1', strtotime('+6 month'), '/');?>
    needSocialWrite = 1;
    <?php elseif ($_COOKIE['scl'] == '1'):?>
    <?php setcookie('scl', '2', strtotime('+6 month'), '/');?>
    needSocialWrite = 2;
    showSocialPopup = true;
    <?php else:?>
    needSocialWrite = 2;
    <?php endif;?>
    <?php elseif ($this->user->isSocialNetworkUser()):?>
    <?php if (!isset($_COOKIE['scl']) || $_COOKIE['scl'] == ''):?>
    <?php setcookie('scl', '2', strtotime('+6 month'), '/');?>
    needSocialWrite = 2;
    showSocialPopup = true;
    <?php elseif ($_COOKIE['scl'] == '1'):?>
    <?php setcookie('scl', '2', strtotime('+6 month'), '/');?>
    needSocialWrite = 2;
    showSocialPopup = true;
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
    <?php endif;?>
    <?php endif?>
</script>