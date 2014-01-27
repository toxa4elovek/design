<script>
    var pitchNumber = <?php echo $pitch->id; ?>;
    var currentUserId = <?= (int) $this->user->getId(); ?>;
    var currentUserName = '<?=$this->user->getFormattedName()?>';
    var isCurrentAdmin = <?php echo $this->user->isAdmin() ? 1 : 0 ?>;
    var isCurrentExpert = <?php echo $this->user->isExpert() ? 1 : 0 ?>;
    var isClient = <?php echo ($this->user->isPitchOwner($pitch->user->id)) ? 1 : 0; ?>;
</script>