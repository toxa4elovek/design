/* Class */

function TableLoader() {
    var self = this;
    // storage object for saving data
    this.storage = {};
    this.container = $('#table-content');
    this.nav = $('.page-nambe-nav');
    this.limit = 3; //even number
    this.navNeub = 2;
    this.options = {
        "page": 1,
        "type": "current",
        "category": "all",
        "order": {"price": "desc"},
        "priceFilter": "all",
        "searchTerm": ""
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
        });
        
        if(window.location.href.match(/finished/)) {
            $('.pitches-type:nth-child(2)').addClass('active-pitches');
            self.options.type = 'finished';
        }
        if(window.location.pathname.match(/(\/pitches\/?)$/)) {
            //self.fetchTable(self.options);
        }else {
            if(window.location.pathname.match(/(\/pitches\/?)(\d+)$/)) {
                var results = window.location.pathname.match(/(\/pitches\/?)(\d+)$/);
                $('a[data-group=category][data-value=' + results[2] + ']').click();
            }else {
                self.fetchTable(self.options);
            }
        }
    };
    this.fetchTable = function(options) {
        var pathname = window.location.pathname;
        if (!options.fromQuery) {
            var queryParams = $.param(options);
            window.history.pushState('object or string', 'Title', pathname + '?' + queryParams); // @todo Check params
        } else {
            queryToSearchField(options);
        }
        $('.pitches-ajax-wrapper').fadeIn(100);
        $.get('/pitches.json', options, function(response) {
            self.page = response.data.info.page;
            self.total = response.data.info.total;
            self.renderTable(response);
            self.renderNav(response);
            $('.pitches-ajax-wrapper').fadeOut(50);
            if (!options.fromQuery) { // Came Back - No Scroll
                $('html, body').animate({ scrollTop: 0 }, 600 );
            }
            if (response.data.pitches.length == 0) {
                $('.all-pitches, .foot-content').hide();
                var image = '/img/filter-arrow-down.png';
                $('#filterToggle').data('dir', 'up');
                $('img', '#filterToggle').attr('src', image);
                $('#filtertab').hide();
                $('.no-result').show();
            } else {
                $('.no-result').hide();
                $('.all-pitches, .foot-content').show();
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
            var temp  = object.started.split(/\-|\s/)
            var pitchStartDate = new Date(temp.slice(0,3).join('/')+' '+temp[3]);
            var plusoneday = Math.round(pitchStartDate.getTime() / 1000) + 24 * 60 * 60;
            var currentunixtime = Math.round(new Date().getTime() / 1000);
            if(object.new_pitch == 1) {
                rowClass += ' newpitch';
            }else {
                if(object.pinned == 1) {
                    rowClass += ' highlighted';
                }
            }
            var icons = '';
            if(object.guaranteed == 1) {
                icons = '<img style="width:30px; margin-top:4px;float:left;" src="/img/guarantee.png" alt="Награда гарантирована">';
            }
            var timeleft = '';
            if(object.status == 0) {
                if((object.private == 1) && (object.expert == 0)){
                    rowClass += ' close';
                    icons += '<img style="margin-right: 5px;margin-left: -5px;margin-top: 1px" src="/img/icon-4.png" title="Закрытый питч." alt="Закрытый питч.">';
                }
                if((object.private == 0) && (object.expert == 1)){
                    rowClass += ' expert';
                    icons += '<img style="margin-right: 5px;margin-top: 1px" src="/img/icon-5.png" title="Важно мнение эксперта." alt="Важно мнение эксперта.">';

                }
                if((object.private == 1) && (object.expert == 1)){
                    rowClass += ' close-and-expert';
                    icons += '<img style="margin-right: 5px;margin-top: 1px" src="/img/icon-3.png" title="Закрытый питч. Важно мнение эксперта." alt="Закрытый питч. Важно мнение эксперта.">';
                }
                if ((object.published == 0) && (object.billed == 0) && (object.moderated != 1)) {
                    timeleft = 'Ожидание оплаты';
                } else if ((object.published == 0) && (object.billed == 0) && (object.moderated == 1)) {
                    timeleft = 'Ожидание<br />модерации';
                } else if((object.published == 0) && (object.billed == 1) && (object.brief == 1)) {
                    timeleft = 'Ожидайте звонка';
                } else {
                    timeleft = object.startedHuman;
                }
            } else if ((object.status == 1) && (object.awarded == 0)) {
                rowClass += ' selection';
                icons += '<img style="margin-right: 5px;margin-top: 1px" src="/img/icon-1.png" title="Идёт выбор победителя." alt="Идёт выбор победителя.">';
                timeleft = 'Выбор победителя';
            } else if ((object.status == 2) || ((object.status == 1) && (object.awarded > 0))){
                rowClass += ' pitch-end';
                icons += '<img style="margin-right: 5px;margin-top: 1px" src="/img/icon-2.png" title="Питч завершён, победитель выбран" alt="Закрытый питч. Важно мнение эксперта.">';
                if(object.status == 2) {
                    timeleft = 'Питч завершен';
                }else if((object.status == 1) && (object.awarded > 0)) {
                    timeleft = 'Победитель выбран';
                }else if((object.status == 1) && (object.awarded == 0)) {
                    timeleft = 'Выбор победителя';
                }else {
                    timeleft = object.startedHuman;
                }
            }

            var imgForDraft = '';
            if((object.user_id == $('#user_id').val()) && (object.awarded == '')) {
                if (object.published == 1) {
                    imgForDraft = ' not-draft';
                }
                if(object.billed == 1) {
                    var userString = '<a title="Редактировать" href="/pitches/edit/' + object.id + '" class="mypitch_edit_link' + imgForDraft + '"><img class="pitches-name-td-img" src="/img/1.gif"></a>';
                }else {
                    var userString = '<a href="/pitches/edit/' + object.id + '" class="mypitch_edit_link" title="Редактировать"><img src="/img/1.gif" class="pitches-name-td-img"></a><a href="/pitches/delete/' + object.id + '" rel="' + object.id  +'" class="mypitch_delete_link" title="Удалить"><img src="/img/1.gif" class="pitches-name-td-img"></a><a href="/pitches/edit/' + object.id + '#step3" class="mypitch_pay_link" title="Оплатить"><img src="/img/1.gif" class="pitches-name-td2-img"></a>';
                }
            }else {
                var userString = '<a href="#"><img class="pitches-name-td-img expand-link" src="/img/arrow.png" /></a>';
            }

            if(object.private == 1) {
                object.editedDescription = 'Это закрытый питч и вам нужно подписать соглашение о неразглашении.';
            }
            var shortIndustry = object.industry;
            if(shortIndustry.length > 80) {
                shortIndustry = shortIndustry.substr(0, 75) + '...';
            }
            var textGuarantee = '';
            if(object.guaranteed == 1) {
                textGuarantee = '<br><span style="font-size: 11px; font-family: Arial; font-weight: normal;  text-transform: uppercase">гарантированы</span>';
            }
            var pitchPath = 'view';
            if (object.ideas_count == 0) {
                pitchPath = 'details';
            }
            var pitchMultiple = '';
            if (object.multiple) {
                pitchMultiple = '<br>' + object.multiple;
            }

            html += '<tr data-id="' + object.id + '" class="' + rowClass + '">' +
                '<td class="icons">' + icons + '</td>' +
                '<td class="pitches-name">' +
                userString +
                '<div style="padding-left: 34px; padding-right: 12px;">' +
                '<a href="/pitches/' + pitchPath + '/' + object.id + '" class="">' + object.title + '</a>' +
                '<!--span style="font-size: 11px;">' + shortIndustry + '</span-->' +
                '</div>' +
                '</td>' +
                '<td class="pitches-cat" style="padding-left: 10px; width: 102px; padding-right: 10px;">' +
                '<a href="#" style="font-size: 11px;">' + object.category.title + pitchMultiple + '</a>' +
                '</td>' +
                '<td class="idea" style="font-size: 11px;">' + object.ideas_count + '</td>' +
                '<td class="pitches-time" style="font-size: 11px;">' + timeleft + '</td>' +
                '<td class="price">' + self._priceDecorator(object.price) + ' Р.-' + textGuarantee + '</td>' +
                '</tr>' +
                '<tr class="pitch-collapsed">' +
                '<td class="icons"></td>' +
                '<td colspan="3" class="al-info-pitch"><p>' + object.editedDescription +
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
        //menu.children().children('a').removeClass('selected');
        //menu.children().children('a[rel=' + value + ']').addClass('selected')
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