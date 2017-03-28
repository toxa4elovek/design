<div class="wrapper">

    <?=$this->view()->render(['element' => 'header'], ['logo' => 'logo'])?>

    <div class="middle">
        <div class="middle_inner user_view" style="min-height:330px; padding-left: 0; padding-right: 0;">

            <h2>Работайте с дизайнером один на один</h2>

            <div class="profile">

                <div class="info_profile">

                    <div class="info_profile_photo">
                        <?= $this->avatar->show($designer->data(), 'true') ?>
                    </div>
                    <div class="info_profile_about">
                        <span class="nickname"><?= $this->user->getFormattedName($designer->first_name, $designer->last_name, true) ?></span>
                        <ul class="profile-list-info"></ul>
                        <div class="pitches">
                            <ul class="profile-list">
                                <li class="regular-small-grey" style="color:#666666;">Решений:<span> <?= $totalSolutionNum ?></span></li>
                                <div class="g_line"></div>
                                <li class="regular-small-grey" style="color:#666666;">Выиграно:<span> <?= $awardedSolutionNum ?></span></li>
                                <div class="g_line"></div>
                                <li class="regular-small-grey" style="color:#666666;">Рейтинг у заказчика:<span> <?= $averageGrade ?></span></li>
                                <div class="g_line"></div>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <section class="lp-projects" >
                <h2 style="position: relative;">Решения дизайнера
                    <div class="arrows-container">
                        <div class="arrows"></div>
                    </div>
                </h2>
                <div class="lp-projects-slider">
                    <?php foreach ($solutions as $solution):
                        if ((!isset($solution->images)) || empty($solution->images) || ($this->Solution->renderImageUrl($solution->images['solution_promo'], 0) === '')):
                            continue;
                        endif;
                        ?>
                        <div class="img-container">
                            <img src="https://godesigner.ru/<?= $this->Solution->renderImageUrl($solution->images['solution_promo'], 0) ?>" alt="Решение" />
                            <a class="overlay" href="/pitches/viewsolution/<?= $solution->id ?>">
                                <?= $solution->pitch->title ?>
                                <span><?php echo $this->moneyFormatter->formatMoney($solution->pitch->price, ['suffix' => ' Р']) ?></span>
                            </a>
                        </div>
                    <?php endforeach;?>
                </div>
            </section>
            <section class="lp-rules">
                <h2>Представляем индивидуальные проекты!</h2>
                <p class="regular">Выберите дизайнера и предложите ему персональный проект. Опишите задачу, установите срок, цену и ожидайте ответ исполнителя в течение 3 дней.</p>
                <p class="regular">Мы блокируем, но не списываем средства с карты, пока дизайнер не подтвердит согласие на выполнение работы. После этого денежные средства резервируются, и исполнитель приступает к работе.</p>
                <p class="regular">Мы отправляем вознаграждение, когда вы подтвердите завершение работы и получение результата. Подробный Регламент индивидуальных проектов — в разделе <a href="https://godesigner.ru/answers/view/115">Помощь</a>.</p>
            </section>
            <section class="lp-form">
                <form method="post" action="/pitches/create1on1Project/<?=$designer->id?>">
                    <label style="display:block;" class="regular">Награда дизайнеру (от 2000 рублей)<br/><input type="text" required name="price" value="<?= $projectData['award']?>" pattern="^[0-9]+$" placeholder="8000"/></label>
                    <label style="display:block;" class="regular">Дней на исполнение <br/><input type="text" required name="days" value="<?= $projectData['days']?>" pattern="^[0-9]+$" placeholder="5"/></label>
                    <label style="display:block;" class="regular">Описание задания для дизайнера<br/><textarea name="description" required><?= $projectData['description']?></textarea></label>
                    <div class="tos-container supplement" style="margin-bottom: 20px; position: relative;">
                        <label><input type="checkbox" name="tos" required style="vertical-align: middle; margin-right: 5px;">Я прочитал(а) и выражаю безусловное согласие с условиями настоящего <a target="_blank" href="/docs/dogovor_oferta_200317.pdf" style="text-decoration: none;">договора публичной оферты</a>.</label>
                    </div>
                    <input class="clear button third" type="submit" value="Оплатить и опубликовать проект" style="margin-top: 30px;">
                </form>
                <script>
                  var payload = {
                    "receipt": <?php echo json_encode($receipt) ?>
                  }
                </script>
                <aside id="receipt-container"></aside>
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