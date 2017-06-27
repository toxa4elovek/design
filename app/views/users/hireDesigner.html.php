<div class="wrapper">

    <?=$this->view()->render(['element' => 'header'], ['logo' => 'logo'])?>

    <div class="middle">
        <div class="middle_inner user_view" style="min-height:330px; padding-left: 70px; padding-right: 0; padding-top: 95px;">

            <h2>Создание проекта «Один на один»</h2>
            <aside id="receipt-container"></aside>
            <section class="lp-rules">
                <div class="users">
                    <a href="/users/view/<?=$client->id?>">
                        <?php if($this->user->getId() === $client->id):?>
                            Вы
                        <?php else:?>
                            <?= $this->NameInflector->renderName($client->first_name, $client->last_name)?>
                        <?php endif?>
                    </a>
                    <a class="avatar-link" href="/users/view/<?=$client->id?>">
                        <?= $this->Avatar->show(['id' => $client->id])?>
                    </a>
                    <img style="margin-top: 7px;" src="/img/users/hireDesigner/arrows.png" alt="нанять дизайнера" />
                    <a class="avatar-link" href="/users/view/<?=$designer->id?>">
                        <?= $this->Avatar->show(['id' => $designer->id])?>
                    </a>
                    <a href="/users/view/<?=$designer->id?>">
                        <?php if($this->user->getId() === $designer->id):?>
                            Вы
                        <?php else:?>
                            <?= $this->NameInflector->renderName($designer->first_name, $designer->last_name)?>
                        <?php endif?>
                    </a>
                </div>
                <p>Мы холдируем, но не списываем средства с карты, пока дизайнер не подтвердит согласие на работу в течение 3-х дней; мы переведем ему гонорар, только когда вы утвердите исходники. Подробнее в <a href="/answers/view/115" target="_blank">Регламенте проведения проекта «Один на один»</a>.</p>
            </section>
            <?php if((int) $selectedProject->billed === 0):?>
            <section class="lp-form">
                <form method="post" action="/pitches/create1on1Project/<?=$designer->id?>/">
                    <label style="display:block;">1. Гонорар (от 2000 рублей)<br/><input type="text" required name="price" value="<?= $projectData['award']?>" pattern="^[0-9]+$" placeholder="8000"/></label>
                    <label style="display:block;">2. Дней на исполнение <br/><input type="text" required name="days" value="<?= $projectData['days']?>" pattern="^[0-9]+$" placeholder="5"/></label>
                    <label style="display:block;">3. Опишите задачу<a href="/answers/view/4" class="hint" target="_blank">Что указывать в брифе?</a><br/><textarea name="description" required><?= $projectData['description']?></textarea></label>
                    <div class="tos-container supplement" style="padding-left: 2px; margin-bottom: 20px; position: relative;">
                        <label><input type="checkbox" name="tos" required style="vertical-align: middle; margin-right: 5px;">Я прочитал(а) и выражаю безусловное согласие с условиями настоящего <a target="_blank" href="/docs/dogovor_oferta_06_2017.pdf" style="text-decoration: none;">договора публичной оферты</a>.</label>
                    </div>
                    <input class="clear button" type="submit" value="Оплатить" style="margin-bottom: 17px;">
                </form>
            </section>
                <script>
                  var payload = {
                    "receipt": <?php echo json_encode($receipt) ?>
                  }
                </script>
            <?php endif?>
            <?php if((int) $selectedProject->billed === 1):?>
                <section class="pitch-comments isField">
                    <div class="separator" style="width: 810px; margin-left: 30px; margin-top: 25px;"></div>
                    <section class=""  data-type="client">
                        <div class="message_info2">
                            <a href="/users/view/<?=$client->id?>" target="_blank">
                                <?= $this->Avatar->show(['id' => $client->id])?>
                            </a>
                            <a href="/users/view/<?=$client->id?>" data-comment-id="183234" data-comment-to="Алина П.">
                                <span><?= $this->NameInflector->renderName($client->first_name, $client->last_name)?></span><br>
                                <span style="font-weight: normal;"><?= date('d.m.Y H:i', $selectedProject->billed_date)?></span>
                            </a>
                            <div class="clr"></div>
                        </div>
                        <div data-id="184622" class="message_text public-comment">
                            <a href="#" class="tooltip_comments" title="Этот комментарий виден всем" style="position: absolute;     top: 0;right: 0;">
                                <img src="/img/public-comment-eye.png" alt="Этот комментарий виден всем">
                            </a>
                            <span class="regular comment-container">
                            <?php echo nl2br($selectedProject->description) ?>
                            </span>
                        </div>
                        <div class="toolbar-wrapper">
                            <div class="toolbar" style="display: none;">
                                <a href="#" data-comment-to="GoDesigner" class="replyto reply-link-in-comment" style="float:right; display: none;">Ответить</a>
                                <a href="#" data-url="/comments/warn.json" class="warning-comment warn-link-in-comment" style="float:right;">Пожаловаться</a>
                            </div>
                        </div>
                        <div class="clr"></div>
                        <div class="hiddenform" style="display:none">
                            <section>
                                <form style="margin-bottom: 25px;" action="/comments/edit/184622" method="post">
                                    <textarea name="text" data-id="184622"></textarea>
                                    <input type="button" src="/img/message_button.png" value="Отправить" class="button editcomment" style="margin-left:16px;margin-bottom:5px; width: 200px;"><br>
                                    <span style="margin-left:25px;" class="supplement3">Нажмите Esс, чтобы отменить</span>
                                    <div class="clr"></div>
                                </form>
                            </section>
                        </div>
                    </section>
                </section>
            <?php endif?>
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