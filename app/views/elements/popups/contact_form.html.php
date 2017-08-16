<div id="loading-overlay2" style="overflow:visible;display:none;">
    <div style="width:486px;height:690px;padding-top:7px;background:url(/img/requesthelpform2.png);">
        <div id="reqmainform">
            <a class="close-request" style="color: rgb(100, 143, 164); font-size: 12px; padding-right: 20px; background: url('/img/closerequestform.png') no-repeat scroll 50px 0px transparent; margin-top: 0px; margin-left: 405px;" href="#">закрыть</a>
            <form id="requesthelp" method="post" action="/users/requesthelp.json" style="padding: 59px 45px 0 45px;">
                <input type="text" style="display:block;opacity:0.1;width:1px;height:1px;" name="reqfiller" value="">
                <input type="hidden" name="case" value="h4820g838f">

                <span style="font-size: 11px; color: #666;text-shadow: 0 1px 1px white">АДРЕСУЮ ЭТО...</span>
                <div style="display: flex; height: 43px; margin-top: 6px;">
                    <input type="text" class="i1" style="width: 100%; height: 100%;" id="reqto" name="to">
                    <a href="#" style="width: auto;" id="requesthelpselector"><img src="/img/requestselector.png" alt="" style="height: 100%;"></a>
                </div>
                <div style="height: 1px; clear: both; margin-bottom: 10px;"></div>
                <span style="font-size: 11px; color: #666666;text-shadow: 0 1px 1px white">ВАШ EMAIL</span><span style="text-decoration:none;margin-left:5px;color:#ff5a5e;font-size:11px;">*</span>
                <input name="email" value="<?= $this->user->getEmail()?>" id="reqemail" type="text" style="height:43px;
                margin-top:5px;width:100%;" class="i1">
                <div style="height: 1px; clear: both; margin-bottom: 10px;"></div>
                <span style="font-size: 11px; color: #666666;text-shadow: 0 1px 1px white">ВАШЕ ИМЯ</span>
                <input name="name" id="reqname" type="text" value="<?= $this->user->getFullname()?>" style="height:43px;margin-top:5px;width:100%;" class="i1">
                <input name="target" id="reqtarget" type="hidden" value="0">
                <div style="height: 1px; clear: both; margin-bottom: 10px;"></div>
                <span style="font-size: 11px; color: #666666;text-shadow: 0 1px 1px white">ОПИШИТЕ ПРОБЛЕМУ И ЗАДАЙТЕ ВОПРОС</span>
                <textarea name="message" id="reqmessage" style="margin-top:5px;width:100%;height:170px;"></textarea>

                <div style="text-align: center;">
                    <input type="submit" id="reqsend" class="reqbutton" value="Отправить" style="margin: 19px auto 20px auto; width: 184px; color:#FFFFFF;font-size: 12px;text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);height:48px">
                </div>

                <a class="form-faq-link" href="https://godesigner.ru/answers/view/108" target="_blank">Предоставляя данные, вы подтверждаете согласие на их обработку и принимаете Политику конфиденциальности GoDesigner.</a><br>
                <a class="form-faq-link" href="https://godesigner.ru/answers/view/96" target="_blank">Можно ли загрузить решение раньше срока?</a>
            </form>
            <div id="contactlist" style=" background-color: #fff; display:none; top:-548px; left:0;right:0; margin:0 auto; width:calc(100% - 90px);position:relative;z-index:15;">
                <ul style="padding: 0 20px; border: 2px solid #e0e0e0;">
                    <?php
                    $hasPriorityTarget = false;
                    if ($this->user->isSubscriptionActive()):
                        $hasPriorityTarget = true;
                    ?>
                    <li class="requestli" style="height:auto; border-bottom: 1px solid #ebeff2; padding-bottom: 11px;"><a href="#" class="reqlink" data-id="5">абонентский сервис (Мария Еленевская)</a></li>
                    <?php endif?>
                    <!--li class="requestli" style="height:auto; border-bottom: 1px solid #ebeff2; padding-bottom: 11px; <?php if ($hasPriorityTarget):?>padding-top:5px;<?php endif?>"><a href="#" class="reqlink" data-id="1">дизайн консультация (Оксана Девочкина)</a></li-->
                    <li class="requestli" style="height:auto; border-bottom: 1px solid #ebeff2; padding-bottom: 11px; padding-top:5px"><a href="#" class="reqlink" data-id="2">бухгалтерия (Слава Афанасьев)</a></li>
                    <li class="requestli" style="height:auto; border-bottom: 1px solid #ebeff2; padding-bottom: 11px; padding-top:5px"><a href="#" class="reqlink" data-id="3">тех. поддержка (Дима Ню)</a></li>
                    <li class="requestli" style="height:auto; border-bottom: 1px solid #ebeff2; padding-bottom: 11px; padding-top:5px"><a href="#" class="reqlink" data-id="4">другое (Максим Федченко)</a></li>
                </ul>
            </div>
        </div>
        <div id="reqformthankyou" style="display:none;">
            <a href="#" style="color: rgb(100, 143, 164); font-size: 12px; padding-right: 20px; background: url('/img/closerequestform.png') no-repeat scroll 50px 0px transparent; margin-top: 0px; margin-left: 405px;" class="close-request">закрыть</a>
            <img style="margin-left:45px;margin-top:0px" src="/img/reqthank.png" alt="">
        </div>
        <div id="fbimg" style="display:none;">
            <img src="/img/share-image.jpg" alt="" />
        </div>
    </div>
</div>