<div class="wrapper">

	<?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header'))?>

	<div class="middle">
	<div class="middle_inner">
       
		
		
		<div class="content group">
			<div class="margins-1">
				<section class="howitworks">
					<h1>как это работает</h1>
				</section>
				<p class="big-gray">с нами создать дизайн можно на</p>
            </div>

			<div class="margins-2">
				<div class="w100">
				
					<ul class="score">
						<li class="fst">
							<div>1</div>
							<h2 class="greyboldheader">Опишите, что вам нужно</h2>
							<p class="regular">Опубликуйте <a href="/pitches/create">бриф</a> на сайте, заполнив все поля. Чем более подробно вы ответите на вопросы, тем дизайнерам станет легче добиться желаемого результата!</p>
						</li>
						<li>
							<div>2</div>
							<h2 class="greyboldheader">Дизайнеры предложат идеи</h2>
							<p class="regular">Десятки креативщиков в сети выложат свои решения на сайт. Комментируйте и оценивайте их, и тогда вы гарантированно получите еще больше предложений!</p>
						</li>
						<li>
							<div>3</div>
							<h2 class="greyboldheader">Выберите лучший дизайн</h2>
							<p class="regular">Помогите дизайнерам лучше понять вас, и вы получите именно то, что хотели! Объявите победителя и  вы получите финальную версию с правами на исходник.</p>
						</li>
					</ul>
				
					<div class="clear"></div>

					<ul class="score">
						<li class="fst">
							<div><p><img src="/img/images/1.gif" alt="" /></p></div>
						</li>
						<li>
							<div><p><img  src="/img/images/2.gif" alt="" /></p></div>
						</li>
						<li>
							<div><p><img  src="/img/images/3.gif" alt="" /></p></div>
						</li>
					</ul>
				</div>				
			</div>		
			
			<div class="clear"></div>
			
			<div class="margins-1">
				<p class="big-gray">На старт - внимание - марш!</p>
				<div style="text-align:center;margin-bottom:25px;">
					<iframe style="text-align:center;" title="YouTube video player" src="http://www.youtube.com/embed/3bhLkorXLI8" frameborder="0" width="600" height="399"></iframe>
				</div>
				<div class="flag-red">
					<p>
						<?=$this->html->link('заполните бриф', 'Pitches::create')?><br />
						<i>и создайте питч для дизайнеров</i>
					</p>
				</div>
			</div>	
			
			<div class="margins-2 trigger-block">
				<div class="w100">
					<ul class="marsh">
						<li>
							<h2 class="greyboldheader">Вы сами назначаете цену дизайнерам</h2>
							<p class="regular"><a href="/">Go Designer</a> рекомендует минимальную сумму и берет процент от размещения брифа на сайте.</p>
						</li>
						<li>
							<h2 class="greyboldheader">Вы выбираете готовые решения, а не портфолио</h2>
							<p class="regular">Вам больше не нужно изучать огромное количество портфолио разных дизайнеров, теперь вы выбираете непосредственно идею!</p>
						</li>
						<li>
							<h2 class="greyboldheader">Больше решений за те же деньги</h2>
							<p class="regular">Если вы обратитесь в агентство, вам предоставят 3 варианта на выбор. Креативное сообщество в интернете способно предоставить намного больше идей. </p>
						</li>
						<li class="clear">
							<h2 class="greyboldheader">Интерактивно</h2>
							<p class="regular">Из-за отсутствия рутинной документации, текучки и иерархии, вы увидите решения сразу, как только дизайнеры загрузят их на сайт.</p>
						</li>
						<li>
							<h2 class="greyboldheader">Мнение экспертов</h2>
							<p class="regular">Креативщики и маркетологи именитых агентств с опытом работы в дизайне и рекламе могут помочь вам в выборе варианта, максимально отвечающего вашим запросам.</p>
						</li>
						<li>
							<h2 class="greyboldheader">Экономьте не только деньги, но и время</h2>
							<p class="regular">Ограниченная длительность питча мотивирует креативщиков: меньше чем за месяц вы получите то, что хотели!</p>
						</li>
					</ul>
				</div>
			</div>
			
			<div class="clear trigger-block"></div>
				
			
			<section class="faq trigger-block">
				<div class="margins-1">
					<h1>Часто задаваемые вопросы</h1>
				</div>
                <?=$this->faq->show($questions)?>
                <div>
                    <?=$this->html->link('Все вопросы', 'Answers::index', array('class' => 'more', 'style' => 'margin-left:28px;'));?>
                </div>
			</section>
				
    	
		</div><!-- /content -->
		
		</div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->			
	</div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->style(array('/howitworks'), array('inline' => false))?>