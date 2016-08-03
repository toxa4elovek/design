<div class="wrapper">

    <?=$this->view()->render(['element' => 'header'], ['logo' => 'logo', 'header' => 'header2'])?>

    <main class="lp-golden-fish">
        <header>
            <div class="text">
                <h3>бизнес-предложение</h3>
                <h1>Золотая рыбка</h1>
            </div>
            <p>Решение бизнес-задач ваших клиентов силами профессиональных дизайнеров и копирайтеров сервиса. Мы гарантируем, что вы получите широкий выбор решений даже для проектов от 500 рублей, или мы вернем вам деньги.
            </p>
            <div class="buttons">
                <a href="#" class="yellow-button clean-style-button ">Начать зарабатывать</a>
                <a href="#" class="empty-button clean-style-button ">Как это работает</a>
            </div>
            <div class="wave"></div>
        </header>
        <section class="lp-advantages">
            <div class="header-advantages">
                <h3>ваши</h3>
                <h1>супер-возможности:</h1>
            </div>
            <ul>
                <li class="time-place">
                    <img src="/img/pages/golden-fish/Icon_I.gif" alt="Любое время и место работы">
                    <strong>Любое время и место работы</strong>
                    Количество прибыли зависит<br>
                    только от вашей занятости</li>
                <li class="ideas">
                    <img src="/img/pages/golden-fish/Icon_II.gif" alt="100 идей на проект">
                    <strong>100 идей на проект</strong>
                    От 500 рублей и пары часов</li>
                <li class="profit">
                    <img src="/img/pages/golden-fish/Icon_III.gif" alt="Неограниченная прибыль">
                    <strong>Неограниченная прибыль</strong>
                    Проекты с прибылью<br>
                    до десятков тысяч рублей</li>
                <li class="guarantee">
                    <img src="/img/pages/golden-fish/Icon_IV.gif" alt="Сохранность денег">
                    <strong>Сохранность денег</strong>
                    Если вам не нравится<br>
                    результат - деньги автоматически<br>
                    вернутся к вам на счет</li>
            </ul>
            <div class="clear"></div>
        </section>
        <section class="lp-how-to-profit">
            <h2>Как вы заработаете?</h2>
            <div class="header-profit"><img src="/img/pages/golden-fish/icons-with-lines.png" alt="Иконки"></div>
            <ul>
                <li class="publish"><strong>Публикуйте задачи клиента</strong>
                    Мы не берем сервисный сбор в течение года, вы оплачиваете только гонорар дизайнеру и копирайтеру.</li>
                <li class="choose"><strong>Выбирайте из ~100 креативных идей</strong>
                    С нами вы получите ~100 идей на логотип и ~240 идей на нейминг в каждом проекте, что в десятки раз больше, чем от одного фрилансера.</li>
                <li class="sell"><strong>Продавайте идеи со своей наценкой</strong>
                    Зарабатывайте за ведение проекта на разнице: бюджет проекта (минус) гонорар исполнителю.</li>
            </ul>
        </section>
        <section class="lp-agency">
            <div class="content">
                <h2>Cтаньте директором<br/>
                    собственного рекламного<br/>
                    агентства</h2>
                <p>с нашим бизнес-предложением “Золотая рыбка” вам останется только искать клиентов, а дизайнеры найдутся у нас.</p>
                <div>
                    <ul class="call-to-action">
                        <li class="price">
                            <h3>всего за</h3>
                            <h1>39 000 Р</h1>
                        </li>
                        <li class="press-button">
                            <a href="#" class="yellow-button clean-style-button ">Начать зарабатывать</a>
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
                <li class="rubles"><strong>Вернем деньги</strong>, если решения
                    вам не понравятся</li>
                <li class="glass"><strong>30 000</strong> дизайнеров
                    и копирайтеров</li>
                <li class="pig third"><strong>Отсутствие</strong> сервисного
                    <strong>сбора</strong> в течение одного года</li>
                <li class="files_projects">Проекты любой сложности
                    и бюджета (<strong>от 500 руб.</strong>)</li>
                <li class="presentation"><strong>Презентация в pdf</strong>
                    для клиента</li>
                <li class="eye third">Можно <strong>скрыть информацию</strong>
                    из общего доступа</li>
            </ul>
        </section>
    </main>
</div>
<?=$this->html->style([
    '/css/common/buttons.css',
    '/css/common/clear.css',
    '/css/common/backgrounds.css',
    '/css/pages/golden-fish.css'
], ['inline' => false])?>
<?=$this->html->script([
    'jquery-plugins/jquery.scrollto.min.js',
    'pages/golden-fish.js'
], ['inline' => false])?>
