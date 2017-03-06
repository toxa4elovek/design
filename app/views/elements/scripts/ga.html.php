<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-9235854-5']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
</script>
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter27256547 = new Ya.Metrika({
                    id:27256547
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/27256547" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<!-- Hotjar Tracking Code for https://godesigner.ru/ -->
<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:193163,hjsv:5};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'//static.hotjar.com/c/hotjar-','.js?sv=');
</script>
<!-- Chatra {literal} -->
<script>
    <?php
        $chatraSetup = [
            'startHidden' => true,
            'disabledOnMobile' => true
        ];
        $chatraIntegration = [];
        $isDesigner = 0;
        if ($this->user->isLoggedIn()):
            $chatraIntegration['name'] = $this->user->getFullname();
            $chatraIntegration['email'] = $this->user->getEmail();
            $chatraIntegration['phone'] = $this->user->getPhone();

            $companyName = $this->user->getFullCompanyName();
            if (!empty($companyName)):
                $chatraIntegration['Название компании'] = $companyName;
            endif;

            $projectsArray = [];
            if ($this->user->getCurrentPitches()):
                foreach ($this->user->getCurrentPitches() as $project):
                    if (($project->type == 'plan-payment') && ($project->billed == 1)):
                        continue;
                    endif;
                    if (($project->blank == 1) && ($project->billed == 0)):
                        continue;
                    endif;
                    if (($project->type == 'penalty') or ($project->type == 'fund-balance')):
                        continue;
                    endif;
                    $projectsArray[] = "$project->title ($project->id)\n\r";
                endforeach;
            endif;
            $projectsString = implode(' ', $projectsArray);
            $chatraIntegration['Проекты'] = $projectsString;
            if (($this->user->isLoggedIn()) && ($this->user->read('user.isDesigner') || ($this->user->read('user.isCopy')))) {
                $isDesigner = 1;
            }
            if ($this->user->isAdmin()) {
                $isDesigner = 1;
            }
        ?>
    <?php endif?>
    window.isDesigner = <?= $isDesigner?>;
    window.ChatraIntegration  = <?php echo json_encode($chatraIntegration);?>;
    window.ChatraSetup = <?php echo json_encode($chatraSetup);?>;
    ChatraID = 'c8KhbzjEvaNsKDeWD';
    (function(d, w, c) {
        var n = d.getElementsByTagName('script')[0],
            s = d.createElement('script');
        w[c] = w[c] || function() {
                (w[c].q = w[c].q || []).push(arguments);
            };
        s.async = true;
        s.src = (d.location.protocol === 'https:' ? 'https:': 'http:')
            + '//call.chatra.io/chatra.js';
        n.parentNode.insertBefore(s, n);
    })(document, window, 'Chatra');
</script>
<!-- /Chatra {/literal} -->
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=BycnZ*7at911xUrJSspJlXmFDG2UjWVzdkZxJYO1brLLyKPCLBHUV44b0Kwpbp30r4lbaTyb8FsZG*N7eqS0xiBx48Zkar0**T52jb2CyNZwXc7e29zkdpok0c*yD2Ardu9lxNb7dHaaGnay7O9r0HLRIU4/U39tisM5iAreJi4-';</script>
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 863454335;
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/863454335/?guid=ON&amp;script=0"/>
    </div>
</noscript>
<!-- start Mixpanel --><script type="text/javascript">(function(e,a){if(!a.__SV){var b=window;try{var c,l,i,j=b.location,g=j.hash;c=function(a,b){return(l=a.match(RegExp(b+"=([^&]*)")))?l[1]:null};g&&c(g,"state")&&(i=JSON.parse(decodeURIComponent(c(g,"state"))),"mpeditor"===i.action&&(b.sessionStorage.setItem("_mpcehash",g),history.replaceState(i.desiredHash||"",e.title,j.pathname+j.search)))}catch(m){}var k,h;window.mixpanel=a;a._i=[];a.init=function(b,c,f){function e(b,a){var c=a.split(".");2==c.length&&(b=b[c[0]],a=c[1]);b[a]=function(){b.push([a].concat(Array.prototype.slice.call(arguments,
    0)))}}var d=a;"undefined"!==typeof f?d=a[f]=[]:f="mixpanel";d.people=d.people||[];d.toString=function(b){var a="mixpanel";"mixpanel"!==f&&(a+="."+f);b||(a+=" (stub)");return a};d.people.toString=function(){return d.toString(1)+".people (stub)"};k="disable time_event track track_pageview track_links track_forms register register_once alias unregister identify name_tag set_config reset people.set people.set_once people.increment people.append people.union people.track_charge people.clear_charges people.delete_user".split(" ");
    for(h=0;h<k.length;h++)e(d,k[h]);a._i.push([b,c,f])};a.__SV=1.2;b=e.createElement("script");b.type="text/javascript";b.async=!0;b.src="undefined"!==typeof MIXPANEL_CUSTOM_LIB_URL?MIXPANEL_CUSTOM_LIB_URL:"file:"===e.location.protocol&&"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js".match(/^\/\//)?"https://cdn.mxpnl.com/libs/mixpanel-2-latest.min.js":"//cdn.mxpnl.com/libs/mixpanel-2-latest.min.js";c=e.getElementsByTagName("script")[0];c.parentNode.insertBefore(b,c)}})(document,window.mixpanel||[]);
  mixpanel.init("94a7a83395c376a509c48a2a07de61f4");</script><!-- end Mixpanel -->