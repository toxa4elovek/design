<script>
    var pitchNumber = <?php echo $pitch->id; ?>;
    var currentUserId = <?php echo (int) $this->session->read('user.id'); ?>;
    var isCurrentAdmin = <?php echo ((int)$this->session->read('user.isAdmin') || \app\models\User::checkRole('admin')) ? 1 : 0 ?>;
    var currentUserName = '<?=$this->nameInflector->renderName($this->session->read('user.first_name'), $this->session->read('user.last_name'))?>';
    var isCurrentExpert = <?php
        if($this->session->read('user.id')):
            echo (in_array($this->session->read('user.id'), $expertsIds)) ? 1 : 0;
        else:
            echo 0;
        endif;
        ?>;
    var isClient = <?php echo ((int)$this->session->read('user.id') == $pitch->user->id) ? 1 : 0; ?>;
</script>