<div class="wrapper">

    <?= $this->view()->render(array('element' => 'header'), array('header' => 'header2')) ?>

    <div class="middle pitch-category" style="padding-bottom: 0;">

        <div class="main">


            <section class="howitworks" style="margin-top: 15px; margin-bottom: 30px;">
                <h1>выберите категорию</h1>
            </section>

            <!--p class="intro_text">Мы не допускаем создания единого проекта<br /> на логотип и фирменный стиль. <a href="http://www.godesigner.ru/answers/view/95" target="_blank">Подробнее...</a>
            </p-->

            <ul>
                <li class="category-logo big">
                    <?= $this->html->link('
						<h3 style="font-size: 28px">Лого</h3>
						<p class="description">Знак, лого, персонаж, написание, иконка</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[1]->discountPrice : $categories[1]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 1), array('escape' => false)) ?>
                </li>
                <li class="category-site big">
                    <?= $this->html->link('
						<h3 style="font-size: 28px">Сайт</h3>
						<p class="description">Приложения iOS, Android, Landing page</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[3]->discountPrice : $categories[3]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 3), array('escape' => false)) ?>
                </li>
                <li class="category-firmstyle2 big">
                    <?= $this->html->link('
						<h3 style="font-size: 26px">Фирм. стиль и логотип</h3>
						<p class="description" style="margin-bottom: 26px;">&nbsp;</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[13]->discountPrice : $categories[13]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 13), array('escape' => false)) ?>
                </li>
                <li class="category-firmstyle">
                    <?= $this->html->link('
						<h3>Фирменный стиль</h3>
						<p class="description">Без логотипа<br><br></p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[5]->discountPrice : $categories[5]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 5), array('escape' => false)) ?>
                </li>
                <li class="category-socialpage">
                    <?= $this->html->link('
						<h3>Страница для соц. сетей</h3>
						<p class="description">Страница Fb, Vk, Twitter</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[6]->discountPrice : $categories[6]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 6), array('escape' => false)) ?>
                </li>
                <li class="category-copyrighting">
                    <!--div style="background: url('/img/category/copyrigh_unactive_button.png') no-repeat scroll 11px 17px transparent">
                        <h3 style="color:#a8a8a8">Копирайтинг</h3>
                        <p class="description" style="color:#a8a8a8">Название, тексты, слоганы, хэдлайны</p>
                        <p class="price" style="color:#a8a8a8">от <?= $this->moneyFormatter->formatMoney($categories[7]->minAward, array('suffix' => 'Р.-')) ?>
                    </div-->
                    <?= $this->html->link('
						<h3>Копирайтинг</h3>
						<p class="description">Название, тексты, слоганы, хэдлайны</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[7]->discountPrice : $categories[7]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 7), array('escape' => false)) ?>
                </li>
                <li class="category-flaer">
                    <?= $this->html->link('
						<h3>Листовка</h3>
						<p class="description">Флаер, буклет, брошюра, презентация</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[4]->discountPrice : $categories[4]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 4), array('escape' => false)) ?>
                </li>
                <li class="category-webban">
                    <?= $this->html->link('
						<h3>Web-баннер</h3>
						<p class="description">Cтатичный баннер или раскадровка для Flash</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[2]->discountPrice : $categories[2]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 2), array('escape' => false)) ?>
                </li>
                <li class="category-illustration">
                    <?= $this->html->link('
						<h3>Иллюстрация</h3>
						<p class="description">Для журнала, футболки, сайта, etc</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[9]->discountPrice : $categories[9]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 9), array('escape' => false)) ?>
                </li>
                <li class="category-upakovka">
                    <?= $this->html->link('
						<h3>Упаковка</h3>
						<p class="description">Этикетка, оформление коробки</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[11]->discountPrice : $categories[11]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 11), array('escape' => false)) ?>
                </li>
                <li class="category-icons">
                    <?= $this->html->link('
						<h3>Реклама</h3>
						<p class="description">полоса в журнале, билборд, сити-формат</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[12]->discountPrice : $categories[12]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 12), array('escape' => false)) ?>
                </li>
                <li class="category-otherdesign">
                    <?= $this->html->link('
						<h3>Другое</h3>
						<p class="description">Что-то не указано в списке? Опишите…</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney((date('N') > 5) ? $categories[10]->discountPrice : $categories[10]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 10), array('escape' => false)) ?>
                </li>
            </ul>
            <div style="text-align:center"><a id="needassist" href="#" style="height:16px;background: url('/img/category_icon.png') no-repeat;padding-left:24px;margin-top: 10px">Не нашли нужную категорию? Спросите у нас.</a></div>
        </div><!-- .main -->
        <a href="http://www.godesigner.ru/fastpitch" title="Логотип в один клик за 19 600 руб" id="fastpitch-logo"></a>


    </div><!-- .middle -->

</div><!-- .wrapper -->
<?=
$this->html->script(array('pitches/create'), array('inline' => false))?>