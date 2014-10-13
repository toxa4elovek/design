<div class="wrapper login">

    <?=$this->view()->render(array('element' => 'header'), array('header' => 'header2', 'logo' => 'logo'))?>

    <div class="middle">
        <div class="middle_inner conteiners" style="margin-top: 0px;padding-left: 0px;">
            <input type="hidden" value="<?=$this->user->getId()?>" id="user_id">
        <section>
            <div class="menu" style="background:none;border:none;width:857px;margin-left:63px;margin-top:0;">
            <nav class="main_nav clear" style="width:832px;">
                <?=$this->view()->render(array('element' => 'office/nav'));?>
            </nav>
            </div>
        </section>
        <section class="js-participate">
            <div class="title" style="padding-left: 0px; margin-left: 75px;">
                <span>Участие</span>
            </div>
            <div class="title" id="my-placeholder" style="padding-left: 0px; margin-left: 75px;background: none; display:none;">
                <p class="largest-header">выбирайте <a href="/pitches">питчи</a><br/> и зарабатывайте деньги!</p>
            </div>
            <table class="all-pitches" id="my-table">
                <tbody id="table-content">

                </tbody>
            </table>
            <div class="foot-content">
                <div class="page-nambe-nav" id="topnav">
                </div>
            </div>
        </section>
        <section class="js-favourites">
            <div class="title" style="padding-left: 0px; margin-left: 75px;">
                <span>Добавленные</span>
            </div>
            <div class="title" id="fav-placeholder" style="background: none; display:none;">
                <p class="largest-header">Добавьте понравившийся <a href="/pitches">питч</a><br/> в список, и вы сможете вернуться<br/> к нему в удобное время</p>
                <p class="align-center"><img  src="/img/mypitchesempty.png" alt="" width="600" height="200"></p>
            </div>

            <table class="all-pitches" id="fav-table">
                <tbody id="faves">
                </tbody>
            </table>
            <div class="foot-content">
                <div class="page-nambe-nav" id="bottomnav">
                </div>
            </div>
        </section>
        </div><!-- /middle_inner -->
        <div id="popup-final-step" class="popup-final-step" style="display:none">
            <h3>Вы уверены, что хотите удалить этот питч?</h3>
            <p>Эта процедура является окончательной, и в дальнейшем вы не сможете изменить свое решение. Нажав "Да, одобряю", вы подтверждаете, что хотите удалить его из списка. Убедитесь, что это черновой вариант и не ждет поступления оплаты на наш счет. За справкой <a href="/pages/contacts">обратитесь к нам</a>.</p>
            <div class="final-step-nav wrapper" style="margin-top: 180px;"><input type="submit" class="button second popup-close" value="Нет, отменить"> <input type="submit" class="button" id="confirmDelete" value="Да, одобряю"></div>
        </div>
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->
    </div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->view()->render(array('element' => 'popups/mypitches_popup'));?>
<?=$this->html->script(array('jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'jquery.tooltip.js', 'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array('/main2.css', '/pitches2.css', '/edit','/view', '/messages12', '/pitches12', '/win_steps1.css', '/win_steps2_final3.css', '/portfolio.css',), array('inline' => false))?>