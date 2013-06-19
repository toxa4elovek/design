$(document).ready(function() {
    var PTable = new ParticipateTableLoader;
    PTable.init();

    var FTable = new FavesTableLoader;
    FTable.init();


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

    $(document).on('mouseover', '.mypitch_edit_link', function() {
        $('img', $(this)).attr('src', '/img/pencil_red.png');
    });

    $(document).on('mouseout', '.mypitch_edit_link', function() {
        $('img', $(this)).attr('src', '/img/pencil.png');
    });

    $(document).on('mouseover', '.mypitch_delete_link', function() {
        $('img', $(this)).attr('src', '/img/kreuz_red.png');
    });

    $(document).on('mouseout', '.mypitch_delete_link', function() {
        $('img', $(this)).attr('src', '/img/kreuz.png');
    });

    $(document).on('mouseover', '.mypitch_pay_link', function() {
        $('img', $(this)).attr('src', '/img/buy_red.png');
    });

    $(document).on('mouseout', '.mypitch_pay_link', function() {
        $('img', $(this)).attr('src', '/img/buy.png');
    })


    $(document).on('click', '.mypitch_delete_link', function() {
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
            var row = $(this);
            if($(this).hasClass('highlighted')) {
                var classToSave = 'highlighted';
            }else if($(this).hasClass('even')) {
                var classToSave = 'even';
            }else if($(this).hasClass('odd')) {
                var classToSave = 'odd';
            }

            row.attr('rel', classToSave);
            row.removeClass(row.attr('rel'));
            row.addClass('pitch-mouseover');
        //}
    });

    $(document).on('mouseleave', '.pitch-mouseover', function() {
        var row = $(this);

        if(!row.hasClass('pitch-open')){
            //$('.pitches-name-td-img', this).attr('src', '/img/arrow.png')
            row.removeClass('pitch-mouseover');
            row.addClass(row.attr('rel'));
            row.removeAttr('rel');
        }
    });
})

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
            if((object.billed == 0) && (object.status == 0)) {
                var status = 'Ожидание оплаты';
                if($('#user_id').val() == object.user_id) {
                    total = object.total;
                    editLink = '<a href="/pitches/edit/' + object.id + '" class="mypitch_edit_link" title="Редактировать"><img src="/img/edit_icon_white.png" class="pitches-name-td-img" width="18" height="18"></a><a href="/pitches/delete/' + object.id + '" rel="' + object.id  +'"  class="mypitch_delete_link" title="Удалить"><img src="/img/kreuz.png" class="pitches-name-td-img" width="12" height="12"></a><a href="/pitches/edit/' + object.id + '#step3" class="mypitch_pay_link" title="Оплатить"><img src="/img/buy.png" class="pitches-name-td2-img"  width="18" height="18"></a>';
                }
            }else {
                if((object.published == 0) && (object.brief == 1)) {
                    var status = 'Ожидайте звонка';
                }else {
                    var status = object.startedHuman;
                }
                if(($('#user_id').val() == object.user_id) && (object.status == 0)) {

                    editLink = '<a href="/pitches/edit/' + object.id + '" class="mypitch_edit_link" title="Редактировать"><img src="/img/edit_icon_white.png" class="pitches-name-td-img" width="18" height="18"></a>';
                }
            }

            if(object.status != 2) {
                var link = '/pitches/view/' + object.id;
                if((object.status == 1) && (object.awarded == 0)) {
                    var status = 'Выбор победителя';
                }
                if((object.status == 1) && (object.awarded != 0)) {
                    var status = 'Победитель выбран';
                }
            }else {
                var status = 'Питч завершен';
                var link = '/users/step2/' + object.awarded;
            }
            var shortIndustry = object.industry;
            if(shortIndustry.length > 80) {
                shortIndustry = shortIndustry.substr(0, 75) + '...';
            }

            html += '<tr data-id="' + object.id + '" class="' + rowClass + '">' +
                '<td class="icons"></td>' +
                '<td class="pitches-name">' +
                editLink +
                '<div>' +
                '<a href="' + link + '" class="expand-link">' + object.title + '</a>' +
                '<span>' + shortIndustry + '</span>' +
                '</div>' +
                '</td>' +
                '<td class="pitches-cat">' +
                '<a href="#">' + object.category.title + '</a>' +
                '</td>' +
                '<td class="idea">' + object.ideas_count + '</td>' +
                '<td class="pitches-time">' + status + '</td>' +
                '<td class="price">' + self._priceDecorator(object.price) + ' р.-</td>' +
                '</tr>' +
                '<tr class="pitch-collapsed">' +
                '<td class="icons"></td>' +
                '<td colspan="3" class="al-info-pitch"><p>' + object.description +
                '</p><a href="/pitches/view/' + object.id + '" class="go-pitch">Перейти к питчу</a>' +
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
            html += '<tr data-id="' + object.id + '" class="' + rowClass + '">' +
                '<td class="icons"></td>' +
                '<td class="pitches-name">' +
                '<a href="#" class="unfav" data-pitchId="' + object.id + '" style="position: relative; top: -20px;"><img style="padding:0 0 0 8px" src="/img/kreuz.png" width="12" height="12"></a>' +
                '<div style="padding-top:3px">' +
                '<a href="/pitches/view/' + object.id + '" class="expand-link">' + object.title + '</a>' +
                '<span>' + shortIndustry + '</span>' +
                '</div>' +
                '</td>' +
                '<td class="pitches-cat">' +
                '<a href="#">' + object.category.title + '</a>' +
                '</td>' +
                '<td class="idea">' + object.ideas_count + '</td>' +
                '<td class="pitches-time">' + status + '</td>' +
                '<td class="price">' + self._priceDecorator(object.price) + ' р.-</td>' +
                '</tr>' +
                '<tr class="pitch-collapsed">' +
                '<td class="icons"></td>' +
                '<td colspan="3" class="al-info-pitch"><p>' + object.description +
                '</p><a href="/pitches/view/' + object.id + '" class="go-pitch">Перейти к питчу</a>' +
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