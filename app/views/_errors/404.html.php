<div class="wrapper">


    <?=$this->view()->render(array('element' => 'header'), array())?>
    <?php
    $message = '';
    if (0 === strpos($info['exception']->getMessage(), 'Public:')):
        $message = str_replace('Public:', '', $info['exception']->getMessage());
    endif;?>
    <div id="e404">
        <?php if(!preg_match('/Решение было удалено автором/', $message)):?>
            <div id="e404_text_1">Ой!</div>
            <div id="e404_text_2">404</div>
            <?php if (!empty($message)):?>
            <div><?php echo $message;?></div>
            <?php endif;?>
            <div id="e404_button"><a href="/">на главную</a></div>
        <?php else:?>
            <div id="e404_text_1" style="font-size: 50px;"><?=$message?></div>
            <div id="e404_button" style="margin-top: 180px;"><a href="/">на главную</a></div>
        <?php endif?>


    </div>
</div><!-- .wrapper -->
<?=$this->html->style(array('/404'), array('inline' => false))?>