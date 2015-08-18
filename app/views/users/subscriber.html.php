<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="main" id="subscribe-main-container">

            <nav class="main_nav subscribe-menu clear">
                <?=$this->view()->render(array('element' => 'office/nav'));?>
            </nav>

            <div style="float:left; width: 400px;">
                <section id="balance">
                    <script type="text/jsx">
                    var balance = <?= $this->user->getBalance() ?>;
                    var companyName = '<?= $this->user->getShortCompanyName() ?>';
                    var expirationDate = '<?= $this->user->getSubscriptionExpireDate('d/m/Y') ?>';
                    var isSubscriptionActive = <?php echo (int) $this->user->isSubscriptionActive() ?>;
                    React.render(
                        <BalanceBox balance={balance} isSubscriptionActive={isSubscriptionActive} companyName={companyName} expirationDate={expirationDate}/>,
                        document.getElementById('balance')
                    );
                    </script>
                </section>

                <section id="fund-balance-button"></section>

                <script type="text/jsx">
                    React.render(
                        <FundBalanceButton/>,
                        document.getElementById('fund-balance-button')
                    );
                </script>

                <aside id="faq-corporate"></aside>

            </div>

            <section id="new-project"></section>

            <script type="text/jsx">
                React.render(
                    <NewProjectBox/>,
                    document.getElementById('new-project')
                );
            </script>

            <div class="clear"></div>

            <section class="project-search-widget" id="subscribe-project-search"></section>

            <section class="project-search-results" id="subscribe-project-search-results">

            </section>

            <script type="text/jsx">
                var ProjectSearchResultsTable = new React.createClass({
                    render: function() {
                        return (
                            <table id="myprojects" className="project-search-table">
                                <ProjectSearchResultsTableHeader/>
                                <ProjectSearchResultsTableRow/>
                            </table>
                        )
                    }
                });

                var ProjectSearchResultsTableRow = new React.createClass({
                    render: function() {
                        return (
                            <tr data-id="105783" className="odd">
                                <td className="td-title">
                                    <a href="/pitches/view/105783" class="newpitchfont">Логотип для проекта PublicDictionary</a>
                                </td>
                                <td className="idea">73</td>
                                <td className="pitches-time">6 дней 7 часов</td>
                                <td className="price">-20 000 Р.-</td>
                            </tr>
                        )
                    }
                });

                var ProjectSearchResultsTableHeader = new React.createClass({
                    render: function() {
                        return (
                            <thead>
                                <tr>
                                    <td>
                                        <a href="#" id="sort-title" className="sort-link" data-dir="asc">название</a>
                                    </td>
                                    <td>
                                        <a href="#" id="sort-ideas_count" className="sort-link" data-dir="asc">идей</a>
                                    </td>
                                    <td>
                                        <a href="#" id="sort-finishDate" className="sort-link" data-dir="asc">срок/статус</a>
                                    </td>
                                    <td>
                                        <a href="#" id="sort-price" className="sort-link" data-dir="asc">Цена</a>
                                    </td>
                                </tr>
                            </thead>
                        )
                    }
                });

                React.render(
                    <ProjectSearchResultsTable/>,
                    document.getElementById('subscribe-project-search-results')
                );
            </script>

            <script type="text/jsx">
                React.render(
                    <ProjectSearchBar/>,
                    document.getElementById('subscribe-project-search')
                );
            </script>

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
    "/js/mixins/SetIntervalMixin.js",
    "/js/users/subscriber/BalanceBox.js",
    "/js/users/subscriber/FundBalanceButton.js",
    "/js/users/subscriber/NewProjectBox.js",
    "/js/users/subscriber/FaqQuestionRow.js",
    "/js/users/subscriber/FaqCorporateBox.js",
    "/js/users/subscriber/ProjectSearchBar.js",
    'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array(
    '/edit',
    '/css/common/buttons.css',
    '/css/common/project-search-results.css',
    '/css/common/project-search-widget.css',
    '/css/users/subscriber.css'
), array('inline' => false))?>