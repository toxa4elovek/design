<!-- start: Solution overlay -->
<div class="solution-overlay-dummy" style="display: none;">
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
                <span id="date" style="color:#878787;">Опубликовано <?= $solution->created ?></span><br/>
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
            <div class="solution-info solution-tags chapter">
                <h2>ТЕГИ</h2>
                <ul class="tags">
                </ul>
                <div class="clear"></div>
            </div>
            <div class="separator"></div>
            <div class="solution-info solution-share chapter">

            </div>
            <div class="separator"></div>
            <div class="solution-info solution-abuse isField"><!--  --></div>
            <!-- end: Solution Right Panel -->
        </div>
        <!-- start: Solution Left Panel -->
        <div class="solution-left-panel">
            <a class="solution-title" href="/pitches/view/<?= $pitch->id ?>">
                <h1>
                    <?= $pitch->title ?>
                </h1>
            </a>
            <!-- start: Soluton Images -->
            <section class="solution-images isField">
                <div style="text-align:center;height:220px;padding-top:180px"><img alt="" src="/img/blog-ajax-loader.gif"></div>
                <!-- end: Solution Images -->
            </section>
            <section class="allow-comments">
                <div class="all_messages">
                    <div class="clr"></div>
                </div>
                <input type="hidden" value="<?= $pitch->category_id ?>" name="category_id" id="category_id">
                <?php if ($this->user->isPitchOwner($pitch->user->id) || $this->user->isAdmin()): ?>
                    <div class="separator full"></div>
                    <form class="createCommentForm" method="post" action="/comments/add">
                        <textarea id="newComment" data-user-autosuggest="true" name="text"></textarea>
                        <div></div>
                        <input type="hidden" value="" name="solution_id">
                        <input type="hidden" value="" name="comment_id">
                        <input type="hidden" value="<?= $pitch->id ?>" name="pitch_id">
                        <input type="button" src="/img/message_button.png" value="Публиковать комментарий для всех" class="button createComment" data-is_public="1" style="margin: 15px 18px 15px 0;">
                        <input type="button" src="/img/message_button.png" value="Отправить только дизайнеру" class="button createComment" data-is_public="0" style="margin: 15px 0 15px 18px;">
                        <div class="public-loader-container">
                            <img class="public-loader" src="/img/blog-ajax-loader.gif" alt="Идёт загрузка">
                        </div>
                        <div class="private-loader-container">
                            <img class="private-loader" src="/img/blog-ajax-loader.gif" alt="Идёт загрузка">
                        </div>
                        <div class="clr"></div>
                    </form>
                <?php endif; ?>
            </section>
            <!-- start: Comments -->
            <section class="solution-comments isField">

                <!-- end: Comments -->
            </section>
            <!-- end: Solution Left Panel -->
        </div>
        <div class="clr"></div>
        <!-- end: Solution Container -->
    </div>
    <!-- end: Solution overlay -->
</div>
