<!-- Comment Delete Moderation -->
<div id="popup-delete-comment" class="popup-warn generic-window" style="display:none">
    <br>
    <form id="delete-comment-form" action="/moderations/add" method="post">
        <h1 class="largest-header regular">Удалить комментарий</h1>
        <ul>
            <li>
                <h2>Причина</h2>
                <label><input type="radio" name="reason" value="critique" required>публичная критика</label><br>
                <label><input type="radio" name="reason" value="link">Ссылка</label><br>
                <label><input type="radio" name="reason" value="other">Другое</label>
            </li>
            <li>
                <h2>Срок</h2>
                <label><input type="radio" name="penalty" value="10" required>10 дней</label><br>
                <label><input type="radio" name="penalty" value="30">30 дней</label><br>
                <label><input type="radio" name="penalty" value="90">90 дней</label>
            </li>
            <li>
                <h2>&nbsp;</h2>
                <label><input type="radio" name="penalty" value="0">Без штрафа</label><br>
                <label><input type="radio" name="penalty" value="1">Заблокировать</label><br>
            </li>
        </ul>
        <textarea id="explanation" name="explanation" form="delete-comment-form" class="placeholder" placeholder="Комментарий"></textarea>
        <input type="hidden" name="model" id="model" value="comment" />
        <input type="hidden" name="model_id" id="model_id" value="" />
        <div class="final-step-nav wrapper" style="margin-top:20px;">
            <input type="submit" name="comment" class="button" id="sendDeleteComment" value="Отправить">
            <div class="moderation-spinner"></div>
        </div>
    </form>
</div>

<!-- Solution Delete Moderation -->
<div id="popup-delete-solution" class="popup-warn generic-window" style="display:none">
    <br>
    <form id="delete-solution-form" action="/moderations/add" method="post">
        <h1 class="largest-header regular">Удалить решение</h1>
        <p id="winner-warning" style="display: none; font-weight: bold; color: red;">Внимание! Решение является победителем в проекте!!!</p>
        <ul>
            <li>
                <h2>Причина</h2>
                <label><input type="radio" name="reason" value="plagiat" required>Плагиат</label><br>
                <label><input type="radio" name="reason" value="template">Использование шаблонов</label><br>
                <label><input type="radio" name="reason" value="other">Другое</label>
            </li>
            <li>
                <h2>Срок</h2>
                <label><input type="radio" name="penalty" value="10" required>10 дней</label><br>
                <label><input type="radio" name="penalty" value="30">30 дней</label><br>
                <label><input type="radio" name="penalty" value="90">90 дней</label>
            </li>
            <li>
                <h2>&nbsp;</h2>
                <label><input type="radio" name="penalty" value="0">Без штрафа</label><br>
                <label><input type="radio" name="penalty" value="1">Заблокировать</label><br>
                <label><input type="radio" name="penalty" value="2">Заблокировать на 30 дней</label><br>
                <label><input type="radio" name="penalty" value="3">Заблокировать в данном проекте</label><br>
            </li>
        </ul>
        <textarea id="explanation" name="explanation" form="delete-solution-form" class="placeholder" placeholder="ссылка на первоисточник или комментарий"></textarea>
        <input type="hidden" name="model" id="model" value="solution" />
        <input type="hidden" name="model_id" id="model_id" value="" />
        <input type="hidden" name="project_id" id="project_id" value="" />
        <div class="final-step-nav wrapper" style="margin-top:20px;">
            <input type="submit" class="button" id="sendDeleteSolution" value="Отправить">
            <div class="moderation-spinner"></div>
        </div>
    </form>
</div>
