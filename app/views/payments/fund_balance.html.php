<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('header' => 'header2'))?>

    <div class="middle add-pitch">

        <div class="main">
            <div>

                <div id="test_button"></div>
                <script type="text/jsx">
                    var FillBalanceTestButton = new React.createClass({
                        fillBalance: function(e) {
                            e.preventDefault();
                            $.post('/users/fill_balance.json', {"amount": this.props.sum})
                                .done(function(response) {
                            }.bind(this));
                        },
                        render: function() {
                            return(<a href="#" className="button" onClick={this.fillBalance} data-sum={this.props.sum}>Пополнить баланс на 10000</a>)
                        }
                    });

                    React.render(
                        <FillBalanceTestButton sum="10000"/>,
                        document.getElementById('test_button')
                    );
                </script>

                <div class="g_line"></div>
            </div>
        </div><!-- .main -->

    </div><!-- .middle -->

<?=$this->html->script(array('jquery-ui-1.8.17.custom.min.js', 'pitches/addon.js?' . mt_rand(100, 999), 'jquery.numeric','jquery.iframe-transport.js', 'jquery.fileupload.js', 'jquery.simplemodal-1.4.2.js', 'jquery.tooltip.js', 'popup.js', 'jquery.damnUploader.js'), array('inline' => false))?>
<?= $this->html->style(array('/brief', '/step3'), array('inline' => false))?>