<div class="wrapper">

	<?=$this->view()->render(array('element' => 'header'), array('header' => 'header2'))?>

	<div class="middle pitch-category">

		<div class="main">

			<h2>Выберите категорию</h2>

			<ul>
				<li class="category-logo big">
					<?=$this->html->link('
						<h3>Лого</h3>
						<p class="description">Знак, лого, персонаж, фирм. написание</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney($categories[1]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 1), array('escape' => false))?>
				</li>
				<li class="category-site big">
					<?=$this->html->link('
						<h3>Сайт</h3>
						<p class="description">Приложения iOS, Android, Landing page</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney($categories[3]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 3), array('escape' => false))?>
				</li>
				<li class="category-flaer big">
					<?=$this->html->link('
						<h3>Флаер</h3>
						<p class="description">Визитка, листовка, брошюра</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney($categories[4]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 4), array('escape' => false))?>
				</li>
				<li class="category-firmstyle">
					<?=$this->html->link('
						<h3>Фирмен. стиль</h3>
						<p class="description">Без логотипа</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney($categories[5]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 5), array('escape' => false))?>
				</li>
				<li class="category-socialpage">
					<?=$this->html->link('
						<h3>Страница для соц. сетей</h3>
						<p class="description">Страница Fb, Vk, Twitter</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney($categories[6]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 6), array('escape' => false))?>
				</li>
				<li class="category-copyrighting">
                    <!--div style="background: url('/img/category/copyrigh_unactive_button.png') no-repeat scroll 11px 17px transparent">
                        <h3 style="color:#a8a8a8">Копирайтинг</h3>
                        <p class="description" style="color:#a8a8a8">Название, тексты, слоганы, хэдлайны</p>
                        <p class="price" style="color:#a8a8a8">от <?=$this->moneyFormatter->formatMoney($categories[7]->minAward, array('suffix' => 'Р.-'))?>
                    </div-->
					<?= $this->html->link('
						<h3>Копирайтинг</h3>
						<p class="description">Название, тексты, слоганы, хэдлайны</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney($categories[7]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 7), array('escape' => false))?>
				</li>
				<li class="category-buklet">
					<?=$this->html->link('
						<h3>Буклет</h3>
						<p class="description">Сетка, верстка многополосного издания</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney($categories[8]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 8), array('escape' => false))?>
				</li>
				<li class="category-webban">
					<?=$this->html->link('
						<h3>Web-баннер</h3>
						<p class="description">Cтатичный баннер или раскадровка для Flash</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney($categories[2]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 2), array('escape' => false))?>
				</li>
				<li class="category-illustration">
					<?=$this->html->link('
						<h3>Иллюстрация</h3>
						<p class="description">Для журнала, футболки, сайта, etc</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney($categories[9]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 9), array('escape' => false))?>
				</li>
				<li class="category-upakovka">
					<?=$this->html->link('
						<h3>Упаковка</h3>
						<p class="description">Этикетка, оформление коробки</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney($categories[11]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 11), array('escape' => false))?>
				</li>
				<li class="category-icons">
					<?=$this->html->link('
						<h3>Реклама</h3>
						<p class="description">полоса в журнале, билборд, сити-формат</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney($categories[12]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 12), array('escape' => false))?>
				</li>
				<li class="category-otherdesign">
					<?=$this->html->link('
						<h3>Другое</h3>
						<p class="description">Что-то не указано в списке? Опишите…</p>
						<p class="price">от ' . $this->moneyFormatter->formatMoney($categories[10]->minAward, array('suffix' => 'Р.-')) . '</p>
					', array('controller' => 'Pitches', 'action' => 'brief', 'category' => 10), array('escape' => false))?>
				</li>
			</ul>
            <div style="text-align:center"><a id="needassist" href="#" style="height:16px;background: url('/img/category_icon.png') no-repeat;padding-left:24px;margin-top: 10px">Не нашли нужную категорию? Спросите у нас.</a></div>
		</div><!-- .main -->

	</div><!-- .middle -->

</div><!-- .wrapper -->
<?=$this->html->script(array('pitches/create'), array('inline' => false))?>