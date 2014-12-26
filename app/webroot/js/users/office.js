// office.js start

$(document).ready(function(){
    //Переключение активной сттаницы в Главном меню
    $('.main_nav a').live('click', function(){
        $('.main_nav a').removeClass('active');
        $(this).addClass('active');
    });

    //Hover на кнопке "full_pitch"
    $('p.full_pitch a').live('mouseover', function(){
        $(this).parent().siblings('h2').children().css('color','#648fa4');
    });
    $('p.full_pitch a').live('mouseout', function(){
        $(this).parent().siblings('h2').children().css('color','#666');
    });

    $(document).on('click', '#older-events', function() {
        $(this).remove();
        Updater.nextPage();
        return false;
    });
    if(window.location.pathname.match(/users\/office/)) {
        var Updater = new OfficeStatusUpdater();
        Updater.init();
        officeInit();
    }

    // Solution Stars
    $(document).on('mouseenter', '.ratingchange', function(){
        $(this).parent().css('background', 'url(/img/' + $(this).data('rating') + '-rating.png) repeat scroll 0% 0% transparent');
    });

    $(document).on('mouseleave', '.ratingcont', function() {
        $(this).css('background', 'url(/img/' + $(this).data('default') + '-rating.png) repeat scroll 0% 0% transparent');
    });
    $(document).on('click', '.ratingchange', function() {
        var id = $(this).parent().data('solutionid');
        var rating = $(this).data('rating');
        var self = $(this);
        $.post('/solutions/rating/' + id + '.json',
            {"id": id, "rating": rating}, function(response) {
                self.parent().data('default', rating);
                self.parent().css('background', 'url(/img/' + rating + '-rating.png) repeat scroll 0% 0% transparent');
            });
        return false;
    });
    

    $(document).on('click', '.post-to-facebook', function() {
        _gaq.push(['_trackEvent', 'Тест', 'Пользователь нажал кнопку расшаривания фейсбука']);
        sendFBMessage();
        return false;
    });
    var sendFBMessage = function() {
        var dataFbWallPost = {
            method: 'stream.publish',
            message: "",
            display: 'iframe',
            caption: " ",
            name: "Мой новый заказ на лучший дизайн",
            link: 'http://www.godesigner.ru/pitches/details/'+shareid,
            description: $('.post-to-facebook').data('share-text')
        };
        FB.ui(dataFbWallPost, function() { });
    };
    shareid = 0;
    var initShares = function() {
        setTimeout(function() {
            $('a.twitter-share-button').attr('data-url','http://www.godesigner.ru/pitches/details/'+shareid);
            !function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                    if (!d.getElementById(id)) {
                    js = d.createElement(s);
                    js.id = id;
                    js.src = "//platform.twitter.com/widgets.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }
            }(document, "script", "twitter-wjs");
            // Vk
            $('.vk_share_button').replaceWith(VK.Share.button(
                {
                    url: 'http://www.godesigner.ru/pitches/details/'+shareid,
                    title: 'Мой новый заказ на лучший дизайн',
                    description: $('.vk_share_button').data('share-text'),
                    noparse: true
                },
                {
                    type: 'round_nocount',
                    text: 'Поделиться'
                }
            ));
        $('#vkshare1').on('click', function() {
            _gaq.push(['_trackEvent', 'Тест', 'Пользователь нажал кнопку расшаривания ВК']);
        });
        // Twitter
        twttr.widgets.load();
        twttr.events.bind('click', function (e) {
            if(e.target.id === 'twitter-widget-1') {
                _gaq.push(['_trackEvent', 'Тест', 'Пользователь нажал кнопку расшаривания твиттера']);
            }
        });
    }, 2000);
    };
    if (location.pathname == '/users/mypitches' && getUrlVar('success')) {
        initShares();
        $('#popup-mypitches-true').modal({  
            containerId: 'mypitches-true',  
            opacity: 80,  
            closeClass: 'true-close'
        });
    }
});

function getUrlVar(key){
    var result = new RegExp(key + "=([^&]*)", "i").exec(window.location.search);
    return result && unescape(result[1]) || "";
}

function officeInit() {
    $('.time').timeago();
    if($('li', '.group').length > 6) {
        //Большая карусель
        if($('#big_carousel').children().first().children().length > 0) {
            $('#big_carousel').jCarouselLite({
                visible: 6,
                auto: 0,
                speed: 500,
                btnPrev: "#prev",
                btnNext: "#next"
            });
        }
    }else {
        $('#prev').click(function() {
            return false;
        })
        $('#next').click(function() {
            return false;
        })
    }

    if($('li', '#carousel_small').length > 1) {
        //Маленькая карусель
        $('#carousel_small').jCarouselLite({
            auto: 0,
            speed: 500,
            btnPrev: "#prev2",
            btnNext: "#next2",
            visible: 1
        });
    }else {
        $('#prev2').click(function() {
            return false;
        })
        $('#next2').click(function() {
            return false;
        })
    }
}

function OfficeStatusUpdater() {
    var self = this;
    // storage object for saving data
    this.storage = {};
    this.page = 1;
    this.date = '';
    // initialisation method
    this.init = function() {
        $('.obnovlenia_box').last().addClass('last_item');
        $(document).everyTime(10000, function(i) {
            self.autoupdate();
        });
    },
    this.autoupdate = function() {
        $.get('/events/updates.json', {"init": true, "created": self.date}, function(response) {
            if(response.count != 0) {
                function sortfunction(a, b){
                    return (a.sort - b.sort);
                }
                response.updates.sort(sortfunction);
                var html = '';
                $.each(response.updates, function(index, object) {
                    if(index == 0) {
                        self.date = object.created;
                    }
                    if(!object.solution) {
                        object.solution = {};
                        object.solution.images = {};
                        object.solution.images.solution_galleryLargeSize = {};
                        object.solution.views = 0;
                        object.solution.likes = 0;
                        object.solution.images.solution_galleryLargeSize.weburl = '';
                    }

                    var newclass = '';
                    if(Date.parse(object.jsCreated) > offsetDate) {
                        newclass = ' newevent ';
                    }

                    if(object.type == 'PitchCreated') {
                        newclass = ' newpitchstream ';
                    }
                    if (typeof(object.solution.images.solution_galleryLargeSize) != "undefined") {
                        if(typeof(object.solution.images.solution_galleryLargeSize.length) == "undefined") {
                            var imageurl = object.solution.images.solution_galleryLargeSize.weburl;
                        }else{
                            var imageurl = object.solution.images.solution_galleryLargeSize[0].weburl;
                        }
                    }
                    if(object.type == 'PitchCreated') {
                        var imageurl = '/img/zaglushka.jpg';
                    }
                    var extraUI = '';
                    if(object.type != 'PitchCreated') {
                        extraUI = '<div class="rating_block" style="height: 9px; margin-top: 2px;"> \
                                <div class="ratingcont" data-default="' + object.solution.rating + '" data-solutionid="' + object.solution.id + '" style="float: right; height: 9px; background: url(/img/' + object.solution.rating + '-rating.png) repeat scroll 0% 0% transparent; width: 56px;">';
                                    if($('#user_id').val() == object.pitch.user_id) {
                                        extraUI += '<a data-rating="1" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a> \
                                        <a data-rating="2" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a> \
                                        <a data-rating="3" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a> \
                                        <a data-rating="4" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a> \
                                        <a data-rating="5" class="ratingchange" href="#" style="width:11px;height:9px;float:left;display:block"></a>';
                                    }
                       extraUI += '</div> \
                            </div>'+
                            '<p class="visit_number">' + object.solution.views + '</p>'+
                            '<p class="fb_like"><a href="#">' + object.solution.likes + '</a></p>'
                    }

                    html +=  '<div class="obnovlenia_box ' + newclass  + ' group">'+
                        '<section class="global_info">'+
                            '<p>' + object.humanType + '</p>'+
                            '<p class="designer_name">' + object.creator + '</p>'+
                            '<p class="add_date">' + object.humanCreated + '</p>'+
                            extraUI +
                        '</section>'+

                        '<section class="global_picture">'+
                            '<div class="pic_wrapper">'+
                                '<a href="/pitches/viewsolution/' + object.solution.id + '"><img src="' + imageurl + '" width="99" height="75" alt="" /></a>'+
                                '<!--img class="winning" src="/img/winner_icon.png" width="25" height="59" /-->'+
                            '</div>'+
                        '</section>'+

                        '<section class="main_info">'+
                            '<h2><a href="/pitches/view/' + object.pitch.id + '">' + object.pitch.title + '</a></h2>'+
                            '<p class="subject">' + object.pitch.industry + '</p>'+
                            '<p class="price"><span>' + self._priceDecorator(object.pitch.price) + '</span> P.-</p>'+
                            '<p class="main_text">'+
                               object.updateText +
                            '</p>'+
                            '<p class="full_pitch"><a href="/pitches/view/' + object.pitch.id + '"></a></p>'+
                        '</section>'+
                    '</div>'
                });
                var $prependEl = $(html);
                $prependEl.hide();
                $prependEl.prependTo('#updates-box').slideDown('slow');
            }
        });
    }
    this.nextPage = function() {
        self.page += 1;
        $('#officeAjaxLoader').show();
        var $formerLast = $('.obnovlenia_box').last();
        $.get('/events/updates.json', {"init": true, "page": self.page}, function(response) {
            $('#officeAjaxLoader').hide();
            if(response.count != 0) {
                function sortfunction(a, b){
                    return (a.sort - b.sort);
                }
                response.updates.sort(sortfunction);
                var html = '';
                $.each(response.updates, function(index, object) {
                    if(!object.solution) {
                        object.solution = {};
                        object.solution.images = {};
                        object.solution.images.solution_galleryLargeSize = {};
                        object.solution.views = 0;
                        object.solution.likes = 0;
                        object.solution.images.solution_galleryLargeSize.weburl = '';
                    }
                    if (typeof(object.solution.images.solution_galleryLargeSize) != "undefined") {
                        if(typeof(object.solution.images.solution_galleryLargeSize.length) == "undefined") {
                            var imageurl = object.solution.images.solution_galleryLargeSize.weburl;
                        }else{
                            var imageurl = object.solution.images.solution_galleryLargeSize[0].weburl;
                        }
                    }
                    html +=  '<div class="obnovlenia_box group">'+
                        '<section class="global_info">'+
                            '<p>' + object.humanType + '</p>'+
                            '<p class="designer_name">' + object.creator + '</p>'+
                            '<p class="add_date">' + object.humanCreated + '</p>'+
                            '<div class="rating_block" style="height: 9px; margin-top: 2px;"> \
                                <div class="ratingcont" data-default="' + object.solution.rating + '" data-solutionid="' + object.solution.id + '" style="float: right; height: 9px; background: url(/img/' + object.solution.rating + '-rating.png) repeat scroll 0% 0% transparent; width: 56px;"> \
                                </div> \
                            </div>'+
                            '<p class="visit_number">' + object.solution.views + '</p>'+
                            '<p class="fb_like"><a href="#">' + object.solution.likes + '</a></p>'+
                        '</section>'+

                        '<section class="global_picture">'+
                            '<div class="pic_wrapper">'+
                                '<a href="/pitches/viewsolution/' + object.solution.id + '"><img src="' + imageurl + '" width="99" height="75" alt="" /></a>'+
                                '<!--img class="winning" src="/img/winner_icon.png" width="25" height="59" /-->'+
                            '</div>'+
                        '</section>'+

                        '<section class="main_info">'+
                            '<h2><a href="/pitches/view/' + object.pitch.id + '">' + object.pitch.title + '</a></h2>'+
                            '<p class="subject">' + object.pitch.industry + '</p>'+
                            '<p class="price"><span>' + self._priceDecorator(object.pitch.price) + '</span> P.-</p>'+
                            '<p class="main_text">'+
                               object.updateText +
                            '</p>'+
                            '<p class="full_pitch"><a href="/pitches/view/' + object.pitch.id + '"></a></p>'+
                        '</section>'+
                    '</div>'
                });
                if (response.nextUpdates > 0) {
                    html += '<div id="earlier_button"><a href="#" id="older-events">Ранее</a></div>';
                }
                var $appendEl = $(html);
                $appendEl.hide();
                $formerLast.removeClass('last_item');
                $appendEl.appendTo('#updates-box').slideDown('slow', function() {
                    $('.obnovlenia_box').last().addClass('last_item');
                });
            }
        });

    }
    this._priceDecorator = function(price) {
        price = price.replace(/(.*)\.00/g, "$1");
        counter = 1;
        while(price.match(/\w\w\w\w/)) {
            price = price.replace(/^(\w*)(\w\w\w)(\W.*)?$/, "$1 $2$3");
            counter ++;
            if(counter > 6) break;
        }
        return price;
    }
};
// office.js end
/*********************/
// mypitches.js start
$(document).ready(function() {

    if(window.location.pathname.match(/users\/mypitches/)) {
        mypitchesInit();
    }

    $(document).on('click', '.unfav', function() {
        var data = { "pitch_id": $(this).data('pitchid')};
        $.post('/favourites/remove.json', data, function(response) {
        });
        $('tr[data-id=' + $(this).data('pitchid') + ']', '#faves').remove();
        if($('#faves').children(':visible').length == 0) {
            $('#fav-placeholder').show();
            $('#fav-table').hide();
        }else{
        }
        return false;
    });

    $(document).on('click', '.mypitch_delete_link', function() {
        if($(this).hasClass('unfav')) {
            return true;
        }
        $('#confirmDelete').data('id', $(this).attr('rel'));
        $('#popup-final-step').modal({
            containerId: 'final-step',
            opacity: 80,
            closeClass: 'popup-close'
        });
        return false;
    })

    $(document).on('click', '#confirmDelete', function() {
        var id = $('#confirmDelete').data('id');
        $.post('/pitches/delete/' + id + '.json', function() {
            $('tr[data-id="' + id + '"]').hide();
            $('.popup-close').click();
        })

    })
})

function mypitchesInit() {
    var PTable = new ParticipateTableLoader;
    PTable.init();

    var FTable = new FavesTableLoader;
    FTable.init();
}

/* Class */

function ParticipateTableLoader() {
    var self = this;
    // storage object for saving data
    this.storage = {};
    this.container = $('#table-content');
    this.nav = $('#topnav');
    this.limit = 3; //even number
    this.navNeub = 2;
    this.options = {
        "page": 1,
        "type": "current",
        "category": "all",
        "order": {"price": "desc"},
        "priceFilter": "all"
    }
    this.page = 1;
    this.type = 'current';
    this.magePage = null;
    // initialisation method
    this.init = function() {
        self.setFilter('category', $('input[name=category]').val(), $('#cat-menu'));
        $(document).on('click', '.js-participate .nav-page', function() {
            var page = $(this).attr('rel');
            if(page == 'prev') {
                page = parseInt(self.page) - 1;
                if(page < 1) page = 1;
            }
            if(page == 'next') {
                page = parseInt(self.page) + 1;
                if(page > self.total) page = self.total;
            }
            self.options.page = page;
            self.fetchTable(self.options);
            return false;
        })
        self.fetchTable(self.options);
    };
    this.fetchTable = function(options) {
        $.get('/pitches/participate.json', options, function(response) {
            self.page = response.data.info.page;
            self.total = response.data.info.total;
            self.renderTable(response);
            self.renderNav(response);
            if(self.total == '0') {
                $('#my-placeholder').show();
                $('#my-table').hide();
            }else {
                $('#my-placeholder').hide();
                $('#my-table').show();
            }
        });
    };
    this.renderTable = function(response) {
        var html = '';
        var counter = 1;

        response.data.pitches.sort(sortfunction);

        function sortfunction(a, b){
            return (a.sort - b.sort);
        }
        $.each(response.data.pitches, function(index, object) {
            if (object.multiwinner != 0 && object.billed == 0) return;
            shareid = object.id;
            var rowClass = 'odd';
            if((counter % 2 == 0)) {
                rowClass = 'even';
            }
            if(object.pinned == 1) {
                rowClass += ' highlighted';
            }
            if(object.status == 0) {
                if((object.private == 1) && (object.expert == 0)){
                    rowClass += ' close';
                }
                if((object.private == 0) && (object.expert == 1)){
                    rowClass += ' expert';
                }
                if((object.private == 1) && (object.expert == 1)){
                    rowClass += ' close-and-expert';
                }
            }else if (object.status == 1) {
                rowClass += ' selection';
            }else if (object.status == 2) {
                rowClass += ' pitch-end';
            }
            var editLink = '';
            var total = object.price;
            var imgForDraft = '';
            if (object.published == 1) {
                imgForDraft = ' not-draft';
            }
            if((object.billed == 0) && (object.status == 0)) {
                var status = (object.moderated == 1) ? 'Ожидание<br />модерации' : 'Ожидание оплаты';
                if($('#user_id').val() == object.user_id) {
                    total = object.total;
                    editLink = '<a href="/pitches/edit/' + object.id + '" class="mypitch_edit_link' + imgForDraft + '" title="Редактировать"><img src="/img/1.gif" class="pitches-name-td-img"></a> \
                    <a href="/pitches/delete/' + object.id + '" rel="' + object.id  +'"  class="mypitch_delete_link" title="Удалить"><img src="/img/1.gif" class="pitches-name-td-img"></a> \
                    <a href="/pitches/edit/' + object.id + '#step3" class="mypitch_pay_link" title="Оплатить"><img src="/img/1.gif" class="pitches-name-td2-img"></a>';
                }
            }else {
                if((object.published == 0) && (object.brief == 1)) {
                    var status = 'Ожидайте звонка';
                }else {
                    var status = object.startedHuman;
                }
                if(($('#user_id').val() == object.user_id)) {
                    editLink = '<a href="/pitches/edit/' + object.id + '" class="mypitch_edit_link' + imgForDraft + '" title="Редактировать"><img src="/img/1.gif" class="pitches-name-td-img"></a>';
                }
            }
            
            var pitchPath = 'view';
            if (object.ideas_count == 0) {
                pitchPath = 'details';
            }

            if(object.status != 2) {
                var link = '/pitches/' + pitchPath + '/' + object.id;
                if((object.status == 1) && (object.awarded == 0)) {
                    var status = 'Выбор победителя';
                }
                if((object.status == 1) && (object.awarded != 0)) {
                    var status = 'Победитель выбран';
                }
            }else {
                var status = 'Питч завершен';
                if (object.hasBill == 'fiz') {
                    status = '<a href="/pitches/getpdfreport/' + object.id + '">Скачать отчёт</a>';
                }
                if (object.hasBill == 'yur') {
                    status = '<a href="/pitches/getpdfact/' + object.id + '">Скачать Акт</a><br><a href="/pitches/getpdfreport/' + object.id + '">Скачать отчёт</a>';
                }

                if(object.winlink == true) {
                    var link = '/users/step2/' + object.awarded;
                }else {
                    var link = '/pitches/' + pitchPath + '/' + object.id;
                }
            }
            var shortIndustry = object.industry;
            if(shortIndustry.length > 80) {
                shortIndustry = shortIndustry.substr(0, 75) + '...';
            }
            shortIndustry = '<span style="font-size: 11px;">' + shortIndustry + '</span>';
            // Disabling Industry in Table
            shortIndustry = '';
            var pitchMultiple = '';
            if (object.multiple) {
                pitchMultiple = '<br>' + object.multiple;
            }

            html += '<tr data-id="' + object.id + '" class="' + rowClass + '">' +
                '<td class="icons"></td>' +
                '<td class="pitches-name">' +
                editLink +
                '<div style="background: none;">' +
                '<a href="' + link + '" class="expand-link">' + object.title + '</a>' +
                shortIndustry +
                '</div>' +
                '</td>' +
                '<td class="pitches-cat">' +
                '<a href="#" style="font-size: 11px;">' + object.category.title + pitchMultiple + '</a>' +
                '</td>' +
                '<td class="idea" style="font-size: 11px;">' + object.ideas_count + '</td>' +
                '<td class="pitches-time" style="font-size: 11px;">' + status + '</td>' +
                '<td class="price">' + self._priceDecorator(object.price) + ' р.-</td>' +
                '</tr>' +
                '<tr class="pitch-collapsed">' +
                '<td class="icons"></td>' +
                '<td colspan="3" class="al-info-pitch"><p>' + object.description +
                '</p><a href="/pitches/' + pitchPath + '/' + object.id + '" class="go-pitch">Перейти к питчу</a>' +
                '</td>' +
                '<td></td>' +
                '<td></td>' +
                '</tr>';
            counter++;
        });
        self.container.html(html);
    }
    this.renderNav = function(response) {
        var navInfo = response.data.info;
        var navBar = '';
        var prepend = '';
        var append = '';
        if(navInfo.total > 1) {
            if(navInfo.total > 1) {
                prepend = '<a href="#" class="nav-page" rel="prev"><</a>';
                append = '<a href="#" class="nav-page" rel="next">></a>';
            }
            if(navInfo.total <= 5) {
                for(i = 1; i <= navInfo.total; i++) {
                    if(navInfo.page == i) {
                        navBar += '<a href="#" class="this-page nav-page" rel="' + i + '">' + i + '</a>';
                    }else {
                        navBar += '<a href="#" class="nav-page" rel="' + i + '">' + i + '</a>';
                    }
                }
            }else {
                if((navInfo.page - 3) <= 0) {
                    for(i = 1; i <= 4; i++) {
                        if(navInfo.page == i) {
                            navBar += '<a href="#" class="this-page nav-page" rel="' + i + '">' + i + '</a>';
                        }else {
                            navBar += '<a href="#" class="nav-page" rel="' + i + '">' + i + '</a>';
                        }
                    }
                    navBar += ' ... ';
                    navBar += '<a href="#" class="nav-page" rel="' + navInfo.total + '">' + navInfo.total + '</a>';
                }

                if(((navInfo.page - 3) > 0) && (navInfo.total > (navInfo.page + 2))) {
                    navBar += '<a href="#" class="nav-page" rel="1">1</a>';
                    navBar += ' ... ';
                    for(i = navInfo.page - 1 ; i <= navInfo.page + 1; i++) {
                        if(navInfo.page == i) {
                            navBar += '<a href="#" class="this-page nav-page" rel="' + i + '">' + i + '</a>';
                        }else {
                            navBar += '<a href="#" class="nav-page" rel="' + i + '">' + i + '</a>';
                        }
                    }
                    navBar += ' ... ';
                    navBar += '<a href="#" class="nav-page" rel="' + navInfo.total + '">' + navInfo.total + '</a>';
                }

                if(navInfo.total <= (navInfo.page + 2)) {
                    navBar += '<a href="#" class="nav-page" rel="1">1</a>';
                    navBar += ' ... ';
                    for(i = navInfo.total - 3; i <= navInfo.total; i++) {
                        if(navInfo.page == i) {
                            navBar += '<a href="#" class="this-page nav-page" rel="' + i + '">' + i + '</a>';
                        }else {
                            navBar += '<a href="#" class="nav-page" rel="' + i + '">' + i + '</a>';
                        }
                    }
                }
            }
        }
        navBar = prepend + navBar + append;
        self.nav.html(navBar);
    };
    this.setFilter = function(key, value, menu) {
        self.options[key] = value;
        menu.children().children('a').removeClass('selected');
        menu.children().children('a[rel=' + value + ']').addClass('selected')
    };
    this._priceDecorator = function(price) {
        if(typeof(price) == 'undefined') {
            price = '0';
        }
        price = price.replace(/(.*)\.00/g, "$1");
        counter = 1;
        while(price.match(/\w\w\w\w/)) {
            price = price.replace(/^(\w*)(\w\w\w)(\W.*)?$/, "$1 $2$3");
            counter ++;
            if(counter > 6) break;
        }
        return price;
    }
}

/* Class */

function FavesTableLoader() {
    var self = this;
    // storage object for saving data
    this.storage = {};
    this.container = $('#faves');
    this.nav = $('#bottomnav');
    this.limit = 3; //even number
    this.navNeub = 2;
    this.options = {
        "page": 1,
        "type": "current",
        "category": "all",
        "order": {"price": "desc"},
        "priceFilter": "all"
    }
    this.page = 1;
    this.type = 'current';
    this.magePage = null;
    // initialisation method
    this.init = function() {
        self.setFilter('category', $('input[name=category]').val(), $('#cat-menu'));
        $(document).on('click', '.js-favourites .nav-page', function() {
            var page = $(this).attr('rel');
            if(page == 'prev') {
                page = parseInt(self.page) - 1;
                if(page < 1) page = 1;
            }
            if(page == 'next') {
                page = parseInt(self.page) + 1;
                if(page > self.total) page = self.total;
            }
            self.options.page = page;
            self.fetchTable(self.options);
            return false;
        })
        self.fetchTable(self.options);
    };
    this.fetchTable = function(options) {
        $.get('/pitches/favourites.json', options, function(response) {
            self.page = response.data.info.page;
            self.total = response.data.info.total;
            self.renderTable(response);
            self.renderNav(response);
            if(self.total == '0') {
                $('#fav-placeholder').show();
                $('#fav-table').hide();
            }else {
                $('#fav-placeholder').hide();
                $('#fav-table').show();
            }
        });
    };
    this.renderTable = function(response) {
        var html = '';
        var counter = 1;

        response.data.pitches.sort(sortfunction);

        function sortfunction(a, b){
            return (a.sort - b.sort);
        }
        $.each(response.data.pitches, function(index, object) {
            var rowClass = 'odd';
            if((counter % 2 == 0)) {
                rowClass = 'even';
            }
            if(object.pinned == 1) {
                rowClass += ' highlighted';
            }
            if(object.status == 0) {
                if((object.private == 1) && (object.expert == 0)){
                    rowClass += ' close';
                }
                if((object.private == 0) && (object.expert == 1)){
                    rowClass += ' expert';
                }
                if((object.private == 1) && (object.expert == 1)){
                    rowClass += ' close-and-expert';
                }
            }else if (object.status == 1) {
                rowClass += ' selection';
            }else if (object.status == 2) {
                rowClass += ' pitch-end';
            }
            var status = object.startedHuman;
            if(object.status != 2) {
                if((object.status == 1) && (object.awarded == 0)) {
                    var status = 'Выбор победителя';
                }
                if((object.status == 1) && (object.awarded != 0)) {
                    var status = 'Победитель выбран';
                }
            }else {
                var status = 'Питч завершен';
            }

            var shortIndustry = object.industry;
            if(shortIndustry.length > 80) {
                shortIndustry = shortIndustry.substr(0, 75) + '...';
            }
            shortIndustry = '<span style="font-size: 11px;">' + shortIndustry + '</span>';
            // Disabling Industry in Table
            shortIndustry = '';
            var pitchPath = 'view';
            if (object.ideas_count == 0) {
                pitchPath = 'details';
            }
            var pitchMultiple = '';
            if (object.multiple) {
                pitchMultiple = '<br>' + object.multiple;
            }

            html += '<tr data-id="' + object.id + '" class="' + rowClass + '">' +
                '<td class="icons"></td>' +
                '<td class="pitches-name">' +
                '<a href="#" class="unfav mypitch_delete_link" data-pitchId="' + object.id + '" style="position: relative; top: 21px; left: 14px;"><img style="margin:0;padding:0" src="/img/1.gif"></a>' +
                '<div style="background:none;padding-top:0px">' +
                '<a href="/pitches/' + pitchPath + '/' + object.id + '" class="expand-link">' + object.title + '</a>' +
                shortIndustry +
                '</div>' +
                '</td>' +
                '<td class="pitches-cat">' +
                '<a href="#" style="font-size: 11px;">' + object.category.title + pitchMultiple + '</a>' +
                '</td>' +
                '<td class="idea" style="font-size: 11px;">' + object.ideas_count + '</td>' +
                '<td class="pitches-time" style="font-size: 11px;">' + status + '</td>' +
                '<td class="price">' + self._priceDecorator(object.price) + ' р.-</td>' +
                '</tr>' +
                '<tr class="pitch-collapsed">' +
                '<td class="icons"></td>' +
                '<td colspan="3" class="al-info-pitch"><p>' + object.description +
                '</p><a href="/pitches/' + pitchPath + '/' + object.id + '" class="go-pitch">Перейти к питчу</a>' +
                '</td>' +
                '<td></td>' +
                '<td></td>' +
                '</tr>';
            counter++;
        });
        self.container.html(html);
    }
    this.renderNav = function(response) {
        var navInfo = response.data.info;
        var navBar = '';
        var prepend = '';
        var append = '';
        if(navInfo.total > 1) {
            if(navInfo.total > 1) {
                prepend = '<a href="#" class="nav-page" rel="prev"><</a>';
                append = '<a href="#" class="nav-page" rel="next">></a>';
            }
            if(navInfo.total <= 5) {
                for(i = 1; i <= navInfo.total; i++) {
                    if(navInfo.page == i) {
                        navBar += '<a href="#" class="this-page nav-page" rel="' + i + '">' + i + '</a>';
                    }else {
                        navBar += '<a href="#" class="nav-page" rel="' + i + '">' + i + '</a>';
                    }
                }
            }else {
                if((navInfo.page - 3) <= 0) {
                    for(i = 1; i <= 4; i++) {
                        if(navInfo.page == i) {
                            navBar += '<a href="#" class="this-page nav-page" rel="' + i + '">' + i + '</a>';
                        }else {
                            navBar += '<a href="#" class="nav-page" rel="' + i + '">' + i + '</a>';
                        }
                    }
                    navBar += ' ... ';
                    navBar += '<a href="#" class="nav-page" rel="' + navInfo.total + '">' + navInfo.total + '</a>';
                }

                if(((navInfo.page - 3) > 0) && (navInfo.total > (navInfo.page + 2))) {
                    navBar += '<a href="#" class="nav-page" rel="1">1</a>';
                    navBar += ' ... ';
                    for(i = navInfo.page - 1 ; i <= navInfo.page + 1; i++) {
                        if(navInfo.page == i) {
                            navBar += '<a href="#" class="this-page nav-page" rel="' + i + '">' + i + '</a>';
                        }else {
                            navBar += '<a href="#" class="nav-page" rel="' + i + '">' + i + '</a>';
                        }
                    }
                    navBar += ' ... ';
                    navBar += '<a href="#" class="nav-page" rel="' + navInfo.total + '">' + navInfo.total + '</a>';
                }

                if(navInfo.total <= (navInfo.page + 2)) {
                    navBar += '<a href="#" class="nav-page" rel="1">1</a>';
                    navBar += ' ... ';
                    for(i = navInfo.total - 3; i <= navInfo.total; i++) {
                        if(navInfo.page == i) {
                            navBar += '<a href="#" class="this-page nav-page" rel="' + i + '">' + i + '</a>';
                        }else {
                            navBar += '<a href="#" class="nav-page" rel="' + i + '">' + i + '</a>';
                        }
                    }
                }
            }
        }
        navBar = prepend + navBar + append;
        self.nav.html(navBar);
    };
    this.setFilter = function(key, value, menu) {
        self.options[key] = value;
        menu.children().children('a').removeClass('selected');
        menu.children().children('a[rel=' + value + ']').addClass('selected')
    };
    this._priceDecorator = function(price) {
        price = price.replace(/(.*)\.00/g, "$1");
        counter = 1;
        while(price.match(/\w\w\w\w/)) {
            price = price.replace(/^(\w*)(\w\w\w)(\W.*)?$/, "$1 $2$3");
            counter ++;
            if(counter > 6) break;
        }
        return price;
    }
}

// mypitches.js end
/* ================*/
// profile.js start

$(document).ready(function(){

    if($('li', '#carousel_small').length > 1) {
        //Маленькая карусель
        $('#carousel_small').jCarouselLite({
            auto: 0,
            speed: 500,
            btnPrev: "#prev2",
            btnNext: "#next2",
            visible: 1
        });
    }else {
        $('#prev2').click(function() {
            return false;
        })
        $('#next2').click(function() {
            return false;
        })
    }

    $('.changeStatus').live('click', function() {
        var name = $(this).attr('name');
        var input = $('#' + name);
        if($(this).hasClass('profselectbtnpressed')) {
            $('#' + name).val(0);
            $(this).removeClass('profselectbtnpressed');
        }else {
            $('#' + name).val(1);
            $(this).addClass('profselectbtnpressed');

        }
        return false;
    })

    $('#photoselectpic, #file-uploader-demo1').live('mouseover', function() {
        $('#file-uploader-demo1').show();
    })

    $('.photoselectbox, #file-uploader-demo1').live('mouseleave', function() {
        $('#file-uploader-demo1').hide();
    })

    var uploader = false;
    if(($('#file-uploader-demo1').length > 0) && (uploader == false)) {
        uploader = new qq.FileUploader({
            element: document.getElementById('file-uploader-demo1'),
            action: '/users/avatar.json',
            onComplete: function(id, fileName, responseJSON){
                var avatarUrl = responseJSON.data.images.avatar_normal.weburl + '?' + (Math.round(Math.random() * 11000));
                $('#photoselectpic').attr('src', avatarUrl);
            },
            debug: false
        });
    }

    $(document).on('click', '#deleteaccount', function() {
        $('#popup-final-step').modal({
            containerId: 'final-step',
            opacity: 80,
            closeClass: 'popup-close'
        });
        return false;
    });

    $(document).on('click', '#confirmWinner', function() {
        //window.location = '/users/deleteaccount';
        $.get('/users/deleteaccount')
        $('.popup-close').click();
        $('#delete-comfirm').modal({
            containerId: 'final-step',
            opacity: 80,
            closeClass: 'popup-close'
        });
        return false;
    });

});

// profile.js end
/* ============= */
// solution.js start
$(document).ready(function(){
    $(".select_checkbox").live('click', function(){
        /*var list = [];
         $.each($('.select_checkbox:checked'), function(index, obj) {
         list.push($(obj).data('id'));
         });*/

        $.post('/solutions/saveSelected.json', {"selectedSolutions": $(this).data('id')}, function(response) {
        })
        //return false;
    })
})
// solution.js end
/* ============= */
// awarded.js start
$(document).ready(function() {

    $('.photo_block').live('mouseenter', function(){
        $('a.end-pitch-hover', this).fadeIn(300);
    })

    $('.photo_block').live('mouseleave', function(){
        $('a.end-pitch-hover', this).fadeOut(300);
    })
});
// awarded.js end
/* ============= */
// details.js start
$('#save').live('click', function() {
    if($('.wrong-input').length > 0) {
        return false;
    }
    if($('input[name="cashintype"]:checked').data('pay') == 'cards') {
        accountCheck();
        if ($('.account-check').length > 0) {
            return false;
        }
    }
    var href = $('#worker-payment-data').attr('action');
    $.post('/users/savePaymentData.json', $('#worker-payment-data').serialize(), function(response) {
        window.location = href;
    })
    return false;
});

$('.rb1').live('change', function() {
    if($(this).data('pay') == 'cards') {
        $('#cards').show();
        $('#wmr').hide();
        $('.tooltip_plugin').tooltip({
            tooltipID: 'tooltip3',
            width: '205px',
            correctPosX: 45,
            positionTop: 180,
            borderSize: '0px',
            tooltipPadding: 0,
            titleAttributeContent: '12 цифр без пробелов',
            tooltipBGColor: 'transparent'
        });
    }else {
        $('#cards').hide();
        $('#wmr').show();
    }
});

$(document).on('focus', 'input[data-validate]', function() {
    $(this).removeClass('wrong-input');
});

$(document).on('blur', 'input[data-validate=fio]', function() {
    if (!/^[А-ЯЁ]{1}[а-яё]+\s[А-ЯЁ]{1}[а-яё]+\s[А-ЯЁ]{1}[а-яё]+$/.test($(this).val())) {
        $(this).addClass('wrong-input');
        required = true;
        return true;
    }
});

$(document).on('blur', 'input[data-validate=numeric]', function() {
    if (/[\D\s]/i.test($(this).val())) {
        $(this).addClass('wrong-input');
        required = true;
        return true;
    }
});

$(document).on('blur', 'input[data-validate=wmr]', function() {
    if (! /^R\d{12}$/.test($(this).val()) ) {
        $(this).addClass('wrong-input');
        required = true;
        return true;
    }
});

$('input[name=bik], input[name=coraccount], input[name=accountnum], input[name="inn"]').on('blur', function() {
    accountCheck();
});

$(document).on('click', function() {
    if ($('.account-check').hasClass('active')) {
        $('.account-check').remove();
    }
});

// details.js end
/* ==============*/
 $('.ajaxoffice').live('click', function() {
     var url = $(this).attr('href');
     if (url == '/news' || url == '/users/feed') {
         window.location.href = url;
         return false;
     }
     $.get(url, function(response) {
         if(url.match(/users\/office/)){
             $(document).on('click', '#older-events', function() {
                $(this).remove();
                 Updater.nextPage();
                 return false;
             });
             var matches = response.match(/parse\(((.)*)\)/);
             offsetDate = matches[1];
             $('.middle', '.wrapper').html($('.middle', response).html());
             var Updater = new OfficeStatusUpdater();
             Updater.init();
             officeInit();
         }else if(url.match(/users\/mypitches/)) {
             $('.middle', '.wrapper').html($('.middle', response).html());
             mypitchesInit();
         }else if(url.match(/users\/referal/)) {
             $('.middle', '.wrapper').html($('.middle', response).html());
             Ref = new Referal;
             Ref.reset({phone:$('#prop-phone').val(), phone_valid:$('#prop-phone_valid').val()});
         }else{
             $('.middle', '.wrapper').html($('.middle', response).html());
         }
     })
     return false;
 })

/*
 * Referal Tab
 */

/*
 * Referal Tab Object
 */
function Referal() {
    this.reset = function(data) {
        $('.referal-block').hide();
        $('#userPhone').val('');
        if (data.phone == 0) {
            $('.block-1').fadeIn(600);
        } else if (data.phone_valid == 0) {
            $('.block-2').fadeIn(600);
        } else {
            $('.block-3').fadeIn(600);
        }
    };
    this.tooltipPhone = function(text) {
        var position = $('#userPhone').position();
        position.top += 45;
        $('p', '#tooltip-phone').text(text);
        $('#tooltip-phone').css(position).fadeIn(200);
        setTimeout(function() { $('#tooltip-phone').fadeOut(200); }, 5000);
    };
}
$(document).ready(function() {
    if (window.location.pathname.match(/users\/referal/)) {
        Ref = new Referal;
        Ref.reset({phone:$('#prop-phone').val(), phone_valid:$('#prop-phone_valid').val()});
    }

    /*
     * Send SMS Code
     */
    $(document).on('submit', '#referal-1', function(e) {
        e.preventDefault();
        if ($('#phone-operator').val() == 0) {
            Ref.tooltipPhone('Выберите вашего провайдера!');
            return false;
        }
        $.post($(this).attr('action') + '.json', {
            'userPhone': $('#userPhone').val(),
            'phoneOperator': $('#phone-operator').val()
        }, function(result) {
            if (result == false) {
                Ref.tooltipPhone('К сожалению, мы не сможем подтвердить ваш телефон. Пожалуйста, укажите другой номер.');
            } else if (result == "limit") {
                Ref.tooltipPhone('К сожалению, Вы превысили лимит отправки сообщений. Попробуйте снова через час.');
            } else {
                if (result.respond.indexOf('error') != -1) {
                    Ref.tooltipPhone('Произошел сбой доставки SMS-сообщения. Попробуйте позже.');
                } else {
                    $('.phone-number', '.referal-title').text(result.phone);
                    $('.code-resend').data('phone', result.phone);
                    Ref.reset(result);
                }
            }
        }, 'json');
    });

    /*
     * ReSend SMS Code
     */
    $(document).on('click', '.code-resend', function(e) {
        e.preventDefault();
        $.post($(this).attr('href') + '.json', {
            'userPhone': $(this).data('phone')
        }, function(result) {
            if (result == 'false') {
                // False
            } else {
                if (result.respond.indexOf('error') != -1) {
                    // Error
                } else {
                    // Good
                }
            }
        });
    });

    /*
     * Verify SMS Code
     */
    $(document).on('submit', '#referal-2', function(e) {
        e.preventDefault();
        $.post($(this).attr('action') + '.json', {
            'verifyCode': $('#verifyCode').val()
        }, function(result) {
            if (result == 'false') {
                // Tooltip
                var position = $('#verifyCode').position();
                position.top += 55;
                $('#tooltip-code').css(position).fadeIn(200);
                setTimeout(function() { $('#tooltip-code').fadeOut(200); }, 5000);
            } else {
                Ref.reset(result);
            }
        });
    });

    /*
     * Delete Phone
     */
    $(document).on('click', '.phone-delete', function(e) {
        e.preventDefault();
        $.post($(this).attr('href') + '.json', {

        }, function(result) {
            if (result.code == true) {
                Ref.reset(result);
            }
        });
    });

    /*
     * Referal Social Share Popup
     */
    $(document).on('click', '.social-popup', function() {
        var width  = 575,
        height = 400,
        left   = ($(window).width()  - width)  / 2,
        top    = ($(window).height() - height) / 2,
        url    = this.href,
        opts   = 'status=1' +
                 ',width='  + width  +
                 ',height=' + height +
                 ',top='    + top    +
                 ',left='   + left;

        window.open(url, 'share', opts);
        return false
    });
});

function accountCheck() {
    $('.account-check').remove();
    var resultCor = 1; //var resultCor = (fn_checkKS($('input[name=coraccount]').val())) ? 1 : 0;
    var resultAcc = (fn_checkRS($('input[name=accountnum]').val(), $('input[name=bik]').val())) ? 2 : 0;
    var result = resultCor + resultAcc;
    var message = '';
    switch (result) {
    case 0:
        message = 'Неверно указан Счёт.<br>Неверно указан Корсчёт.<br>'
        break;
    case 1:
        message = 'Неверно указан Счёт или БИК.<br>'
            break;
    case 2:
        message = 'Неверно указан Корсчёт.<br>'
            break;
    default:
        break;
    }
    var messageBik = (/^\d{9}$/.test($('input[name=bik]').val())) ? '' : 'Неверно указан БИК.<br>';
    var messageInn = (/^\d{12}$/.test($('input[name="inn"]').val()) ) ? '' : 'Неверно указан ИНН.<br>';
    message = messageBik + messageInn + message;
    if (message) {
        var el = $('<tr class="account-check"><td colspan="2">' + message + '</td></tr>');
        el.appendTo($('#step1table')).animate({'opacity': 1}, 200, function() { $(this).addClass('active'); });
    }
}

/*
 * From http://javascript.ru/forum/misc/37373-funkciya-klyuchevaniya-scheta.html
 */
function fn_bank_account(Str)  
{         
    var result = false;
    var Sum = 0;
    if (Str == 0) {
        return result;
    }
    
    //весовые коэффициенты
    var v = [7,1,3,7,1,3,7,1,3,7,1,3,7,1,3,7,1,3,7,1,3,7,1];
    
    for (var i = 0; i <= 22; i++) 
    { 
        //вычисляем контрольную сумму
        Sum = Sum + ( Number(Str.charAt(i)) * v[i] ) % 10;
    }
    
    //сравниваем остаток от деления контрольной суммы на 10 с нулём
    if(Sum % 10 == 0)
    {
        result = true;
    }
        
    return result;          
}

function fn_checkKS(Account)  
{
    return (/^\d{20}$/.test(Account));
}

/*
Проверка правильности указания расчётного счёта:
1. Для проверки контрольной суммы перед расчётным счётом добавляются три последние цифры БИКа банка.
*/
function fn_checkRS(Account,BIK)  
{
    return fn_bank_account(BIK.substr(-3,3)+Account);
}
