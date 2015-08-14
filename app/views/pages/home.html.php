<div class="wrapper login" style="background:none;">

  <?=$this->view()->render(array('element' => 'header'))?>
  <div style="clear:both"></div>
  <div id="slides">
    <div class="slides_container" style="height:300px;">
      <div class="slide">
      <a href="/pages/howitworks"><img src="/img/main/1.png" alt="" /></a>
      </div>
      <div class="slide">
          <a href="/pages/howitworks"><img src="/img/main/2.png" alt="" /></a>
      </div>
        <div class="slide">
            <a href="/pages/howitworks"><img src="/img/main/3.png" alt="" /></a>
        </div>
        <div class="slide">
            <a href="/fastpitch"><img src="/img/main/4.png" alt="" /></a>
        </div>
    </div>
    <a id="finished" href="/pitches/?type=finished" style="height:32px;width:173px;position: absolute; top: 251px; left: 79px; z-index:101;background-image:url(/img/examples_173_32_red.png)"><img src="/img/examples_173_32.png" alt="Просмотреть примеры"></a>
    <a id="video" href="#" style="height:32px;width:190px;position: absolute; top: 251px; left: 242px; z-index:101;background-image:url(/img/video_red_173_32.png)"><img src="/img/video_173_32.png" alt="Просмотреть примеры"></a>
    <a href="#" class="prev"><img src="img/arrow-prev.png"  alt="Arrow Prev"></a>
    <a href="#" class="next"><img src="img/arrow-next.png"  alt="Arrow Next"></a>
  </div>

  <div class="main_page_content">
      <div class="take_fill_block">
          <div class="take">
              <?=$this->html->link('Дизайнеру', 'Pitches::index')?><br>
              <span>предложите идею заказчику</span>
          </div>
          <div class="fill">
              <?=$this->html->link('Заказчику', 'Pitches::create', array('class' => 'mainpage-create-project'))?><br>
              <span>создайте проект для дизайнеров</span>
          </div>
      </div>

    <ul class="front_catalog">
      <li>
          <?php if(count($promos) > 0):
          $promo = $promos->current();
          ?>
          <img src="<?=$promo->weburl?>" alt="" />
          <a class="more_info" href="/pitches/view/<?=$promo->solution->pitch_id?>" style="">
              <p><span><?=(int)$promo->solution->pitch->days?></span><br><?=$this->numInflector->formatString((int)$promo->solution->pitch->days, array(
                  'first' => 'день',
                  'second' => 'дня',
                  'third' => 'дней'
              ))?></p>
              <p><span><?=(int)$promo->solution->pitch->ideas_count?></span><br><?=$this->numInflector->formatString((int)$promo->solution->pitch->ideas_count, array(
                  'string' => 'решен',
                  'first' => 'ие',
                  'second' => 'ия',
                  'third' => 'ий'
              ))?></p>
              <p><span><?=(int)$promo->solution->pitch->price?>.-</span><br><?=$this->numInflector->formatString((int)$promo->solution->pitch->price, array(
                  'string' => 'рубл',
                  'first' => 'ь',
                  'second' => 'я',
                  'third' => 'ей'
              ))?></p>
          </a>
        <?php endif?>
        </li>
      <li style="height:259px;">
          <?php if(count($promos) > 1):
            $promo = $promos->next();
            ?>
          <img src="<?=$promo->weburl?>" alt="" />
          <a class="more_info" href="/pitches/view/<?=$promo->solution->pitch_id?>" style="">
              <p><span><?=(int)$promo->solution->pitch->days?></span><br><?=$this->numInflector->formatString((int)$promo->solution->pitch->days, array(
                  'first' => 'день',
                  'second' => 'дня',
                  'third' => 'дней'
              ))?></p>
              <p><span><?=(int)$promo->solution->pitch->ideas_count?></span><br><?=$this->numInflector->formatString((int)$promo->solution->pitch->ideas_count, array(
                  'string' => 'решен',
                  'first' => 'ие',
                  'second' => 'ия',
                  'third' => 'ий'
              ))?></p>
              <p><span><?=(int)$promo->solution->pitch->price?>.-</span><br><?=$this->numInflector->formatString((int)$promo->solution->pitch->price, array(
                  'string' => 'рубл',
                  'first' => 'ь',
                  'second' => 'я',
                  'third' => 'ей'
              ))?></p>
          </a>
          <?php endif?>
        </li>
      <li>
       <?php $randonBanner = mt_rand(1, 3);?>
        <div style="height:261px;width:260px" id="bannerblock">
            <div id="special_banner" style="position: absolute; <?php if($randonBanner != 1) echo 'display:none;'?>">
                <img src="/img/vernem_dengi_feb-02.png" alt="" />
                <a class="more_info" href="/pages/special"></a>
            </div>
            <div id="brief_banner" style="position: absolute; <?php if($randonBanner != 2) echo 'display:none;'?>">
                <img src="/img/brief.png" alt="" />
                <a class="more_info" href="/pages/brief"></a>
            </div>
            <div id="referal_banner" style="position: absolute; <?php if($randonBanner != 3) echo 'display:none;'?>">
                <img src="/img/banner-referal.jpg" alt="" />
                <a class="more_info" href="/pages/referal"></a>
            </div>
        </div>
      </li>
    </ul>

    <div class="use_table">
        <div id="pitch-table" style="height:280px;">
              <div class="to_use">
                  <?=$this->html->link('Используя<br> сообщество<br> дизайнеров<br>в сети, Создайте<br> лого, название,<br> упаковку, сайт, <br> визитку, etc ...', 'Pages::howitworks', array('class' => '', 'id' => 'to_use_text', 'escape' => false))?>
              </div>
              <div class="wap_table">
                <table class="spec_table">
                  <tr>
                    <th style="font-size:11px;color:#666666;">текущие проекты</th>
                    <th class="price_th" style="font-size:11px;color:#666666;">цена</th>
                    <th class="idea__th" style="font-size:11px;color:#666666;">идей</th>
                    <th class="term_th" style="font-size:11px;color:#666666;">Срок</th>
                  </tr>
                  <?php
                  $counter =1;
                  foreach($pitches as $pitch):
                  if(($counter % 2) == 1) {
                    $class = 'odd';
                  }else {
                    $class = 'even';
                  }
                  if(($pitch->status == '1') && ($pitch->awarded == 0)) {
                      $timeleft = 'Выбор победителя';
                  }else {
                      $timeleft = $pitch->startedHuman;
                  }
                  ?>
                  <tr class="<?=$class?>">
                    <td class="pitches-name">
                    <?=$this->html->link($this->PitchTitleFormatter->renderTitle($pitch->title, 80), array('controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id), array('class' => 'expand-link'))?><br>
                    <!--span><?=$pitch->industry?></span-->
                    </td>
                    <td><?=$this->moneyFormatter->formatMoney($pitch->price)?></td>
                    <td><?=$pitch->ideas_count?></td>
                    <td><?=$timeleft?></td>
                  </tr>
                  <?php
                  $counter++;
                  endforeach;?>
                </table>

              </div>
        </div>
        <div style="margin-left: 61px; margin-top: -55px;">
            <?=$this->html->link('Подробнее', 'Pages::howitworks', array('class' => 'new_more', 'id' => 'to_use_link'))?>
            <?php //$this->html->link('показать все питчи', 'Pitches::index', array('class' => 'more', 'style' => 'float: right; border-right-width: 22px; margin-right: 52px;'))?>
        </div>
    </div>
    <?php
    $imageArray = array(
        1 => '/img/4.jpg',
        3 => '/img/jara_174.png',
        2 => '/img/6.jpg',
        4 => '/img/chern.jpg',
        5 => '/img/experts/nesterenko174.jpg',
        6 => '/img/experts/efremov174.jpg',
        7 => '/img/experts/percia_174.png',
        8 => '/img/experts/makarov_dmitry_174.png',
    );
    $expertsDB = $experts->data();
    ?>

    <div class="experts-main">
      <ul id="experts-zone">
        <?php foreach($experts->data() as $expert): if ($expert['enabled'] == 0) continue; ?>
        <li class="expert-<?=$expert['id']?> expert_enabled" data-expert_id="<?=$expert['id']?>" style="display:none;">
            <?=$this->html->link('<img style="width: 174px; height: 174px;" src="'. $imageArray[$expert['id']] .'" alt="" />', array('Experts::view', 'id' => $expert['id']), array('data-id' => $expert['id'], 'escape' => false))?>
            <p><?=$this->html->link($expert['name'], array('Experts::view', 'id' => $expert['id']), array('data-id' => $expert['id']))?></p>
            <span><?php echo strip_tags($expert['title'])?></span>
        </li>
        <?php endforeach;?>
      </ul>
      <div class="about_ex">
          <?=$this->html->link('Опытные эксперты будут рады помочь вам с выбором варианта...', 'Experts::index', array('class' => 'experts_text','id' => 'experts_text', 'style' => 'color: #666666;'));?>
          <div style="margin-top:10px">
            <?=$this->html->link('подробнее', 'Experts::index', array('class' => 'new_more', 'id' => 'experts_link'))?>
          </div>
      </div>
    </div>


    <div class="logosale">
        <div class="logosale_content">
            <p><span class="highlight"><?= $totalCount ?></span> отборных логотипов<br> из завершенных проектов в распродаже</p>
            <div class="logosale_search-block">
                <form id="logosale_form" method="get" action="/logosale">
                    <input type="text" name='search' placeholder="Найдите логотип по ключевому слову" class="">
                    <a href="#" class="button third clean-style-button">Поиск</a>
                </form>
            </div>
        </div>
    </div>

        <?php
          $grade1 = $grades->first();
          $grade2 = $grades->next();
        ?>
    <?php if(($grade1) && ($grade2)):
    $avatar1 = '/img/default_small_avatar.png';
    $avatar2 = '/img/default_small_avatar.png';
    if(!empty($grade1->user->images)):
      $avatar1 = $grade1->user->images['avatar_small']['weburl'];
    endif;
    if(!empty($grade2->user->images)):
      $avatar2 = $grade2->user->images['avatar_small']['weburl'];
    endif;
    ?>
    <div>
        <h2 class="largest-header" style="text-align: left; margin-left:65px; text-transform: uppercase;">Отзывы</h2>
        <div style="width:959px;height:166px;margin-bottom:18px;margin-top:12px;">

          <div class="talkhoverzone" style="float:left;height:144px;width:398px;padding-left:66px;padding-top:22px">
            <div style="float:left;width:50px;height:142px;padding-top: 2px;">
            <img src="<?= $avatar1 ?>" alt="" style="border: 4px solid #cecece;">
            </div>
            <div  style="float:left;width:320px;padding-left:12px;height:142px;">
                <p class="regular" style=""><a target="_blank" href="/pitches/view/<?=$grade1->pitch->id?>" style="font: 17px RodeoC;color:#666666;text-decoration:none;"><?=$grade1->user->first_name . ' ' . $grade1->user->last_name?></a></p>
                <p class="regular" style="font-style:italic; font-size:14px; font-family: Georgia;"><a target="_blank" href="/pitches/view/<?=$grade1->pitch->id?>" style="color:#666666;text-decoration:none;"><?=$grade1->pitch->title?></a></p>
                <p class="regular regular_officina" style="color:#666666;margin-top:16px;">«<?php if($grade1->short != ''): echo $grade1->short; else: echo $grade1->text; endif?>»</p>
            </div>

          </div>
          <div class="talkhoverzone" style="float:left;height:144px;width:398px;padding-left:22px;padding-top:22px">
            <div  style="float:left;width:50px;height:142px;padding-top: 2px;">
            <img src="<?= $avatar2 ?>" alt="" style="border: 4px solid #cecece;">
            </div>
            <div style="float:left;width:320px;padding-left:12px;height:142px;">
                <p class="regular" style="font-size: 17px;"><a target="_blank" href="/pitches/view/<?=$grade2->pitch->id?>" style="font:17px RodeoC;color:#666666;text-decoration:none;"><?=$grade2->user->first_name . ' ' . $grade2->user->last_name?></a></p>
                <p class="regular" style="font-style:italic; font-size:14px; font-family: Georgia;"><a target="_blank" href="/pitches/view/<?=$grade2->pitch->id?>" style="color:#666666;text-decoration:none;"><?=$grade2->pitch->title?></a></p>
                <p class="regular regular_officina" style="color:#666666;margin-top:16px;">«<?php if($grade2->short != ''): echo $grade2->short; else: echo $grade2->text; endif?>»</p>
            </div>

          </div>
        </div>
    </div>
    <div class="clear" style="clear:both; height:3px; margin-bottom:12px;"></div>
    <?php endif?>

    <div class="statistika">
      <dl class="dl_1">
        <dt><?=$statistic['numOfSolutionsPerProject'][$category_id]?></dt>
        <?php switch($category_id):
            case 1: $string = '<dd>среднее кол-во <br>идей в категории<br>«логотип»</dd>'; break;
            case 3: $string = '<dd>среднее кол-во <br>идей в категории<br>«сайт»</dd>'; break;
            case 7: $string = '<dd>среднее кол-во <br>идей в категории<br>«копирайтинг»</dd>'; break;
        endswitch?>
        <?php echo $string; ?>
      </dl>
      <dl class="dl_2">
        <dt><?=$statistic['numOfCurrentPitches']?></dt>
        <dd>текущих<br> проектов</dd>
      </dl>
      <dl class="dl_3">
        <dt><a href="#" style="color: #f9f9f9;"><?=$this->moneyFormatter->formatMoney($statistic['totalAwards'], array('suffix' => ''))?></a></dt>
        <dd>заработанных<br> дизайнерами рублей</dd>
      </dl>
      <dl class="dl_4">
        <dt><?=$this->moneyFormatter->formatMoney($statistic['totalParticipants'], array('suffix' => ''))?></dt>
        <dd>дизайнеров и копирайтеров зарегистрировано на сайте</dd>
      </dl>
      <dl class="dl_5">
        <dt><?=$statistic['lastDaySolutionNum']?></dt>
        <dd>новых идей загружено за последние 24 часа</dd>
      </dl>
    </div>
      <div style="height: 4px; background-color: rgb(204, 204, 204); clear: both; margin-top: -15px; margin-left: 0px;"></div>
      <section id="clients-logos"></section>
      <script>
          var logos = [
              {
                  title: "ОАО «АК «ТРАНСАЭРО»",
                  id: 105061,
                  imageOn: "/img/partners/transaero_on.png",
                  imageOff: "/img/partners/transaero.png",
                  width: 212,
                  paddingTop: 18
              },
              {
                  title: "Лаборатория Касперского",
                  id: 104724,
                  imageOn: "/img/partners/kaspersky_on.png",
                  imageOff: "/img/partners/kaspersky.png",
                  width: 187,
                  paddingTop: 14
              },
              {
                  title: "Trendsbrands.ru",
                  id: 100162,
                  imageOn: "/img/partners/trends_on.png",
                  imageOff: "/img/partners/trends.png",
                  width: 110,
                  paddingTop: 0
              },
              {
                  title: "Цукерберг позвонит",
                  id: 100072,
                  imageOn: "/img/partners/zucker_on.png",
                  imageOff: "/img/partners/zucker.png",
                  width: 253,
                  paddingTop: 0
              }
          ];
      </script>

    <ul class="bottom_menu">
        <li><a href="/pitches/1?type=finished">логотипы</a> /</li>
        <li><a href="/pitches/5?type=finished">фирменный стиль</a> /</li>
        <li><a href="/pitches/3?type=finished">сайт</a> /</li>
        <li><a href="/pitches/2?type=finished">web-баннер</a> /</li>
        <li><a href="/pitches/11?type=finished">упаковка</a> /</li>
        <li><a href="/pitches/7?type=finished">копирайтинг</a> /</li>
        <li><a href="/pitches/8?type=finished">презентация</a> /</li>
        <li><a href="/pitches/9?type=finished">иллюстрация</a> /</li>
        <li><a href="/pitches/12?type=finished">реклама</a> /</li>
        <li><a href="/pitches/4?type=finished">флаер</a> /</li>
        <li><a href="/pitches/10?type=finished">другое...</a></li>
    </ul>
  </div>
</div><!-- .wrapper -->

<div id="popup-final-step" class="popup-final-step" style="display:none; padding-left: 0px; height: 390px;">
    <div id="ytplayer"></div>
    <script>
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/player_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        var player;
        function onYouTubePlayerAPIReady() {
            player = new YT.Player('ytplayer', {
                height: '390',
                width: '640',
                videoId: '3bhLkorXLI8',
                playerVars: {"autoplay": '0'}
            });
        }
    </script>
</div>

<?=$this->html->script(array(
    'slides.min.jquery',
    'jquery.simplemodal-1.4.2.js',
    '/js/pages/home/ClientLogo.js',
    '/js/pages/home/ClientsLogosShowcase.js',
    'pages/main.js'
), array('inline' => false))?>
<?=$this->html->style(array('/css/main_page.css', '/css/pages/home.css'), array('inline' => false))?>