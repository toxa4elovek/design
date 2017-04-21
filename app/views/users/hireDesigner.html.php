<div class="wrapper">

    <?=$this->view()->render(['element' => 'header'], ['logo' => 'logo'])?>

    <div class="middle">
        <div class="middle_inner user_view" style="min-height:330px; padding-left: 70px; padding-right: 0; padding-top: 95px;">

            <h2>Создание проекта «Один на один»</h2>
            <aside id="receipt-container"></aside>
            <section class="lp-rules">
                <div class="users">
                    <a href="/users/view/<?=$this->user->getId()?>">Вы</a>
                    <a class="avatar-link" href="/users/view/<?=$this->user->getId()?>">
                        <?= $this->Avatar->show(['id' => $this->user->getId()])?>
                    </a>
                    <img style="margin-top: 7px;" src="/img/users/hireDesigner/arrows.png" alt="нанять дизайнера" />
                    <a class="avatar-link" href="/users/view/<?=$designer->id?>">
                        <?= $this->Avatar->show(['id' => $designer->id])?>
                    </a>
                    <a href="/users/view/<?=$designer->id?>"><?= $this->NameInflector->renderName($designer->first_name, $designer->last_name)?></a>
                </div>
                <p>Мы холдируем, но не списываем средства с карты, пока дизайнер не подтвердит согласие на работу в течение 3-х дней; мы переведем ему гонорар, только когда вы утвердите исходники. Подробнее в <a href="/answers/view/115" target="_blank">Регламенте проведения проекта «Один на один»</a>.</p>
            </section>
            <section class="lp-form">
                <form method="post" action="/pitches/create1on1Project/<?=$designer->id?>/">
                    <label style="display:block;">1. Гонорар (от 2000 рублей)<br/><input type="text" required name="price" value="<?= $projectData['award']?>" pattern="^[0-9]+$" placeholder="8000"/></label>
                    <label style="display:block;">2. Дней на исполнение <br/><input type="text" required name="days" value="<?= $projectData['days']?>" pattern="^[0-9]+$" placeholder="5"/></label>
                    <label style="display:block;">3. Опишите задачу<a href="/answers/view/4" class="hint" target="_blank">Что указывать в брифе?</a><br/><textarea name="description" required><?= $projectData['description']?></textarea></label>
                    <div class="tos-container supplement" style="padding-left: 2px; margin-bottom: 20px; position: relative;">
                        <label><input type="checkbox" name="tos" required style="vertical-align: middle; margin-right: 5px;">Я прочитал(а) и выражаю безусловное согласие с условиями настоящего <a target="_blank" href="/docs/dogovor_oferta_04_2017.pdf" style="text-decoration: none;">договора публичной оферты</a>.</label>
                    </div>
                    <input class="clear button" type="submit" value="Оплатить" style="margin-bottom: 17px;">
                </form>
                <script>
                  var payload = {
                    "receipt": <?php echo json_encode($receipt) ?>
                  }
                </script>
            </section>
        </div>
    </div>

</div>
<?=$this->html->style([
    '/cabinet',
    '/portfolio',
    '/messages12',
    '/css/common/receipt.css',
    '/css/users/hireDesigner',
    '/js/slick/slick.css',
    '/js/slick/slick-theme.css'
], ['inline' => false])?>
<?=$this->html->script([
    'common/receipt/ReceiptLine.js',
    'common/receipt/ReceiptTotal.js',
    'common/receipt/Receipt.js',
    'jquery-ui-1.11.4.min.js',
    '/js/slick/slick.min.js',
    'users/hireDesigner.js'
], ['inline' => false])?>