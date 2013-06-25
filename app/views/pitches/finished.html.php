<div class="wrapper pitchpanel">

    <?=$this->view()->render(array('element' => 'header'))?>
    <div class="conteiner">
        <section>
            <h2>Все питчи</h2>
            <div class="shadow"></div>
            <nav>
                <ul class="pitches-type">
                    <li><a href="/pitches" class="status-switch" rel="current">Текущие</a></li>
                    <li class="active-pitches"><a href="/pitches/finished" class="status-switch" rel="finished">Завершённые</a></li>
                </ul>
            </nav>
            <nav>
                <ul class="navigation-pitches">
                    <li><a href="#" id="cat-menu-toggle"><span id="cat-menu-label" data-default="Категория">Категория</span></a>
                        <ul id="cat-menu" class="list-collapsed" style="display:none;">
                            <li><a href="#" class="category-filter" rel="all"><span>Всё</span></a></li>
                            <?php foreach($categories as $category):?>
                            <li><a href="#" class="category-filter" rel="<?= $category->id?>"><span><?=$category->title?></span></a></li>
                            <?php endforeach;?>
                        </ul>
                    </li>
                    <li><a href="#" id="price-menu-toggle"><span id="price-menu-label" data-default="Цена">Цена</span></a>
                        <ul id="price-menu" class="list-collapsed" style="display:none;">
                            <li><a href="#" class="price-filter" rel="all"><span>Всё</span></a></li>
                            <li><a href="#" class="price-filter" rel="1"><span>1000-3000</span></a></li>
                            <li><a href="#" class="price-filter" rel="2"><span>3001-6000</span></a></li>
                            <li><a href="#" class="price-filter" rel="3"><span>Более 6000</span></a></li>
                        </ul>
                    </li>
                    <li><a href="#" id="timelimit-menu-toggle" data-default="Срок"><span id="timelimit-menu-label" data-default="Срок">Срок</span></a>
                        <ul id="timelimit-menu" class="list-collapsed" style="display:none;">
                            <li><a href="#" class="timelimit-filter" rel="all"><span>Всё</span></a></li>
                            <li><a href="#" class="timelimit-filter" rel="1"><span>Меньше дня</span></a></li>
                            <li><a href="#" class="timelimit-filter" rel="2"><span>Меньше 4-ех дней</span></a></li>
                            <li><a href="#" class="timelimit-filter" rel="3"><span>Более 4-ех дней</span></a></li>
                            <li><a href="#" id="sort-started" rel="desc"><span>По новизне</span></a></li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <table class="all-pitches">
                <thead>
                <tr>
                    <td class="icons"></td>
                    <td class="pitches-name"><a href="#" id="sort-title" rel="asc" style="font-size:11px;color:#666666;">название питча</a></td>
                    <td class="pitches-cat"><a href="#" id="sort-category" rel="asc" style="font-size:11px;color:#666666;">Категории</a></td>
                    <td class="idea"><a href="#" id="sort-ideas_count" rel="desc" style="font-size:11px;color:#666666;">Идеи</a></td>
                    <td class="pitches-time"><a href="#" id="sort-finishDate" rel="asc" style="font-size:11px;color:#666666;">Срок</a></td>
                    <td class="price"><a href="#" id="sort-price" rel="desc" style="font-size:11px;color:#666666;">Цена</a></td>
                </tr>
                </thead>
                <tbody id="table-content">

                </tbody>
            </table>
            <div class="foot-content">
                <ul class="icons-infomation">
                    <li class="icons-infomation-one supplement3">Мнение экспертов<br> важно для этого клиента</li>
                    <li class="icons-infomation-two supplement3">Закрытый питч</li>
                    <li class="icons-infomation-three supplement3">Идеи больше не принимаются, идет выбор победителя</li>
                    <li class="icons-infomation-four supplement3">Питч завершен,<br> победитель выбран</li>
                </ul>
                <div class="page-nambe-nav">
                    <a href="#">&#60;</a><a href="#" class="this-page">1</a><a href="#">2</a><a href="#">3</a><a href="#">4</a><a href="#">5</a><a href="#">6</a> ... <a href="#">7</a><a href="#">&#62;</a>
                </div>
                <div class="you-profile supplement3">
                    Хотите узнать о добавлении новых питчей?<br>Измените <a href="/users/profile">настройки своего профиля</a>
                </div>
            </div>
        </section>
    </div>
    <div class="conteiner-bottom">
        <input type="hidden" value="<?=$selectedCategory?>" name="category">
        <input type="hidden" value="<?=$this->session->read('user.id')?>" id="user_id">
    </div>
</div>
</div><!-- .wrapper -->
<?=$this->html->script(array('tableloader.js', 'pitches/index.js'), array('inline' => false))?>