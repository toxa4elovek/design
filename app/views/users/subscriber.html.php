<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo'))?>

    <div class="middle">
        <div class="main" id="subscribe-main-container">

            <nav class="main_nav subscribe-menu clear">
                <?=$this->view()->render(array('element' => 'office/nav'));?>
            </nav>

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

            <section id="new-project"></section>

            <script type="text/jsx" src="/js/users/subscriber/NewProjectBox.js"> </script>
            <script type="text/jsx">
                React.render(
                    <NewProjectBox/>,
                    document.getElementById('new-project')
                );
            </script>

            <section id="fund-balance-button"></section>

            <aside id="faq-corporate"></aside>

            <script type="text/jsx" src="/js/users/subscriber/FundBalanceButton.js"> </script>
            <script type="text/jsx">
                React.render(
                    <FundBalanceButton/>,
                    document.getElementById('fund-balance-button')
                );
            </script>

            <div class="clear"></div>
        </div><!-- .main -->
    </div><!-- .middle -->


</div><!-- .wrapper -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.3/react.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/react/0.13.3/JSXTransformer.js"></script>
<script type="text/jsx" src="/js/users/subscriber/FaqQuestionRow.js"> </script>
<script type="text/jsx" src="/js/users/subscriber/FaqCorporateBox.js"> </script>

<?=$this->html->script(array('jcarousellite_1.0.1.js', 'jquery.timers.js', 'jquery.simplemodal-1.4.2.js', 'tableloader.js', 'jquery.timeago.js', 'fileuploader', 'jquery.tooltip.js', 'users/office.js'), array('inline' => false))?>
<?=$this->html->style(array('/edit', '/css/common/buttons.css', '/css/users/subscriber.css'), array('inline' => false))?>