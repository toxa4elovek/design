<div class="wrapper">
	
<?=$this->view()->render(array('element' => 'header'), array('logo' => 'logo', 'header' => 'header'))?>

	<div class="middle">
        <div class="middle_inner">
            <div class="content group">
                    <section class="howitworks">
                    	<h1>О проекте</h1>
                      </section>
                        <div id="ap_content_top">
                        	<div id="ap_content_top_l">
                            	<h2 class="largest-header"><i>Go Designer</i> — это виртуальная площадка, объединяющая дизайнеров и&nbsp;тех, кому нужны творческие решения</h2>
                                <div id="ap_content_top_l_img"><img src="/img/ap_c_t_d_1.jpg" alt=""></div>
                                <p class="regular">Мы — команда креативщиков, бизнесменов и&nbsp;менеджеров из мира рекламы. Мы как никто другой знакомы с проблемой, когда прямо сейчас нужны свободные дизайнеры или интересные заказы. Поэтому мы решили приложить все усилия и&nbsp;соединить представителей творческих профессий и&nbsp;заказчиков воедино, создав платформу для эффективной работы.</p>
                                <img src="/img/ap_c_i_d_2.png" alt="">
                                <p class="regular">Есть проекты, для которых не нужны сетевые агентства или которые нужно сделать в короткие сроки. Вот где вас выручит Go Designer — мы опубликуем ваши проекты и&nbsp;предоставим дизайнеров с отличными идеями. </p>

<p class="regular">Вам нужен сайт, логотип, слоган, любой графический дизайн, текст или сценарий? Тогда вам — к нам! Просто опишите, что вам нужно, какой гонорар вы предлагаете и&nbsp;укажите дедлайн. Опубликуйте бриф на сайте, и&nbsp;дизайнеры со всего мира предложат вам свои решения — а вы выберете лучшее! Вы назначаете цену,<br/> вы определяете требования и&nbsp;дэдлайн. <br/>
Подробнее вы можете ознакомиться с этим в разделе <a href="/pages/howitworks">«Как это работает»</a>!</p>

<p class="regular">Наш головной офис <a href="/pages/contacts">расположен</a> в Санкт-Петербурге, а команда дизайнеров <br/>и копирайтеров работает со всех точек земного шара. Интернет дарит неограниченные возможности&nbsp;для эффективной и&nbsp;быстрой работы!</p>
                            </div>
                            <div id="ap_content_top_r">
                            	<div id="ap_content_r_1" class="regular">
                                    <h2 class="greyboldheader">Возникли вопросы?</h2>
                                    Вы можете найти ответ в разделе <a href="/answers">«Часто задаваемые вопросы»</a>, или напишите нам сообщение. Мы постараемся вам ответить в течении 24 часов по рабочим дням.
                                    <?=$this->html->link('<img src="/img/ap_r_1_1.gif" alt="">', 'Pages::contacts', array('escape' => false))?>
                                </div>
                                <div id="ap_content_r_2">
                                    <h2 class="greyboldheader">Часто задаваемые вопросы</h2>
                                    <?=$this->faq->show($questions)?>
                                </div>
                                <!--div id="ap_content_r_3">
                                    <h2>Популярные разделы</h2>
                                    <ul>
                                    	<li><a href="#">Помощь дизайнерам</a></li>
                                        <li><a href="#">Помощь заказщчикам</a></li>
                                        <li><a href="#">Наши эксперты</a></li>
                                    </ul>
                                </div-->
                            </div>
                        </div>
                        <div id="ap_content_b" style="position: relative; float: left; margin-top: 20px;">
                            <section class="howitworks" style="margin-bottom: 40px;">
                                <h1>команда</h1>
                            </section>
                            <table>	
                            	<tr>	
                                	<td width="256" class="img" style="text-align: center;"><img src="/img/mf.png" alt="Максим Федченко"></td>
                                    <td width="35"></td>
                                    <td width="253" class="img" style="text-align: center;"><img src="/img/od.png" alt="Оксана Девочкина"></td>
                                    <td width="35"></td>
                                    <td width="253" class="img" style="text-align: center;"><img src="/img/dn.png" alt="Дмитрий Ню"></td>
                                </tr>
                                <tr height="5"><td colspan="5"></td></tr>
                                <tr>	
                                	<td width="256" class="title greyboldheader" style="text-align: center;"><i>Максим Федченко</i><br/>(CEO)</td>
                                    <td width="35"></td>
                                    <td width="253" class="title greyboldheader" style="text-align: center;"><i>Оксана Девочкина</i><br/>(арт-директор)</td>
                                    <td width="35"></td>
                                    <td width="253" class="title greyboldheader" style="text-align: center;"><i>Дмитрий Ню</i><br/>(тех. директор)</td>
                                </tr>
                                <tr height="20"><td colspan="5"></td></tr>
                                <tr>	
                                	<td width="256" class="text regular">Бизнесмен и&nbsp;основатель tutdesign, Максим был всегда заинтересован<br> в развитии интернет-технологий, веб&#8209;продвижения, краудсорсинга и&nbsp;независимого месторасположения работы. Вращаясь в кругу творческих людей, сначала он создал <a href="http://tutdesign.ru" target="_blank">новостной сайт о дизайне</a>, и&nbsp;позже —&nbsp;платформу, объединяющую креативщиков и&nbsp;за-<br>казчиков с любой точки планеты. Максим очень любит путешествовать, вдохновляется актуальным искус-<br>ством и&nbsp;электронной музыкой.</td>
                                    <td width="35"></td>
                                    <td width="253" class="text regular">Оксана выросла на побережье Черного моря, училась в Германии, работала в США и&nbsp;много путешество-<br>вала по Европе. Закончив отделение «Дизайн» в СПбГУ с Красным дип-<br>ломом, она получила незаменимый опыт в самом креативном агентстве Петербурга. Оксана обозревает тен-<br>денции в дизайне на <a href="http://tutdesign.ru" target="_blank">tutdesign</a>, помо-<br>гает решать творческие задачи и&nbsp;об-<br>ретать технические навыки. Как никто другой она знает, как важно проявить себя и&nbsp;поработать над интересными проектами.</td>
                                    <td width="35"></td>
                                    <td width="253" class="text regular">Дмитрий с детства увлекался высо-<br>кими технологиями, поэтому неудиви-<br>тельно, что когда в его жизнь ворвал-<br>ся интернет, первым делом он решил разобраться, как же всё в нём рабо-<br>тает. Вскоре он изучил несколько популярных интернет технологий, <br>и начал разрабатывать различные web&#8209;системы для заказчиков. Дмитрий никогда не зацикливался на&nbsp;одном, и&nbsp;поэтому в его активе есть многочисленные web-сайты, сложные корпоративные системы и&nbsp;социаль-<br>ные игры.</td>
                                </tr>
                           </table>
                        </div>
            </div><!-- /content -->		
		</div><!-- /middle_inner -->
        <div id="under_middle_inner"></div><!-- /under_middle_inner -->			
	</div><!-- /middle -->

</div><!-- .wrapper -->
<?=$this->html->style(array('/howitworks', '/about_project'), array('inline' => false))?>