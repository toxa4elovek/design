<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="main" id="subscribe-main-container">

            <nav class="main_nav subscribe-menu clear">
                <?=$this->view()->render(array('element' => 'office/nav'));?>
            </nav>

            <div style="float:left; width: 400px;">
                <section id="balance">
                    <script>
                        var userInfo = {
                            "balance": <?= $this->user->getBalance() ?>,
                            "companyName": '<?= $this->user->getShortCompanyName() ?>',
                            "expirationDate": '<?= $this->user->getSubscriptionExpireDate('d/m/Y') ?>',
                            "isSubscriptionActive": <?php echo (int) $this->user->isSubscriptionActive() ?>,
                            "plan": <?php echo json_encode($this->user->getCurrentPlanData()) ?>
                        };
                        var questions = [
                            {title: 'Как наша компания может заключить с вами договор?', id: '82'},
                            {title: 'Какие способы оплаты мы принимаем?', id: '6'},
                            {title: 'Если я создаю проект от лица компании?', id: '89'}
                        ];
                        var payload = <?php echo json_encode($payments); ?>;
                    </script>
                </section>

                <section id="fund-balance-button"></section>

                <aside id="faq-corporate"></aside>

            </div>

            <section id="new-project"></section>

            <div class="clear"></div>

            <section class="project-search-widget" id="subscribe-project-search"></section>

            <section class="project-search-results" id="subscribe-project-search-results"></section>

        </div><!-- .main -->
    </div><!-- .middle -->

    <script src="/js/jquery-plugins/jquery.numeric.min.js"><script>
</div><!-- .wrapper -->
<?=$this->html->script(array(
    'jcarousellite_1.0.1.js',
    'jquery.timers.js',
    'jquery.simplemodal-1.4.2.js',
    'tableloader.js',
    'jquery.timeago.js',
    'fileuploader',
    'jquery.tooltip.js',
    '/js/jquery-plugins/jquery.numeric.min.js',
    'moment.min.js',
    '/js/bootstrap/bootstrap.min.js',
    '/js/bootstrap/bootstrap-datetimepicker.min.js',
    "/js/mixins/SetIntervalMixin.js",
    "/js/users/subscriber/BalanceBox.js",
    "/js/users/subscriber/FundBalanceButton.js",
    "/js/users/subscriber/NewProjectBox.js",
    "/js/users/subscriber/FaqQuestionRow.js",
    "/js/users/subscriber/FaqCorporateBox.js",
    "/js/users/subscriber/ProjectSearchBar.js",
    '/js/users/subscriber/ProjectSearchResultsTable.js',
    '/js/users/subscriber/ProjectSearchResultsTableRow.js',
    '/js/users/subscriber/ProjectSearchResultsTableHeader.js',
    '/js/users/subscriber.js',
    'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array(
    '/edit',
    '/css/common/buttons.css',
    '/css/common/project-search-results.css',
    '/css/common/project-search-widget.css',
    '/css/bootstrap/bootstrap-datetimepicker-standalone.css',
    '/css/bootstrap/bootstrap-datetimepicker.min.css',
    '/css/users/subscriber.css'
), array('inline' => false))?>