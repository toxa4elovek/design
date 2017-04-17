<div class="wrapper lp-wrapper">

    <?=$this->view()->render(['element' => 'header'], ['logo' => 'logo', 'header' => 'header2'])?>

    <main class="lp-golden-fish">
        <header>
            <div class="text">
                <h3>бизнес-план</h3>
                <h1>Золотая рыбка</h1>
            </div>
            <p>Решайте задачи клиентов силами дизайнеров и копирайтеров сервиса.
                Зарабатывайте на разнице гонораров. Мы вернем деньги, если идеи не понравятся.
            </p>
            <div class="buttons">
                <a href="/subscription_plans/subscriber/4" class="yellow-button clean-style-button ">Начать зарабатывать</a>
                <a href="/golden-fish/how-it-works" class="empty-button clean-style-button ">Как это работает</a>
            </div>
            <div class="wave"></div>
        </header>
        <section class="lp-advantages">

            <div class="calculator">
                <div class="sliders">
                    <h2>Рассчитайте прибыль:</h2>

                    <ul>
                        <li>Клиентов в месяц</li>
                        <li id="clients" data-clients="10">10</li>
                    </ul>
                    <div class="clear"></div>
                    <div class="gf-slider clients"></div>

                    <ul>
                        <li>Средний гонорар исполнителю</li>
                        <li id="award" data-award="50000">50 000 Р</li>
                    </ul>
                    <div class="clear"></div>
                    <div class="gf-slider award"></div>

                    <ul>
                        <li>Ваша наценка в %</li>
                        <li id="margin" data-margin="25">25</li>
                    </ul>
                    <div class="clear"></div>
                    <div class="gf-slider margin"></div>
                </div>

                <div class="profit-calc">
                    <h3>За 12 месяцев я получу:</h3>
                    <span class="from">от</span> <h1 id="result">1 500 000 Р</h1>
                    <a href="/subscription_plans/subscriber/4" class="yellow-button clean-style-button ">Начать зарабатывать</a>
                    <span class="nb"><?= $this->moneyFormatter->formatMoney($plan, ['suffix' => ''])?>руб./год<br>стоимость &laquo;Золотой рыбки&raquo;</span>
                </div>
            </div>


            <div class="header-advantages clear">
                <h3>ваши</h3>
                <h1>супер-возможности:</h1>

            </div>
            <ul class="advantage-list">
                <li class="time-place">
                    <img src="/img/pages/golden-fish/Icon_I.gif" alt="Любое время и место работы">
                    <strong>Любое время и место работы</strong>
                    Количество прибыли зависит<br>
                    только от занятости</li>
                <li class="ideas">
                    <img src="/img/pages/golden-fish/Icon_II.gif" alt="100 идей на проект">
                    <strong>100 идей на проект</strong>
                    Задачи любой сложности<br>
                    от 500 р. и пары часов</li>
                <li class="profit">
                    <img src="/img/pages/golden-fish/Icon_III.gif" alt="Неограниченная прибыль">
                    <strong>Неограниченная прибыль</strong>
                    Зарабатывайте на разнице<br> стоимости заказа<br> и гонорара автору</li>
                <li class="guarantee">
                    <img src="/img/pages/golden-fish/Icon_IV.gif" alt="Сохранность денег">
                    <strong>Сохранность денег</strong>
                    Мы вернем деньги,<br>
                    если решения не понравятся</li>
            </ul>
            <div class="clear"></div>
        </section>
        <section class="lp-how-to-profit">
            <h2>Как вы заработаете?</h2>
            <div class="header-profit"><img src="/img/pages/golden-fish/icons-with-lines.png" alt="Иконки"></div>
            <ul>
                <li class="publish"><strong>Публикуйте задачи клиента</strong>
                    Опишите задачу, ответив на вопросы,<br>и получайте готовые идеи через пару часов.</li>
                <li class="choose"><strong>Выбирайте из ~100 креативных идей</strong>
                    Дизайнеры предложат ~100 идей на логотип и ~240 идей на нейминг, что в 10 раз больше, чем от 1 фрилансера.</li>
                <li class="sell"><strong>Продавайте идеи со своей наценкой</strong>
                    Устанавливайте свои цены, и зарабатывайте<br>на разнице гонораров вам и исполнителю.</li>
            </ul>
        </section>
        <section class="lp-agency">
            <div class="content">
                <h2>Cтаньте директором<br/>
                    собственного рекламного<br/>
                    агентства</h2>
                <p>С бизнес-планом «Золотая рыбка» вы ищете клиентов,<br>а дизайнеры и копирайтеры найдутся у нас.</p>
                <div>
                    <ul class="call-to-action">
                        <li class="price">
                            <h3>всего за</h3>
                            <h1><?= $this->moneyFormatter->formatMoney($plan, ['suffix' => ''])?><span style="font-size: 20px;">руб./год</span></h1>
                        </li>
                        <li class="press-button">
                            <a href="/subscription_plans/subscriber/4" class="yellow-button clean-style-button ">Начать зарабатывать</a>
                        </li>
                    </ul>
                </div>
                <img src="/img/pages/golden-fish/ipad.png" alt="IPad">
            </div>
        </section>
        <section class="lp-you-get">
            <div class="header-youget">
                <h3>что</h3>
                <h1>вы получите</h1>
            </div>
            <ul>
                <li class="rubles">Вернем деньги, если решения
                    не понравятся</li>
                <li class="glass">30 000 дизайнеров<br>
                    и копирайтеров</li>
                <li class="pig third">Отсутствие сервисного<br>
                    сбора в течение одного года</li>
                <li class="files_projects">Задачи любой сложности
                    и бюджета от 500 руб.</li>
                <li class="presentation">Презентация в pdf<br>
                    для клиента</li>
                <li class="eye third">Можно скрыть информацию
                    из общего доступа</li>
            </ul>
        </section>
        <section class="lp-projects" >
            <h2 style="position: relative;">Реализованные проекты
                <div class="arrows-container">
                    <div class="arrows"></div>
                </div>
            </h2>
            <div class="lp-projects-slider">
                <?php foreach ($solutions as $solution):
                    if ((!isset($solution->images)) || (empty($solution->images)) || ($this->Solution->renderImageUrl($solution->images['solution_promo'], 0) === '')):
                        continue;
                    endif;
                    ?>
                    <div class="img-container">
                        <img src="https://godesigner.ru/<?= $this->Solution->renderImageUrl($solution->images['solution_promo'], 0) ?>" alt="Решение" />
                        <a class="overlay" href="/pitches/view/<?= $solution->pitch_id ?>">
                            <?= $solution->pitch->title ?>
                            <span><?php echo $this->moneyFormatter->formatMoney($solution->pitch->price, ['suffix' => ' Р']) ?></span>
                        </a>
                    </div>
                <?php endforeach;?>
            </div>
        </section>
        <section class="lp-contact">
            <ul>
                <li class="info">
                    <h2>Остались вопросы?</h2>
                    <p>Мы ответим в течение рабочего дня: <a href="mailto:team@godesigner.ru">team@godesigner.ru</a><br>
                        или просто звоните по телефону +7 812 648-24-12 с 10-17 по Москве</p>
                </li>
                <li class="form">
                    <form action="/users/requesthelp" method="post">
                        <input type="hidden" name="case" value="fu27fwkospf">
                        <input type="hidden" value="0" name="target" />
                        <input type="hidden" value="" name="name" />
                        <textarea name="message" placeholder="Напишите здесь свой вопрос"></textarea>
                        <input type="text" value="" name="email" placeholder="Ваш E-mail">
                        <a href="#" id="send-message" class="teal-button clean-style-button ">отправить вопрос</a>
                    </form>
                </li>
            </ul>
        </section>
    </main>
</div>
<!-- Facebook Pixel Code -->
<script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '1707695879554473', {
        em: 'insert_email_variable,'
    });
    fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
               src="https://www.facebook.com/tr?id=1707695879554473&ev=PageView&noscript=1"
    /></noscript>
<!-- DO NOT MODIFY -->
<!-- End Facebook Pixel Code -->
<?=$this->html->style([
    '/css/ui-lightness/jquery-ui.css',
    '/css/common/buttons.css',
    '/css/common/clear.css',
    '/css/common/backgrounds.css',
    '/css/pages/golden-fish.css',
    '/js/slick/slick.css',
    '/js/slick/slick-theme.css'
], ['inline' => false])?>
<?=$this->html->script([
    'jquery-ui-1.11.4.min.js',
    '/js/slick/slick.min.js',
    '/js/libgif.js',
    'pages/golden-fish.js'
], ['inline' => false])?>
