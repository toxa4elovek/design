<!-- start: Solution overlay -->
<div class="solution-overlay-dummy solution-sale" style="display: none;">
    <!-- start: Solution Container -->
    <div class="solution-container">
        <div class="solution-nav-wrapper">
            <div class="solution-prev"></div>
            <a class="solution-prev-area" href="#"></a>
            <div class="solution-next"></div>
            <a class="solution-next-area" href="#"></a>
        </div>
        <!-- start: Solution Right Panel -->
        <div class="solution-right-panel">
            <div class="solution-popup-close"></div>
            <div class="solution-info solution-summary">
                <div class="solution-number">#<span class="number isField"><!--  --></span></div>
                <div style="height: 180px;margin-top: 20px;margin-bottom: 20px;">
                    <aside class="summary-price expanded" style="position: relative; top: 0; left: 0; margin-left: 0;">
                        <h3>Итого:</h3>
                        <p class="summary"><strong id="total-tag">
                                <?php if($this->user->isSubscriptionActive()):?>
                                7500р.-
                                <?php else: ?>
                                9500р.-
                                <?php endif ?>
                            </strong></p><!-- .summary -->
                        <ul id="check-tag">
                        </ul>
                        <div class="hide">
                            <span id="to-pay">Перейти к оплате</span>
                        </div>
                    </aside><!-- .summary-price -->
                    <!-- end: Solution Left Panel -->
                </div>
                <div class="solution-rating"><div class="rating-image star0"></div> рейтинг заказчика</div>
            </div>
            <div class="separator"></div>
            <div class="solution-info solution-author chapter">
                <h2>АВТОР</h2>
                <img class="author-avatar" src="/img/default_small_avatar.png" alt="Портрет автора" />
                <a class="author-name isField" href="#"><!--  --></a>
                <div class="author-from isField"><!--  --></div>
                <div class="clr"></div>
            </div>
            <div class="separator"></div>
            <div class="solution-info solution-about chapter">
                <h2>О РЕШЕНИИ</h2>
                <span id="date" style="color:#878787;">Опубликовано </span><br/>
                <span class="solution-description isField"><!--  --></span><a class="description-more">… Подробнее</a>
            </div>
            <div class="separator"></div>
            <div class="solution-copyrighted"><!--  --></div>
            <div class="solution-info">
                <table class="solution-stat">
                    <col class="icon">
                    <col class="description">
                    <col class="value">
                    <tr>
                        <td class="icon icon-eye"></td>
                        <td>Просмотры</td>
                        <td class="value-views isField"><!--  --></td>
                    </tr>
                    <tr>
                        <td class="icon icon-thumb"></td>
                        <td>Лайки</td>
                        <td class="value-likes isField"><!--  --></td>
                    </tr>
                    <tr>
                        <td class="icon icon-comments"></td>
                        <td>Комментарии</td>
                        <td class="value-comments isField"><!--  --></td>
                    </tr>
                </table>
            </div>
            <div class="separator"></div>
            <!--div class="solution-info solution-tags chapter">
                <h2>ТЕГИ</h2>
                <ul class="tags">
                </ul>
                <div class="clear"></div>
            </div-->
            <!--div class="separator"></div-->
            <div class="solution-info solution-share chapter">

            </div>
            <div class="separator"></div>
            <div class="solution-info solution-abuse isField"><!--  --></div>
            <div class="clr"></div>
            <!-- end: Solution Right Panel -->
        </div>
        <!-- start: Solution Left Panel -->
        <div class="solution-left-panel">
            <div class="solution-title" href="#">
                <h1 class="logosale-h1">

                </h1>
            </div>
            <!-- start: Soluton Images -->
            <section class="solution-images isField">
                <div style="text-align:center;height:220px;padding-top:180px"><img alt="" src="/img/blog-ajax-loader.gif"></div>
                <!-- end: Solution Images -->
            </section>
            <section class="allow-comments">
                <div class="all_messages">
                    <div class="clr"></div>
                </div>
                <div class="separator full"></div>

            </section>
            <!-- start: Comments -->
            <section class="solution-comments isField">

                <!-- end: Comments -->
            </section>
            <!-- end: Solution Container -->
        </div>

        <?= $this->view()->render(array('element' => 'logosalepay'), array('data' => $data)) ?>

    </div><!-- .main -->  
    <!-- end: Solution overlay -->
</div>
