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
                        $('#balance')[0]
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
            <script type="text/jsx">
                var ProjectSearchBar = new React.createClass({
                    placeholder: "найдите свой  проект по ключевому слову или типу",
                    arrowLinkClick: function(event) {
                        var arrow = $(React.findDOMNode(this.refs.arrowIndicator));
                        var dir = arrow.data('dir');
                        var imageUrl = '/img/filter-arrow-up.png';
                        if ('up' == dir) {
                            arrow.data('dir', 'down');
                        } else {
                            imageUrl = '/img/filter-arrow-down.png';
                            arrow.data('dir', 'up');
                        }
                        arrow.attr('src', imageUrl);
                        event.preventDefault();
                    },
                    searchButtonClick: function(event) {
                        console.log('button click');
                        event.preventDefault();
                    },

                    handleChange: function(event) {
                        console.log('new value');
                        this.setState({value: event.target.value});
                    },
                    /*componentDidMount: function() {
                        React.findDOMNode(this.refs.searchInput).placeholder = "Enter a Date";
                        console.log(React.findDOMNode(this.refs.searchInput))
                    },*/
                    render: function() {
                        return (
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div id="filterContainer" className="search-box-container">
                                                <ul className="tags" id="filterbox"></ul>
                                                <input ref="searchInput" type="text" placeholder={this.placeholder} id="searchTerm" onChange={this.handleChange} className="placeholder" defaultValue="Test test" />
                                                <a href="#" onClick={this.arrowLinkClick} className="arrow-container">
                                                    <img ref="arrowIndicator" className="arrow-down" src="/img/filter-arrow-down.png" data-dir="up" alt="Раскрыть меню" />
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

<?=$this->html->script(array(
    'jcarousellite_1.0.1.js',
    'jquery.timers.js',
    'jquery.simplemodal-1.4.2.js',
    'tableloader.js',
    'jquery.timeago.js',
    'fileuploader',
    'jquery.tooltip.js',
    "/js/users/subscriber/BalanceBox.js",
    "/js/users/subscriber/FundBalanceButton.js",
    "/js/users/subscriber/NewProjectBox.js",
    "/js/users/subscriber/FaqQuestionRow.js",
    "/js/users/subscriber/FaqCorporateBox.js",
    'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array('/edit', '/css/common/buttons.css', '/css/common/project-search-widget.css', '/css/users/subscriber.css'), array('inline' => false))?>