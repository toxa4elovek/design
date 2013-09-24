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

    //Добавление оценки(звездочки)
    /*$('.global_info ul li a').toggle(function(){
        $(this).css({backgroundPosition: '0 -9px'});
    },function(){
        $(this).css({backgroundPosition: 'left top'});
    });*/


    // Появление блока с инфой, при наведении на элементы главной карусели
    /*$('.group li').hover(function(){
        //$(this).html('<div class="info_block"></div>')
    },function(){

    });*/




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
    /*var SidebarUpdater = new SidebarStatusUpdater();
    SidebarUpdater.init();*/
});

function SidebarStatusUpdater() {
    var self = this;
    this.init = function() {
        $.get('/events/sidebar.json', {"init": true}, function(response) {
            if(response.count == 0) {
            }else {

                function sortfunction(a, b){
                    return (a.sort - b.sort);
                }
                response.updates.sort(sortfunction);
                var html = '';
                $.each(response.updates, function(index, object) {
                    if(index == 0) {
                        self.date = object.created;
                    }
                    var info = '';
                    if(object.pitch.private == 1) {
                        info = '<img src="/img/private_pitch.png" width="16" height="25" alt="" />';
                    }
                    if(object.pitch.expert == 1) {
                        info = '<img src="/img/expert_opinion.png" width="16" height="25" alt="" />';
                    }
                    html +=  '<li>' +
                            '<a href="/pitches/view/' + object.pitch_id + '">' +
                                '<p class="date">' + object.humanCreatedShort + '</p>' +
                                '<p class="price"><span>' + self._priceDecorator(object.pitch.price) + '</span> P.-</p>' +
                                '<p>' + object.pitch.title + ', ' + object.pitch.category.lcTitle + '</p>' +
                                info +
                            '</a>'+
                        '</li>';
                });
                //html += '<div id="earlier_button"><a href="#" id="older-events">Ранее</a></div>';
                $('#sidebar-content').html(html);
                $('#current_pitch ul li').css({backgroundColor: '#e7e7e7'});
                $(document).everyTime(10000, function(i) {
                    self.autoupdate();
                });
            }
        });
    },
    this.autoupdate = function() {
        $.get('/events/sidebar.json', {"init": true, "created": self.date}, function(response) {
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
                    var info = '';
                    if(object.pitch.private == 1) {
                        info = '<img src="/img/private_pitch.png" width="16" height="25" alt="" />';
                    }
                    if(object.pitch.expert == 1) {
                        info = '<img src="/img/expert_opinion.png" width="16" height="25" alt="" />';
                    }
                    html +=  '<li>' +
                            '<a href="/pitches/view/' + object.pitch_id + '">' +
                                '<p class="date">' + object.humanCreatedShort + '</p>' +
                                '<p class="price"><span>' + self._priceDecorator(object.pitch.price) + '</span> P.-</p>' +
                                '<p>' + object.pitch.title + ', ' + object.pitch.category.lcTitle + '</p>' +
                                info +
                            '</a>'+
                        '</li>';
                });
                //html += '<div id="earlier_button"><a href="#" id="older-events">Ранее</a></div>';
                $('#sidebar-content').prepend(html);
                $('#current_pitch ul li').removeAttr('style');
                $('#current_pitch ul li').css({backgroundColor: '#e7e7e7'});

                /*$(document).everyTime(10000, function(i) {
                    self.autoupdate();
                });*/
            }
        });
    },
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
        /*$.get('/events/updates.json', {"init": true, "page": self.page}, function(response) {
            if(response.count == 0) {
                $('#no-updates').show();
                $('#updates-box').hide();
            }else {

                function sortfunction(a, b){
                    return (a.sort - b.sort);
                }
                response.updates.sort(sortfunction);
                var html = '';
                $.each(response.updates, function(index, object) {
                    if(object.solution == null) {

                    }else {
                        var newclass = '';
                        if(Date.parse(object.jsCreated) > offsetDate) {
                            newclass = ' newevent ';
                        }
                        if(object.type == 'PitchCreated') {
                            newclass = ' newpitchstream ';
                        }
                        if(index == 0) {
                            self.date = object.created;
                        }
                        if(typeof(object.solution.images.solution_galleryLargeSize) != "undefined") {
                            if(typeof(object.solution.images.solution_galleryLargeSize.length) == "undefined") {
                                var imageurl = object.solution.images.solution_galleryLargeSize.weburl;
                            }else{
                                var imageurl = object.solution.images.solution_galleryLargeSize[0].weburl;
                            }
                            if(object.type == 'PitchCreated') {
                                var imageurl = '/img/zaglushka.jpg';
                            }
                            var extraUI = '';
                            if(object.type != 'PitchCreated') {
                                extraUI = '<ul class="group">'+
                                '<li><a href="#"></a></li>'+
                                '<li><a href="#"></a></li>'+
                                '<li><a href="#"></a></li>'+
                                '<li><a href="#"></a></li>'+
                                '<li><a href="#"></a></li>'+
                                '</ul>'+
                                '<p class="visit_number">' + object.solution.views + '</p>'+
                                '<p class="fb_like"><a href="#">' + object.solution.likes + '</a></p>'
                            }
                            html +=  '<div class="obnovlenia_box ' + newclass + 'group">'+
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
                        }
                    }
                });
                html += '<div id="earlier_button"><a href="#" id="older-events">Ранее</a></div>';
                //$('#updates-box').html(html);

            }
        });*/
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
                    console.log(object);
                    if(typeof(object.solution.images.solution_galleryLargeSize.length) == "undefined") {
                        var imageurl = object.solution.images.solution_galleryLargeSize.weburl;
                    }else{
                        var imageurl = object.solution.images.solution_galleryLargeSize[0].weburl;
                    }
                    if(object.type == 'PitchCreated') {
                        var imageurl = '/img/zaglushka.jpg';
                    }
                    var extraUI = '';
                    if(object.type != 'PitchCreated') {
                        extraUI = '<ul class="group">'+
                            '<li><a href="#"></a></li>'+
                            '<li><a href="#"></a></li>'+
                            '<li><a href="#"></a></li>'+
                            '<li><a href="#"></a></li>'+
                            '<li><a href="#"></a></li>'+
                            '</ul>'+
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
                //html += '<div id="earlier_button"><a href="#" id="older-events">Ранее</a></div>';
                $('#updates-box').prepend(html);
            }
        });
    }
    this.nextPage = function() {
        self.page += 1;
        $.get('/events/updates.json', {"init": true, "page": self.page}, function(response) {
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
                    if(typeof(object.solution.images.solution_galleryLargeSize.length) == "undefined") {
                        var imageurl = object.solution.images.solution_galleryLargeSize.weburl;
                    }else{
                        var imageurl = object.solution.images.solution_galleryLargeSize[0].weburl;
                    }
                    html +=  '<div class="obnovlenia_box group">'+
                        '<section class="global_info">'+
                            '<p>' + object.humanType + '</p>'+
                            '<p class="designer_name">' + object.creator + '</p>'+
                            '<p class="add_date">' + object.humanCreated + '</p>'+
                            '<ul class="group">'+
                                '<li><a href="#"></a></li>'+
                                '<li><a href="#"></a></li>'+
                                '<li><a href="#"></a></li>'+
                                '<li><a href="#"></a></li>'+
                                '<li><a href="#"></a></li>'+
                            '</ul>'+
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
                //html += '<div id="earlier_button"><a href="#" id="older-events">Ранее</a></div>';
                $('#updates-box').append(html + '<div id="earlier_button"><a href="#" id="older-events">Ранее</a></div>');
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

    /* Zebra */

    $(document).on('mouseenter', '.highlighted, .even, .odd', function() {
        //if($('.pitches-name-td-img', this).hasClass('expand-link')) {
        //$('.pitches-name-td-img', this).attr('src', '/img/arrow_grey.png')

        /*var row = $(this);
        if($(this).hasClass('highlighted')) {
            var classToSave = 'highlighted';
        }else if($(this).hasClass('even')) {
            var classToSave = 'even';
        }else if($(this).hasClass('odd')) {
            var classToSave = 'odd';
        }

        row.attr('rel', classToSave);
        row.removeClass(row.attr('rel'));
        row.addClass('pitch-mouseover');*/
        //}
    });

    $(document).on('mouseleave', '.pitch-mouseover', function() {
        //var row = $(this);

        //if(!row.hasClass('pitch-open')){
            //$('.pitches-name-td-img', this).attr('src', '/img/arrow.png')
            /*row.removeClass('pitch-mouseover');
            row.addClass(row.attr('rel'));
            row.removeAttr('rel');*/
        //}
    });
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
        $(document).on('click', '.nav-page', function() {
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
                if(($('#user_id').val() == object.user_id) && (object.status == 0)) {
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
                '<a href="#" style="font-size: 11px;">' + object.category.title + '</a>' +
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
        $(document).on('click', '.nav-page', function() {
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
                '<a href="#" style="font-size: 11px;">' + object.category.title + '</a>' +
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
    if(($('input[name="cashintype"]:checked').data('pay') == 'cards') && ($('input[name="accountnum"]').val().length < 20)) {
        alert('Введите номер своего счета, он должен содержать не меньше 20 символов!');
        return false;
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
    }else {
        $('#cards').hide();
        $('#wmr').show();
    }
});

// details.js end
/* ==============*/
 $('.ajaxoffice').live('click', function() {
     //console.log($(this).attr('href'));
     var url = $(this).attr('href');
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
    var self = this;
    this.reset = function(data) {
        $('.referal-block').hide();
        $('#userPhone').val('');
        if (data.phone == 0) {
            $('.block-1').fadeIn(200);
        } else if (data.phone_valid == 0) {
            $('.block-2').fadeIn(200);
        } else {
            $('.block-3').fadeIn(200);
        }
    }
}
$(document).ready(function() {
    /*
     * Send SMS Code
     */
    $(document).on('submit', '#referal-1', function(e) {
        e.preventDefault();
        $.post($(this).attr('action') + '.json', {
            'userPhone': $('#userPhone').val(),
        }, function(result) {
            if (result == 'false') {
                // Tooltip
                var position = $('#userPhone').position();
                position.top += 55;
                $('p', '#tooltip-phone').text('К сожалению, мы не сможем подтвердить ваш телефон. Пожалуйста, укажите другой номер.');
                $('#tooltip-phone').css(position).fadeIn(200);
                setTimeout(function() { $('#tooltip-phone').fadeOut(200); }, 5000);
            } else {
                if (result.respond.indexOf('error') != -1) {
                    // Tooltip
                    var position = $('#userPhone').position();
                    position.top += 55;
                    $('p', '#tooltip-phone').text('Произошел сбой доставки SMS-сообщения. Попробуйте позже.');
                    $('#tooltip-phone').css(position).fadeIn(200);
                    setTimeout(function() { $('#tooltip-phone').fadeOut(200); }, 5000);
                } else {
                    $('.phone-number', '.referal-title').text(result.phone);
                    Ref.reset(result);
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
            'verifyCode': $('#verifyCode').val(),
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
});
