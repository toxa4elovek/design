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
            </section>
            <script type="text/jsx">
                var ProjectSearchBar = new React.createClass({
                    arrowLinkClick: function(event) {
                        console.log('arrow click');
                        var arrow = $(event.target);
                        var arrowLink = arrow.parent();
                        var dir = arrowLink.data('dir');
                        var imageUrl = '/img/filter-arrow-up.png';
                        if ('up' == dir) {
                            arrowLink.data('dir', 'down');
                        } else {
                            imageUrl = '/img/filter-arrow-down.png';
                            arrowLink.data('dir', 'up');
                        }
                        arrow.attr('src', imageUrl);
                        event.preventDefault();
                    },
                    searchButtonClick: function(event) {
                        console.log('button click');
                        event.preventDefault();
                    },
                    render: function() {
                        return (
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div id="filterContainer" className="search-box-container">
                                                <ul className="tags" id="filterbox"></ul>
                                                <input type="text" placeholder="найдите свой  проект по ключевому слову или типу" id="searchTerm" />
                                                <a href="#" onClick={this.arrowLinkClick} id="filterToggle" className="arrow-container" data-dir="up">
                                                    <img className="arrow-down" src="/img/filter-arrow-down.png" alt="Раскрыть меню" />
                                                </a>
                                                <a href="#" id="filterClear"></a>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="#" onClick={this.searchButtonClick} className="button clean-style-button start-search">Поиск</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        )
                    }
                });

                React.render(
                    <ProjectSearchBar/>,
                    document.getElementById('subscribe-project-search')
                );
            </script>

        </div><!-- .main -->
    </div><!-- .middle -->


</div><!-- .wrapper -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.3/react.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.3/JSXTransformer.js"></script>
<script type="text/jsx" src="/js/users/subscriber/FaqQuestionRow.js"> </script>
<script type="text/jsx" src="/js/users/subscriber/FaqCorporateBox.js"> </script>

<?=$this->html->script(array('jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'jquery.tooltip.js', 'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array('/edit', '/css/common/buttons.css', '/css/common/project-search-widget.css', '/css/users/subscriber.css'), array('inline' => false))?>