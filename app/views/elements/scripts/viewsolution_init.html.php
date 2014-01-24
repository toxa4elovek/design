<script>
    var pitchNumber = <?php echo $pitch->id; ?>;
    var currentUserId = <?php echo (int) $this->session->read('user.id'); ?>;
    var isCurrentAdmin = <?php echo $this->user->isAdmin() ? 1 : 0 ?>;
    var currentUserName = '<?=$this->nameInflector->()?>';
    var isCurrentExpert = <?php echo $this->user->isExpert() ? 1 : 0 ?>;
    var isClient = <?php echo ((int)$this->session->read('user.id') == $pitch->user->id) ? 1 : 0; ?>;
</script>