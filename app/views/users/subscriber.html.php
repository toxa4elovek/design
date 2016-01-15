<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array(
        'logo' => 'logo',
        'header' => 'header2'))
    ?>

    <div class="middle">
        <div class="main" id="subscribe-main-container">

            <nav class="main_nav subscribe-menu clear">
                <?=$this->view()->render(array('element' => 'office/nav'));?>
            </nav>

            <script>
                var payload = <?php echo json_encode($payments); ?>;
                var userInfo = {
                    "balance": <?= $this->user->getBalance() ?>,
                    "companyName": '<?= $this->user->getShortCompanyName() ?>',
                    "fullCompanyName": '<?= $this->user->getFullCompanyName() ?>',
                    "expirationDate": '<?= $this->user->getSubscriptionExpireDate('d/m/Y') ?>',
                    "isSubscriptionActive": <?php echo (int) $this->user->isSubscriptionActive() ?>,
                    "plan": <?php echo json_encode($this->user->getCurrentPlanData()) ?>
                };
                var questions = [
                    {title: 'FAQ по годовому обслуживанию', id: '104'},
                    {title: 'Как работает годовое обслуживание?', id: '102'},
                    {title: 'Финальные правки: завершительный этап', id: '63'}
                ];
                var settings = {
                    "isFilterListActive" : false
                };
                var defaultDate = '<?= $defaultFinishDate ?>';
                var data = {"payload": payload, "userInfo": userInfo, "questions": questions, "settings": settings};
            </script>

            <div id="pageMount"></div>


        </div><!-- .main -->
    </div><!-- .middle -->

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
    '/js/tableloader.js',
    '/js/ru.js',
    '/js/polyfills/browser-polyfill.min.js',
    '/js/polyfills/fetch.js',
    '/js/bootstrap/bootstrap-datetimepicker.min.js',
    '/js/users/subscriber/SubscriberPage.js',
    "/js/users/subscriber/BalanceBox.js",
    "/js/users/subscriber/FundBalanceButton.js",
    "/js/users/subscriber/NewProjectBox.js",
    "/js/users/subscriber/FaqQuestionRow.js",
    "/js/users/subscriber/FaqCorporateBox.js",
    "/js/users/subscriber/ProjectSearch.js",
    "/js/users/subscriber/ProjectSearchBar.js",
    '/js/users/subscriber/ProjectSearchResultsTable.js',
    '/js/users/subscriber/ProjectSearchResultsTableRow.js',
    '/js/users/subscriber/ProjectSearchResultsTableHeader.js',
    '/js/users/subscriber/ProjectSearchSubscriberFilters.js',
    '/js/users/subscriber.js',
    'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array(
    '/edit',
    '/css/common/buttons.css',
    '/css/common/project-search-results.css',
    '/css/common/project-search-widget.css',
    '/css/bootstrap/bootstrap.css',
    '/css/bootstrap/bootstrap-datetimepicker.min.css',
    '/css/bootstrap/bootstrap-datetimepicker-standalone.css',
    '/css/users/subscriber.css'
), array('inline' => false))?>