<div class="wrapper">

    <?= $this->view()->render(['element' => 'header'], ['header' => 'header2']) ?>

    <div class="middle pitch-category" style="padding-bottom: 0;">

        <div class="main">

            <section class="howitworks" style="margin-top: 15px; margin-bottom: 30px;">
                <h1 style="margin-top: 30px;">выберите категорию</h1>
            </section>

            <!--p class="intro_text">Мы не допускаем создания единого проекта<br /> на логотип и фирменный стиль. <a href="http://godesigner.ru/answers/view/95" target="_blank">Подробнее...</a>
            </p-->

            <ul>
                <li class="empty"></li>
                <li class="category-micro">
                    <?= $this->html->link('
                    <h3>Микропроекты</h3>
                    <p class="description">для несложных проектов</p>
                    <p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[22]->discountPrice : $categories[22]->minAward, ['suffix' => 'Р.-']) . '</p>
                    ', ['controller' => 'Pitches', 'action' => 'brief', 'category' => 22], ['escape' => false]) ?>
                </li>
                <li class="empty"></li>
                <li class="category-logo big">
                    <?= $this->html->link('
						<h3 style="font-size: 28px">Лого</h3>
						<p class="description">Знак, персонаж, написание, иконка</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[1]->discountPrice : $categories[1]->minAward, ['suffix' => 'Р.-']) . '</p>
					', ['controller' => 'Pitches', 'action' => 'brief', 'category' => 1], ['escape' => false]) ?>
                </li>
                <li class="category-site big">
                    <?= $this->html->link('
						<h3 style="font-size: 28px">Сайт</h3>
						<p class="description">Приложения iOS, Android, Landing page</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[3]->discountPrice : $categories[3]->minAward, ['suffix' => 'Р.-']) . '</p>
					', ['controller' => 'Pitches', 'action' => 'brief', 'category' => 3], ['escape' => false]) ?>
                </li>
                <li class="category-firmstyle2 big">
                    <?= $this->html->link('
						<h3 style="font-size: 26px">Фирм. стиль и логотип</h3>
						<p class="description" style="margin-bottom: 26px;">&nbsp;</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[13]->discountPrice : $categories[13]->minAward, ['suffix' => 'Р.-']) . '</p>
					', ['controller' => 'Pitches', 'action' => 'brief', 'category' => 13], ['escape' => false]) ?>
                </li>
                <li class="category-firmstyle">
                    <?= $this->html->link('
						<h3>Фирменный стиль</h3>
						<p class="description">Без логотипа<br><br></p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[5]->discountPrice : $categories[5]->minAward, ['suffix' => 'Р.-']) . '</p>
					', ['controller' => 'Pitches', 'action' => 'brief', 'category' => 5], ['escape' => false]) ?>
                </li>
                <li class="category-copyrighting">
                    <!--div style="background: url('/img/category/copyrigh_unactive_button.png') no-repeat scroll 11px 17px transparent">
                        <h3 style="color:#a8a8a8">Копирайтинг</h3>
                        <p class="description" style="color:#a8a8a8">Название, тексты, слоганы, хэдлайны</p>
                        <p class="price" style="color:#a8a8a8">от <?= $this->moneyFormatter->formatMoney($categories[7]->minAward, ['suffix' => 'Р.-']) ?>
                    </div-->
                    <?= $this->html->link('
						<h3>Копирайтинг</h3>
						<p class="description">Название, тексты, слоганы, хэдлайны</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[7]->discountPrice : $categories[7]->minAward, ['suffix' => 'Р.-']) . '</p>
					', ['controller' => 'Pitches', 'action' => 'brief', 'category' => 7], ['escape' => false]) ?>
                </li>
                <li class="category-upakovka">
                    <?= $this->html->link('
						<h3>Упаковка</h3>
						<p class="description">Этикетка, оформление коробки</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[11]->discountPrice : $categories[11]->minAward, ['suffix' => 'Р.-']) . '</p>
					', ['controller' => 'Pitches', 'action' => 'brief', 'category' => 11], ['escape' => false]) ?>
                </li>
                <li class="category-flaer">
                    <?= $this->html->link('
						<h3>Листовка</h3>
						<p class="description">Флаер, буклет, брошюра, презентация</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[4]->discountPrice : $categories[4]->minAward, ['suffix' => 'Р.-']) . '</p>
					', ['controller' => 'Pitches', 'action' => 'brief', 'category' => 4], ['escape' => false]) ?>
                </li>
                <li class="category-webban">
                    <?= $this->html->link('
						<h3>Web-баннер</h3>
						<p class="description">Cтатичный баннер или раскадровка для Flash</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[2]->discountPrice : $categories[2]->minAward, ['suffix' => 'Р.-']) . '</p>
					', ['controller' => 'Pitches', 'action' => 'brief', 'category' => 2], ['escape' => false]) ?>
                </li>
                <li class="category-illustration">
                    <?= $this->html->link('
						<h3>Иллюстрация</h3>
						<p class="description">Для журнала, футболки, сайта, etc</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[9]->discountPrice : $categories[9]->minAward, ['suffix' => 'Р.-']) . '</p>
					', ['controller' => 'Pitches', 'action' => 'brief', 'category' => 9], ['escape' => false]) ?>
                </li>
                <li class="category-icons">
                    <?= $this->html->link('
						<h3>Реклама</h3>
						<p class="description">полоса в журнале, билборд, сити-формат</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[12]->discountPrice : $categories[12]->minAward, ['suffix' => 'Р.-']) . '</p>
					', ['controller' => 'Pitches', 'action' => 'brief', 'category' => 12], ['escape' => false]) ?>
                </li>
                <li class="category-subscriber">
                    <?= $this->html->link('
						<h3>Годовое обслуживание</h3>
						<p class="description">&nbsp;</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney(49000, ['suffix' => 'Р.-']) . '</p>
					', ['controller' => 'pages', 'action' => 'subscribe'], ['escape' => false]) ?>
                </li>
                <li class="category-otherdesign">
                    <?= $this->html->link('
						<h3>Другое</h3>
						<p class="description">Что-то не указано в списке? Опишите…</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[10]->discountPrice : $categories[10]->minAward, ['suffix' => 'Р.-']) . '</p>
					', ['controller' => 'Pitches', 'action' => 'brief', 'category' => 10], ['escape' => false]) ?>
                </li>
            </ul>
            <div style="text-align:center"><a id="needassist" href="#" style="height:16px;background: url('/img/category_icon.png') no-repeat;padding-left:24px;margin-top: 10px">Не нашли нужную категорию? Спросите у нас.</a></div>
        </div><!-- .main -->
        <a href="https://godesigner.ru/fastpitch" title="Логотип в один клик за 21 400 руб" id="fastpitch-logo"></a>


    </div><!-- .middle -->

</div><!-- .wrapper -->
<?=
$this->html->script(['pitches/create'], ['inline' => false])?>