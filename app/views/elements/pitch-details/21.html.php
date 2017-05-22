<?php
$details = unserialize($pitch->specifics);
?>
<input type="hidden" id="logo_properties" data-props="<?php echo urlencode(json_encode($details["audience"])); ?>">

<div class="groupc">

    <?php if (!empty($details['qualities'])):?>
        <h2 class="blueheading">Какие 3 основных качества нужно донести до аудитории?</h2>
        <p class="regular"><?=$this->brief->deleteHtmlTagsAndInsertHtmlLinkInText($details['qualities'])?></p>
    <?php endif;?>

</div>