
    <div class="wrapper auth login">

        <?=$this->view()->render(array('element' => 'header'))?>

        <div class="middle">
            <div class="main">
                <section>
                    <h2> Личный кабинет</h2>

                    <p><?= $this->session->read('user.first_name')?> 
<?= $this->session->read('user.last_name')?>
                    </p>
                    <p><?= $this->session->read('user.email')?>
                    </p>
                    <a href="/users/suicide">удалить себя</a>
                </section>
            </div>
        </div><!-- .middle -->

    </div><!-- .wrapper -->