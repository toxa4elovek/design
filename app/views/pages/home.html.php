<div class="wrapper auth login" style="background:none;">

  <?=$this->view()->render(array('element' => 'header'))?>
  <div style="clear:both"></div>
  <div id="slides">
    <div class="slides_container" style="height:300px;">
      <div class="slide">
      <a href="/pages/howitworks"><img src="/img/1_BANNER.png" alt="" /></a>
      </div>
      <div class="slide">
          <a href="/pages/howitworks"><img src="/img/2_BANNER.png" alt="" /></a>
      </div>
        <div class="slide">
            <a href="/pages/howitworks"><img src="/img/3_BANNER.png" alt="" /></a>
        </div>
      <!--div class="slide">
       <a href="#" class="b_img_slide"><img src="img/4.png" alt="" /></a>
       <a href="#" class="s_img_slide"><img src="img/5.png" alt="" /></a>
       <a href="/pages/howitworks"><img src="/img/2_BANNER.png" alt="" /></a>
      </div>
      <div class="slide">
       <a href="#" class="b_img_slide"><img src="img/4.png" alt="" /></a>
       <a href="#" class="s_img_slide"><img src="img/5.png" alt="" /></a>
       <a href="/pages/howitworks"><img src="/img/3_BANNER.png" alt="" /></a>
      </div-->
    </div>
    <a id="finished" href="/pitches/?type=finished" style="height:32px;width:173px;position: absolute; top: 251px; left: 79px; z-index:101;background-image:url(/img/examples_173_32_red.png)"><img src="/img/examples_173_32.png" alt="Просмотреть примеры"></a>
    <a id="video" href="#" style="height:32px;width:190px;position: absolute; top: 251px; left: 242px; z-index:101;background-image:url(/img/video_red_173_32.png)"><img src="/img/video_173_32.png" alt="Просмотреть примеры"></a>
    <a href="#" class="prev"><img src="img/arrow-prev.png"  alt="Arrow Prev"></a>
    <a href="#" class="next"><img src="img/arrow-next.png"  alt="Arrow Next"></a>
  </div>

  <div class="main_page_content">
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
          <?php else:?>
            <img src="/img/banner_mison.png" alt="" />
            <a class="more_info" href="/pitches/view/100043" style="background: url('/img/banner_mison_press.png') repeat scroll left top transparent"></a>
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
    <?php else:?>
          <img src="/img/visitcard_parsuna.png" alt="" />
       <a class="more_info" href="/pitches/view/45" style="background: url(/img/visitcard_parsuna_banner_onpress.png) repeat scroll left top transparent"></a>
          <?php endif?>
        </li>
      <li>
       <!--img src="/img/special_offer_banner_260X260.jpg" alt="" /-->
       <?php
       if(mt_rand(0,1) == 0) {
        $specialShow = true;
        $briefShow = false;
       }else {
        $specialShow = false;
        $briefShow = true;
       }

       ?>
       <div style="height:261px;width:260px" id="bannerblock">
        <div id="special_banner" style="position: absolute; <?php if($specialShow == false) echo 'display:none;'?>">
       <img src="/img/vernem_dengi_feb-02.png" alt="" />
       <a id="special_link" class="more_info" href="/pages/special" style="background: url(/img/banner_onpress.png) repeat scroll left top transparent">

       </a></div>
       <div id="brief_banner" style="position: absolute; <?php if($briefShow == false) echo 'display:none;'?>">
       <img src="/img/brief.png" alt="" />
       <a id="brief_link" class="more_info" href="/pages/brief" style="background: url(/img/banner_onpress.png) repeat scroll left top transparent">

       </a>
        </div></div>
      </li>
    </ul>
    <div class="take_fill_block">
      <div class="take">
        <?=$this->html->link('Дизайнеру', 'Pitches::index')?><br>
        <span>предложите идею заказчику</span>
      </div>
      <div class="fill">
        <?=$this->html->link('Заказчику', 'Pitches::create')?><br>
        <span>создайте питч для дизайнеров</span>
      </div>
    </div>

    <div class="use_table">
        <div id="pitch-table" style="height:280px;">
              <div class="to_use">
                  <?=$this->html->link('Используя<br> сообщество<br> дизайнеров<br>в сети, Создайте<br> лого, буклет,<br> упаковку, сайт, <br> визитку, etc ...', 'Pages::howitworks', array('class' => '', 'id' => 'to_use_text', 'escape' => false))?>
              </div>
              <div class="wap_table">
                <table class="spec_table">
                  <tr>
                    <th style="font-size:11px;color:#666666;">текущие питчи</th>
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
                    <?=$this->html->link($pitch->title, array('controller' => 'pitches', 'action' => 'view', 'id' => $pitch->id), array('class' => 'expand-link'))?><br>
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
        <div style="margin-left: 61px;">
            <?=$this->html->link('Как это работает?', 'Pages::howitworks', array('class' => 'more', 'id' => 'to_use_link'))?>
            <?=$this->html->link('показать все питчи', 'Pitches::index', array('class' => 'more', 'style' => 'float: right; border-right-width: 22px; margin-right: 52px;'))?>
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
        <?php foreach($experts->data() as $expert):?>
        <li class="expert-<?=$expert['id']?>" style="display:none;">
            <?=$this->html->link('<img style="width: 174px; height: 174px;" src="'. $imageArray[$expert['id']] .'" alt="" />', array('Experts::view', 'id' => $expert['id']), array('data-id' => $expert['id'], 'escape' => false))?>
            <p><?=$this->html->link($expert['name'], array('Experts::view', 'id' => $expert['id']), array('data-id' => $expert['id']))?></p>
            <span><?php echo strip_tags($expert['title'])?></span>
        </li>
        <?php endforeach;?>
      </ul>
      <div class="about_ex">
          <?=$this->html->link('Опытные эксперты будут рады помочь вам с выбором варианта...', 'Experts::index', array('class' => 'experts_text','id' => 'experts_text', 'style' => 'color: #666666;'));?>
          <div style="margin-top:10px">
            <?=$this->html->link('подробнее', 'Experts::index', array('class' => 'more', 'id' => 'experts_link'))?>
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
        <h2 class="largest-header" style="text-align: left; margin-left:64px;">Отзывы</h2>
        <div style="width:959px;height:166px;background-image:url(/img/peopletalk.png);margin-bottom:18px;margin-top:12px;">

          <div class="talkhoverzone" style="float:left;height:144px;width:398px;padding-left:81px;padding-top:22px">
            <div style="float:left;width:50px;height:142px;padding-top: 2px;">
            <img src="<?=$avatar1?>" alt="" style="border: 4px solid #a7a7a7;">
            </div>
            <div  style="float:left;width:320px;padding-left:12px;height:142px;">
                <p class="regular" style=""><a target="_blank" href="/pitches/view/<?=$grade1->pitch->id?>" style="font: 15px RodeoC;color:#666666;text-decoration:none;"><?=$grade1->user->first_name . ' ' . $grade1->user->last_name?></a></p>
                <p class="regular" style="font-style:italic;font-size:14px;"><a target="_blank" href="/pitches/view/<?=$grade1->pitch->id?>" style="color:#666666;text-decoration:none;"><?=$grade1->pitch->title?></a></p>
                <p class="regular" style="font-size:14px;color:#666666;margin-top:4px;">«<?php if($grade1->short != ''): echo $grade1->short; else: echo $grade1->text; endif?>»</p>
            </div>

          </div>
          <div class="talkhoverzone" style="float:left;height:144px;width:398px;padding-left:22px;padding-top:22px">
            <div  style="float:left;width:50px;height:142px;padding-top: 2px;">
            <img src="<?=$avatar2?>" alt="" style="border: 4px solid #a7a7a7;">
            </div>
            <div style="float:left;width:320px;padding-left:12px;height:142px;">
                <p class="regular" style=""><a target="_blank" href="/pitches/view/<?=$grade2->pitch->id?>" style="font:15px RodeoC;color:#666666;text-decoration:none;"><?=$grade2->user->first_name . ' ' . $grade2->user->last_name?></a></p>
                <p class="regular" style="font-style:italic;font-size:14px;"><a target="_blank" href="/pitches/view/<?=$grade2->pitch->id?>" style="color:#666666;text-decoration:none;"><?=$grade2->pitch->title?></a></p>
                <p class="regular" style="font-size:14px;color:#666666;margin-top:4px;">«<?php if($grade2->short != ''): echo $grade2->short; else: echo $grade2->text; endif?>»</p>
            </div>

          </div>
        </div>
    </div>
    <div class="clear" style="clear:both; height:3px; background:url('/img/sep.png') no-repeat scroll 62px bottom transparent;margin-bottom:12px;"></div>
    <?php endif?>

    <div class="statistika">
      <dl class="dl_1">
        <dt><?=$numOfSolutionsPerProject?></dt>
        <dd>Решений <br>на проект</dd>
      </dl>
      <dl class="dl_2">
        <dt><?=$numOfCurrentPitches?></dt>
        <dd>текущих<br> питчей</dd>
      </dl>
      <dl class="dl_3">
        <dt><?=$this->moneyFormatter->formatMoney($totalAwards)?></dt>
        <dd>заработанных<br> дизайнерами денег</dd>
      </dl>
      <dl class="dl_4">
        <dt><?=$this->moneyFormatter->formatMoney($totalWaitingForClaim)?></dt>
        <dd>в ожидании победителей</dd>
      </dl>
      <dl class="dl_5">
        <dt><?=$this->moneyFormatter->formatMoney($totalAwardsValue)?></dt>
        <dd>общий бюджет прошедший через GoDesigner</dd>
      </dl>
    </div>

    <ul class="logos">
        <li style="width:128px;">
          <a target="_blank" style="opacity:1;position:relative;z-index:2;" class="hoverlogo" href="/pitches/view/47" data-off="/img/partners/logo_tutdesign.png" data-on="/img/partners/logo_tutdesign_on.png"><img class="tutdesign" src="/img/partners/logo_tutdesign.png" alt="" /></a>
          <a target="_blank" style="opacity:0;position:relative;bottom:54px;z-index:1;" class="nonhoverlogo" href="/pitches/view/47" data-off="/img/partners/logo_tutdesign.png" data-on="/img/partners/logo_tutdesign_on.png"><img class="tutdesign" src="/img/partners/logo_tutdesign_on.png" alt="" /></a>
        </li>
        <li style="width:148px;">
          <a target="_blank" style="opacity:1;position:relative;z-index:2;" class="hoverlogo" href="/pitches/view/100079" data-off="/img/partners/surfinbird.png" data-on="/img/partners/surfinbird_on.png"><img class="surfin" src="/img/partners/surfinbird.png" alt="" /></a>
          <a target="_blank" style="opacity:0;position:relative;bottom:60px;z-index:1;" class="nonhoverlogo" href="/pitches/view/100079" data-off="/img/partners/surfinbird.png" data-on="/img/partners/surfinbird_on.png"><img class="surfin" src="/img/partners/surfinbird_on.png" alt="" /></a>
        </li>
        <li style="width:99px;">
          <a target="_blank" style="opacity:1;position:relative;z-index:2;" class="hoverlogo" href="/pitches/view/100075" data-off="/img/partners/play.png" data-on="/img/partners/play_on.png"><img class="yota" src="/img/partners/play.png" alt="" /></a>
          <a target="_blank" style="opacity:0;position:relative;bottom:50px;z-index:1;" class="nonhoverlogo" href="/pitches/view/100075" data-off="/img/partners/play.png" data-on="/img/partners/play_on.png"><img class="yota" src="/img/partners/play_on.png" alt="" /></a></li>

        <li style="width:253px;">
          <a target="_blank" style="opacity:1;position:relative;z-index:2;" class="hoverlogo" href="/pitches/view/100072" data-off="/img/partners/zucker.png" data-on="/img/partners/zucker_on.png"><img class="zucker" src="/img/partners/zucker.png" alt="" /></a>
          <a target="_blank" style="opacity:0;position:relative;bottom:59px;z-index:1;" class="nonhoverlogo" href="/pitches/view/100072" data-off="/img/partners/zucker.png" data-on="/img/partners/zucker_on.png"><img class="zucker" src="/img/partners/zucker_on.png" alt="" /></a>
        </li>
        <li style="width:110px;" class="logolast">
          <a target="_blank" style="opacity:1;position:relative;z-index:2;" class="hoverlogo" href="/pitches/view/100162" data-off="/img/partners/trends.png" data-on="/img/partners/trends_on.png"><img class="brands" src="/img/partners/trends.png" alt="" /></a>
          <a target="_blank" style="opacity:0;position:relative;bottom:72px;z-index:1;" class="nonhoverlogo" href="/pitches/view/100162" data-off="/img/partners/trends.png" data-on="/img/partners/trends_on.png"><img class="brands" src="/img/partners/trends_on.png" alt="" /></a>
        </li>
    </ul>

    <ul class="bottom_menu">
        <li><a href="/pitches/1?type=finished">логотипы</a> /</li>
        <li><a href="/pitches/5?type=finished">фирменный стиль</a> /</li>
        <li><a href="/pitches/3?type=finished">сайт</a> /</li>
        <li><a href="/pitches/2?type=finished">web-баннер</a> /</li>
        <li><a href="/pitches/11?type=finished">упаковка</a> /</li>
        <li><a href="/pitches/7?type=finished">копирайтинг</a> /</li>
        <li><a href="/pitches/8?type=finished">буклет</a> /</li>
        <li><a href="/pitches/9?type=finished">иллюстрация</a> /</li>
        <li><a href="/pitches/12?type=finished">реклама</a> /</li>
        <li><a href="/pitches/4?type=finished">флаер</a> /</li>
        <li><a href="/pitches/10?type=finished">другое...</a></li>
    </ul>
  </div>
</div><!-- .wrapper -->

<div id="popup-final-step" class="popup-final-step" style="display:none">
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

<div id="socials-modal" style="overflow: visible; display: none; width: 700px; background: #282A34;">
    <div style="position: absolute; top: 460px; left: 25px; width: 652px; height: 10px; background: #454650; box-shadow: 0 5px 5px rgba(0,0,0,.4);"></div>
    <div style="position: relative; height: 70px; width: 100%; background: url('img/go-social-header.png') no-repeat top left #282a34; box-shadow: inset 0 -1px 0 rgba(39,41,50,1);">
        <a class="close-request" style="float: right; color: rgb(100, 143, 164); font-size: 12px; padding-right: 20px; background: url('/img/closerequest.png') no-repeat 50px center; margin-top: 30px; margin-right: 20px;" href="#">закрыть</a>
    </div>
    <div style="position: relative; height: 390px; background: url('img/404_bg.png') top center no-repeat; background-size: cover; box-shadow: inset 0 1px 0 rgba(45,47,56,1), 0 5px 5px rgba(0,0,0,.4);">
        <!-- Start: VK -->
        <script src="http://vk.com/js/api/openapi.js" type="text/javascript"></script>
        <!-- VK Widget -->
        <div id="vk_groups" style="float: left; margin: 40px 30px 0 20px;"></div>
        <!-- End: VK -->

        <!-- Start: FB -->
        <div class="fb-like-box" style="float: left; margin: 40px 20px 0 30px; background: white;" data-href="https://www.facebook.com/pages/Go-Designer/160482360714084" data-width="300" data-height="290" data-show-faces="true" data-header="false" data-stream="false" data-show-border="false"></div>
        <!-- End: FB -->
    </div>
</div>

<?=$this->html->script(array('slides.min.jquery', 'jquery.simplemodal-1.4.2.js', 'pages/main.js'), array('inline' => false))?>
<?=$this->html->style(array('/new_css'), array('inline' => false))?>