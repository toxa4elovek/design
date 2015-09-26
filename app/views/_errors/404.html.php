<div class="wrapper">


    <?=$this->view()->render(array('element' => 'header'), array())?>

    <div id="e404">
        <div id="e404_text_1">Ой!</div>
        <div id="e404_text_2">404</div>
        <?php if (0 === strpos($info['exception']->getMessage(), 'Public:')):?>
        <div><?php echo str_replace('Public:', '', $info['exception']->getMessage());?></div>
        <?php endif;?>
        <div id="e404_button"><a href="/">на главную</a></div>

    </div>
</div><!-- .wrapper -->
<?=$this->html->style(array('/404'), array('inline' => false))?>