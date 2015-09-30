'use strict';

function TableLoader() {
    var self = this;

    this.storage = {};
    this.container = $('#table-content');
    this.nav = $('.page-nambe-nav');
    this.limit = 3;
    this.options = {
        "page": 1,
        "type": "current",
        "category": "all",
        "order": { "price": "desc" },
        "priceFilter": "all",
        "searchTerm": ""
    };
    this.page = 1;
    this.type = 'current';

    this.init = function () {
        self.setFilter('category', $('input[name=category]').val(), $('#cat-menu'));

        $(document).on('click', '.nav-page', function () {
            var page = this.getAttribute('rel');
            if (page == 'prev') {
                page = parseInt(self.page) - 1;
                if (page < 1) page = 1;
            }
            if (page == 'next') {
                page = parseInt(self.page) + 1;
                if (page > self.total) page = self.total;
            }
            self.options.page = page;
            self.fetchTable(self.options);
            return false;
        });

        if (window.location.href.match(/finished/)) {
            $('.pitches-type:nth-child(2)').addClass('active-pitches');
            self.options.type = 'finished';
        }
        if (window.location.pathname.match(/(\/pitches\/?)$/)) {} else {
                if (window.location.pathname.match(/(\/pitches\/?)(\d+)$/)) {
                    var results = window.location.pathname.match(/(\/pitches\/?)(\d+)$/);
                    $('a[data-group=category][data-value=' + results[2] + ']').click();
                } else {
                    self.fetchTable(self.options);
                }
            }
    };
    this.fetchTable = function (options) {
        var pathname = window.location.pathname;
        if (!options.fromQuery) {
            var queryParams = $.param(options);
            window.history.pushState('object or string', 'Title', pathname + '?' + queryParams);
        } else {
                queryToSearchField(options);
            }
        $('.pitches-ajax-wrapper').fadeIn(100);

        $.get('/pitches.json', options, function (response) {
            self.page = response.data.info.page;
            self.total = response.data.info.total;
            self.renderTable(response);
            self.renderNav(response);
            $('.pitches-ajax-wrapper').fadeOut(50);
            if (!options.fromQuery) {
                $('html, body').animate({ scrollTop: 0 }, 600);
            }
            if (response.data.pitches.length == 0) {
                var image = '/img/filter-arrow-down.png';
                $('.all-pitches, .foot-content').hide();
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
    this.renderTable = function (response) {
        var html = '';
        var counter = 1;

        response.data.pitches.sort(sortfunction);

        function sortfunction(a, b) {
            return a.sort - b.sort;
        }

        $.each(response.data.pitches, function (index, object) {
            var rowClass = 'odd';
            if (counter % 2 == 0) {
                rowClass = 'even';
            }
            if (object.new_pitch == 1) {
                rowClass += ' newpitch';
            } else {
                if (object.pinned == 1) {
                    rowClass += ' highlighted';
                }
            }
            var icons = '';
            if (object.guaranteed == 1) {
                icons = '<img style="width:30px; margin-top:4px;float:left;" src="/img/guarantee.png" alt="Награда гарантирована">';
            }
            var timeLeft = '';
            if (object.status == 0) {
                if (object['private'] == 1 && object.expert == 0) {
                    rowClass += ' close';
                    icons += '<img style="margin-right: 5px;margin-left: -5px;margin-top: 1px" src="/img/icon-4.png" title="Закрытый проект." alt="Закрытый проект.">';
                }
                if (object['private'] == 0 && object.expert == 1) {
                    rowClass += ' expert';
                    icons += '<img style="margin-right: 5px;margin-top: 1px" src="/img/icon-5.png" title="Важно мнение эксперта." alt="Важно мнение эксперта.">';
                }
                if (object['private'] == 1 && object.expert == 1) {
                    rowClass += ' close-and-expert';
                    icons += '<img style="margin-right: 5px;margin-top: 1px" src="/img/icon-3.png" title="Закрытый проект. Важно мнение эксперта." alt="Закрытый проект. Важно мнение эксперта.">';
                }
                if (object.published == 0 && object.billed == 0 && object.moderated != 1) {
                    timeLeft = 'Ожидание оплаты';
                } else if (object.published == 0 && object.billed == 0 && object.moderated == 1) {
                    timeLeft = 'Ожидание<br />модерации';
                } else if (object.published == 0 && object.billed == 1 && object.brief == 1) {
                    timeLeft = 'Ожидайте звонка';
                } else {
                    timeLeft = object.startedHuman;
                }
            } else if (object.status == 1 && object.awarded == 0) {
                rowClass += ' selection';
                icons += '<img style="margin-right: 5px;margin-top: 1px" src="/img/icon-1.png" title="Идёт выбор победителя." alt="Идёт выбор победителя.">';
                timeLeft = 'Выбор победителя';
            } else if (object.status == 2 || object.status == 1 && object.awarded > 0) {
                rowClass += ' pitch-end';
                icons += '<img style="margin-right: 5px;margin-top: 1px" src="/img/icon-2.png" title="Проект завершён, победитель выбран" alt="Закрытый проект. Важно мнение эксперта.">';
                if (object.status == 2) {
                    timeLeft = 'Проект завершен';
                } else if (object.status == 1 && object.awarded > 0) {
                    timeLeft = 'Победитель выбран';
                } else if (object.status == 1 && object.awarded == 0) {
                    timeLeft = 'Выбор победителя';
                } else {
                    timeLeft = object.startedHuman;
                }
            }

            var imgForDraft = '';
            var userString = '';
            if (object.user_id == $('#user_id').val() && object.awarded == '') {
                if (object.published == 1) {
                    imgForDraft = ' not-draft';
                }
                if (object.billed == 1) {
                    userString = '<a title="Редактировать" href="/pitches/edit/' + object.id + '" class="mypitch_edit_link' + imgForDraft + '"><img class="pitches-name-td-img" src="/img/1.gif"></a>';
                } else {
                    userString = '<a href="/pitches/edit/' + object.id + '" class="mypitch_edit_link" title="Редактировать"><img src="/img/1.gif" class="pitches-name-td-img"></a><a href="/pitches/delete/' + object.id + '" rel="' + object.id + '" class="mypitch_delete_link" title="Удалить"><img src="/img/1.gif" class="pitches-name-td-img"></a><a href="/pitches/edit/' + object.id + '#step3" class="mypitch_pay_link" title="Оплатить"><img src="/img/1.gif" class="pitches-name-td2-img"></a>';
                }
            } else {
                userString = '<a href="#"><img class="pitches-name-td-img expand-link" src="/img/arrow.png" /></a>';
            }

            if (object['private'] == 1) {
                object.editedDescription = 'Это закрытый проект и вам нужно подписать соглашение о неразглашении.';
            }
            var textGuarantee = '';
            if (object.guaranteed == 1) {
                textGuarantee = '<br><span style="font-size: 11px; font-family: Arial, serif; font-weight: normal;  text-transform: uppercase">гарантированы</span>';
            }
            var pitchPath = 'view';
            if (object.ideas_count == 0) {
                pitchPath = 'details';
            }
            var pitchMultiple = '';
            if (object.multiple) {
                pitchMultiple = '<br>' + object.multiple;
            }
            var categoryLinkHref = '#';
            if (object.category.id == 20) {
                categoryLinkHref = '/pages/subscribe';
            }
            html += '<tr data-id="' + object.id + '" class="' + rowClass + '">\n                <td class="icons">' + icons + '</td>\n                <td class="pitches-name">\n                ' + userString + ' +\n                <div style="padding-left: 34px; padding-right: 12px;">\n                <a href="/pitches/' + pitchPath + '/' + object.id + '" class="">' + object.title + '</a>\n                </div>\n                </td>\n                <td class="pitches-cat" style="padding-left: 10px; width: 102px; padding-right: 10px;">\n                <a href="' + categoryLinkHref + '" target="_blank" style="font-size: 11px;">' + object.category.title + pitchMultiple + '</a>\n                </td>\n                <td class="idea" style="font-size: 11px;">' + object.ideas_count + '</td>\n                <td class="pitches-time" style="font-size: 11px;">' + timeLeft + '</td>\n                <td class="price">' + self._priceDecorator(object.price) + ' Р.-' + textGuarantee + '</td>\n                </tr>\n                <tr class="pitch-collapsed">\n                <td class="icons"></td>\n                <td colspan="3" class="al-info-pitch"><p>' + object.editedDescription.replace(/<\/?[^>]+>/gi, '') + '\n                </p><a href="/pitches/' + pitchPath + '/' + object.id + '" class="go-pitch">Перейти к проекту</a>\n                </td>\n                <td></td>\n                <td></td>\n                </tr>';
            counter++;
        });
        self.container.html(html);
    };
    this.renderNav = function (response) {
        var navInfo = response.data.info;
        var navBar = '';
        var prepend = '';
        var append = '';
        if (navInfo.total > 1) {
            if (navInfo.total > 1) {
                prepend = '<a href="#" class="nav-page" rel="prev"><</a>';
                append = '<a href="#" class="nav-page" rel="next">></a>';
            }
            if (navInfo.total <= 5) {
                for (i = 1; i <= navInfo.total; i++) {
                    if (navInfo.page == i) {
                        navBar += '<a href="#" class="this-page nav-page" rel="' + i + '">' + i + '</a>';
                    } else {
                        navBar += '<a href="#" class="nav-page" rel="' + i + '">' + i + '</a>';
                    }
                }
            } else {
                if (navInfo.page - 3 <= 0) {
                    for (i = 1; i <= 4; i++) {
                        if (navInfo.page == i) {
                            navBar += '<a href="#" class="this-page nav-page" rel="' + i + '">' + i + '</a>';
                        } else {
                            navBar += '<a href="#" class="nav-page" rel="' + i + '">' + i + '</a>';
                        }
                    }
                    navBar += ' ... ';
                    navBar += '<a href="#" class="nav-page" rel="' + navInfo.total + '">' + navInfo.total + '</a>';
                }

                if (navInfo.page - 3 > 0 && navInfo.total > navInfo.page + 2) {
                    navBar += '<a href="#" class="nav-page" rel="1">1</a>';
                    navBar += ' ... ';
                    for (i = navInfo.page - 1; i <= navInfo.page + 1; i++) {
                        if (navInfo.page == i) {
                            navBar += '<a href="#" class="this-page nav-page" rel="' + i + '">' + i + '</a>';
                        } else {
                            navBar += '<a href="#" class="nav-page" rel="' + i + '">' + i + '</a>';
                        }
                    }
                    navBar += ' ... ';
                    navBar += '<a href="#" class="nav-page" rel="' + navInfo.total + '">' + navInfo.total + '</a>';
                }

                if (navInfo.total <= navInfo.page + 2) {
                    navBar += '<a href="#" class="nav-page" rel="1">1</a>';
                    navBar += ' ... ';
                    for (i = navInfo.total - 3; i <= navInfo.total; i++) {
                        if (navInfo.page == i) {
                            navBar += '<a href="#" class="this-page nav-page" rel="' + i + '">' + i + '</a>';
                        } else {
                            navBar += '<a href="#" class="nav-page" rel="' + i + '">' + i + '</a>';
                        }
                    }
                }
            }
        }
        navBar = prepend + navBar + append;
        self.nav.html(navBar);
    };
    this.setFilter = function (key, value) {
        self.options[key] = value;
    };
    this._priceDecorator = function (price) {
        price = price.replace(/(.*)\.00/g, "$1");
        var counter = 1;
        while (price.match(/\w\w\w\w/)) {
            price = price.replace(/^(\w*)(\w\w\w)(\W.*)?$/, "$1 $2$3");
            counter++;
            if (counter > 6) break;
        }
        return price;
    };
}