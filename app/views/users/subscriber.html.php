<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="main" id="subscribe-main-container">

            <nav class="main_nav subscribe-menu clear">
                <?=$this->view()->render(array('element' => 'office/nav'));?>
            </nav>

            <div style="float:left; width: 400px;">
                <section id="balance">
                    <script type="text/jsx" src="/js/users/subscriber/BalanceBox.js"></script>
                    <script type="text/jsx">
                    var balance = <?= $this->user->getBalance() ?>;
                    var companyName = '<?= $this->user->getShortCompanyName() ?>';
                    var expirationDate = '<?= $this->user->getSubscriptionExpireDate('d/m/Y') ?>';
                    var isSubscriptionActive = <?php echo (int) $this->user->isSubscriptionActive() ?>;
                    React.render(
                    <BalanceBox balance={balance} isSubscriptionActive={isSubscriptionActive} companyName={companyName} expirationDate={expirationDate}/>,
                        $('#balance')[0]
                    );
                    </script>
                </section>

                <section id="fund-balance-button"></section>

                <script type="text/jsx" src="/js/users/subscriber/FundBalanceButton.js"> </script>
                <script type="text/jsx">
                    React.render(
                        <FundBalanceButton/>,
                        document.getElementById('fund-balance-button')
                    );
                </script>

                <aside id="faq-corporate"></aside>

            </div>

            <section id="new-project"></section>

            <script type="text/jsx" src="/js/users/subscriber/NewProjectBox.js"> </script>
            <script type="text/jsx">
                React.render(
                    <NewProjectBox/>,
                    document.getElementById('new-project')
                );
            </script>

            <div class="clear"></div>

            <section class="project-search-widget" id="subscribe-project-search">
                <table>
                    <tbody><tr>
                        <td>
                            <div id="filterContainer" style="border-radius:4px 4px 4px 4px;border:4px solid #F3F3F3; height:41px;padding-top:0;background-color:white;box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2) inset; width:600px;">
                                <ul class="tags" id="filterbox" style="margin-left: 9px"></ul>
                                <input type="text" id="searchTerm" style="padding-bottom:10px; width:545px; box-shadow:none;line-height:12px; height:13px; padding-top: 3px;margin-left:4px;">
                                <a href="#" id="filterToggle" data-dir="up" style="float:right;"><img style="padding-top:4px;margin-right:1px;" src="/img/filter-arrow-down.png" alt=""></a>
                                <a href="#" id="filterClear"></a>
                            </div>
                        </td>
                        <td>
                            <a href="#" id="goSearch" class="button clean-style-button start-search">Поиск</a>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <div id="filtertab" style="display:none;border-radius:10px;padding-top:14px;margin-left:25px;width: 637px;height:347px;background-color: white;z-index:10;position:absolute;">
                    <ul class="filterlist" style="float:left;width:190px;margin-left:25px;text-transform: none">
                        <li class="first">проекты</li>
                        <li style="width:205px"><a data-group="type" data-value="all" href="#">все проекты с моими решениями</a></li>
                        <!--li><a href="#">по новизне</a></li-->
                        <li style="width:85px"><a data-group="type" data-value="current" href="#">текущие</a></li>
                        <li style="width:85px"><a data-group="type" data-value="finished" href="#">завершенные</a></li>
                        <li style="width:85px"><a data-group="type" data-value="favourites" href="#">отслеживаемые</a></li>
                        <li style="width:140px"><a data-group="type" data-value="completion-stage" href="#">на стадии завершения</a></li>
                        <li style="width:85px"><a data-group="type" data-value="awarded" href="#">награжденные</a></li>
                    </ul>
                    <ul class="filterlist" style="float:left;width:151px;margin-left:45px;text-transform: none">
                        <li class="first">категория</li>
                        <li><a data-group="category" data-value="all" href="#">все</a></li>
                        <li style="width: 175px;"><a data-group="category" data-value="1" href="#">логотип</a></li>
                        <li style="width: 175px;"><a data-group="category" data-value="2" href="#">web-баннер</a></li>
                        <li style="width: 175px;"><a data-group="category" data-value="3" href="#">сайт</a></li>
                        <li style="width: 175px;"><a data-group="category" data-value="4" href="#">флаер</a></li>
                        <li style="width: 175px;"><a data-group="category" data-value="5" href="#">фирменный стиль</a></li>
                        <li style="width: 175px;"><a data-group="category" data-value="6" href="#">страница соцсети</a></li>
                        <li style="width: 175px;"><a data-group="category" data-value="7" href="#">копирайтинг</a></li>
                        <li style="width: 175px;"><a data-group="category" data-value="8" href="#">презентация</a></li>
                        <li style="width: 175px;"><a data-group="category" data-value="9" href="#">иллюстрация</a></li>
                        <li style="width: 175px;"><a data-group="category" data-value="10" href="#">другое</a></li>
                        <li style="width: 175px;"><a data-group="category" data-value="11" href="#">упаковка</a></li>
                        <li style="width: 175px;"><a data-group="category" data-value="12" href="#">реклама</a></li>
                        <li style="width: 175px;"><a data-group="category" data-value="13" href="#">фирменный стиль и логотип</a></li>
                    </ul>
                    <ul class="filterlist" style="float:left;width:160px;margin-left:65px;text-transform: none">
                        <li class="first">гонорар</li>
                        <li style="width:130px"><a data-group="priceFilter" data-value="3" href="#">от 20 000 Р.-</a></li>
                        <li style="width:130px"><a data-group="priceFilter" data-value="2" href="#">от 10 000 - 20 000 Р.-</a></li>
                        <li style="width:130px"><a data-group="priceFilter" data-value="1" href="#">от 5 000 - 10 000 Р.-</a></li>
                    </ul>
                    <div style="clear:both"></div>
                </div>
            </section>

        </div><!-- .main -->
    </div><!-- .middle -->


</div><!-- .wrapper -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.3/react.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.3/JSXTransformer.js"></script>
<script type="text/jsx" src="/js/users/subscriber/FaqQuestionRow.js"> </script>
<script type="text/jsx" src="/js/users/subscriber/FaqCorporateBox.js"> </script>

<?=$this->html->script(array('jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'jquery.tooltip.js', 'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array('/edit', '/css/common/buttons.css', '/css/common/project-search-widget.css', '/css/users/subscriber.css'), array('inline' => false))?>