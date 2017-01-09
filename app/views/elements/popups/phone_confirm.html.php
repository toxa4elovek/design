<!-- Start: Phone Confirm -->
<div id="phone-confirm">
    <h2>Подтвердите номер</h2>
    <p class="regular top-text">
        Пожалуйста, укажите сотовый для экстренной связи.<br/> Команда GoDesignеr не разглашает данные третьим<br/> лицам и никогда не использует номер для спама.
    </p>
    <section class="user-mobile-section">
        <form method="post" id="mobile-form" action="/users/update" style="margin-top: 0;">
            <p class="confirm-message" style="margin-top: 0;"></p>
            <div class="phone-input-container" <?php if (!empty($user->phone) && $user->phone_valid == 1):?>style="display:none;"<?php endif?> <?php if (!empty($user->phone) && $user->phone_valid == 0):?>style="display:none;"<?php endif?>>
                <span class="plus">+</span>
                <input type="text" name="phone" placeholder="79121234567" style="height: 51px; width: 310px; display: block;">
            </div>
            <div class="clear"></div>
            <div>
                <input type="text" name="phone_code" />
                <input type="submit" id="confirm-mobile" class="button clean-style-button" value="Подтвердить код" style="margin-top:1px;width: 197px;
height: 41px;margin-left: 26px;">
            </div>
            <div class="clear"></div>
            <a href="#" style="margin-top: 13px; width: 140px; height: 18px; display: none" class="resend-code">Выслать код повторно</a>
            <ul style="margin-top: 30px;">
                <li class="number"></li>
                <li class="remove-number"><a href="#" class="remove-number-link">Удалить номер</a></li>
            </ul>
            <input type="submit" id="save-mobile" class="button" value="Подтвердить телефон" style="margin-top:23px;width: 219px;height: 49px;">
            <div class="clear"></div>
            <p class="help" style="margin-top: 40px; font-family: Georgia, serif; font-size: 13px; font-style: italic; line-height: 16px;">Свяжитесь с нами, если  не получается подтвердить номер:<br/>
                <a href="mailto:team@godesigner.ru">team@godesigner.ru</a> или (812) 648-24-12 по будням с 10–17<br/> по Москве</p>
        </form>
    </section>
    <div class="mobile-close" style="display:none"></div>
</div>
<!-- End: Phone Confirm -->
