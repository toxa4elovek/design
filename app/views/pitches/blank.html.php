<div class="wrapper">

    <?=$this->view()->render(array('element' => 'header'), array('header' => 'header2'))?><script type="text/javascript">
    //<![CDATA[
    var _flocktory = _flocktory || [];
    _flocktory.push({
        "order_id":     "<?= $pitch->id ?>",
        "email":        "hello@flocktory.com",
        "sex":          "f",
        "name":         "<?= $pitch->user->first_name?> <?= $pitch->user->last_name?>",
        "price":        <?= (int) $pitch->total?>,
        "items": [
        {
            "id":    "1",
            "title": "Питч",
            "price":  <?= (int) $pitch->total?>,
            "count":  1,
            "image": "http://www.godesigner.ru/img/fb_icon.jpg"
        }
    ]
    });

    (function() {
        var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true;
        s.src = "//api.flocktory.com/1/hello.js";
        var l = document.getElementsByTagName('script')[0]; l.parentNode.insertBefore(s, l);
    })();
    //]]>
</script>