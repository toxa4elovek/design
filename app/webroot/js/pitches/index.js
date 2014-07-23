$(document).ready(function() {
    
    checkPlaceholder();
    
    function goSearch() {
        var searchTerm = $('#searchTerm').val();
        Table.options.searchTerm = searchTerm;
        if(!isCurrentTypeFilterExists()) {
            Table.setFilter('type', 'all');
        }
        Table.fetchTable(Table.options);
        var image = '/img/filter-arrow-down.png';
        $('#filterToggle').data('dir', 'up');
        $('img', '#filterToggle').attr('src', image);
        $('#filtertab').hide();
    }

	/* Filters */

    $('body').click(function() {
        $('#cat-menu, #price-menu, #timelimit-menu').hide().parent().removeClass('active-list');
    });

    $(document).on('click', '#goSearch', function(){
        goSearch();
        return false;
    });
    
    $(document).on('keyup', '#searchTerm', function(e) {
        checkFilterClear();
        if (e.keyCode == 13) {
            goSearch();
        }
    });

    $('#searchTerm').keyboard('backspace', function() {
        if(($('li[data-group]').length > 0) && ($('#searchTerm').val() == '')) {
            $('.removeTag', 'li[data-group]:last').click()
        }
    })

    $('#searchTerm').on('focus', function() {
        if ($(this).val() == placeholder) {
            $(this).val('').removeClass('placeholder');
        }
        $('#filterContainer').css('border', '4px solid rgb(231, 231, 231)');
        $('#filterContainer').css('box-shadow', '');
        toggleFilterTab();
        //$('#filterContainer').css('height', '37px')
        //$('#filterContainer').css('padding-top', '6px')
        //$('#filterContainer').css('margin-top', '2px')
    })

    $(document).on('blur', '#searchTerm', function() {
        checkPlaceholder();
        $('#filterContainer').css('box-shadow', '0 1px 2px rgba(0, 0, 0, 0.2) inset');
        $('#filterContainer').css('border', '4px solid #F3F3F3');
        //$('#filterContainer').css('height', '41px')
        //$('#filterContainer').css('padding-top', '10px')
       //#$('#filterContainer').css('margin-top', '')
    })

    $(document).on('click', function(e) {
        if ($(e.target).is('#searchTerm')) {
            return false;
        }
        var image = '/img/filter-arrow-down.png';
        $('#filterToggle').data('dir', 'up');
        $('img', '#filterToggle').attr('src', image);
        $('#filtertab').hide();
    });

    $('#filtertab').click(function(event){
        event.stopPropagation();
    });

    $(document).on('click', '#filterToggle', function(){
        toggleFilterTab();
        return false;
    });
    
    function toggleFilterTab() {
        var $el = $('#filterToggle');
        var dir = $el.data('dir');
        if(dir == 'up') {
            var image = '/img/filter-arrow-up.png';
            $el.data('dir', 'down');
        }else {
            var image = '/img/filter-arrow-down.png';
            $el.data('dir', 'up');
        }
        $('img', $el).attr('src', image);
        $('#filtertab').toggle();
    }

    $(document).on('click', '.removeTag', function() {
        removeTag($(this));
        checkPlaceholder();
        recalculateBox();
        
        //Table.fetchTable(Table.options);
        goSearch();
        return false;
    })
    
    function removeTag(el) {
        el.parent().remove();
        Table.setFilter(el.data('group'), 'all');
        if ($('li[data-group]').length == 0) {
            Table.setFilter('type', 'current');
        } else {
            if ((el.data('group') == 'timeframe') && ($('li[data-group=type]').length == 0)) {
                Table.setFilter('type', 'all');
            }
        }
        checkFilterClear();
    }
    
    $(document).on('click', '#filterClear', function() {
        $.each($('.removeTag', '#filterbox'), function(index, object) {
            removeTag($(this));
        });
        $('#searchTerm').val('');
        recalculateBox();
        checkPlaceholder();
        goSearch();
        return false;
    });

    $('.filterlist li').on('mouseover', function() {
        if($(this).hasClass('first')) {

        }else {
            $(this).css('background-color', '#ebf8fc')
        }
    } )

    $('.filterlist li').on('mouseout', function() {
        $(this).css('background-color', '')
    } )


	// categories
	$('#cat-menu-toggle').click(function() {
		var menuItem = $(this).parent();
        $('#cat-menu, #price-menu, #timelimit-menu').hide().parent().removeClass('active-list');
		$('#cat-menu').toggle();
		//active-list
		if(menuItem.hasClass('active-list')) {
			menuItem.removeClass('active-list');
		}else {
			menuItem.addClass('active-list');
		}
		return false;
	});

	$(document).on('click', '.category-filter', function(){
		Table.setFilter('category', $(this).attr('rel'), $('#cat-menu'));
        if($(this).attr('rel') != 'all') {
            $('#cat-menu-label').html($('span', this).html());
        }else {
            $('#cat-menu-label').html($('#cat-menu-label').data('default'));
        }
		Table.fetchTable(Table.options);
		$('#cat-menu').hide().parent().removeClass('active-list');
		return false;
	});

	// price

	$('#price-menu-toggle').click(function() {
		var menuItem = $(this).parent();
        $('#cat-menu, #price-menu, #timelimit-menu').hide().parent().removeClass('active-list');
		$('#price-menu').toggle();
		//active-list
		if(menuItem.hasClass('active-list')) {
			menuItem.removeClass('active-list');
		}else {
			menuItem.addClass('active-list');
		}
		return false;
	}); 

	$(document).on('click', '.price-filter', function(){
		Table.setFilter('priceFilter', $(this).attr('rel'), $('#cpriceat-menu'));
        if($(this).attr('rel') != 'all') {
            $('#price-menu-label').html($('span', this).html());
        }else {
            $('#price-menu-label').html($('#price-menu-label').data('default'));
        }
		Table.fetchTable(Table.options);
		$('#price-menu').hide().parent().removeClass('active-list');
		return false;
	});

    // timelimit

    $('#timelimit-menu-toggle').click(function() {
        var menuItem = $(this).parent();
        $('#cat-menu, #price-menu, #timelimit-menu').hide().parent().removeClass('active-list');
        $('#timelimit-menu').toggle();
        //active-list
        if(menuItem.hasClass('active-list')) {
            menuItem.removeClass('active-list');
        }else {
            menuItem.addClass('active-list');
        }
        return false;
    });

    $(document).on('click', '.timelimit-filter', function(){
        Table.setFilter('timelimitFilter', $(this).attr('rel'), $('#cpriceat-menu'));
        if($(this).attr('rel') != 'all') {
            $('#timelimit-menu-label').html($('span', this).html());
        }else {
            $('#timelimit-menu-label').html($('#timelimit-menu-label').data('default'));
        }
        Table.fetchTable(Table.options);
        $('#price-menu').hide().parent().removeClass('active-list');
        return false;
    });

	/* Sorting*/

	// price
	$(document).on('click', '#sort-price', function(){
		var sortDirection = $(this).attr('rel');
		if(sortDirection == 'asc') {
			$(this).attr('rel', 'desc');
		}else {
			$(this).attr('rel', 'asc');
		}
		Table.options.order = {"price": sortDirection};
		Table.fetchTable(Table.options);
		return false;
	});

	// ideas_count
	$(document).on('click', '#sort-ideas_count', function(){
		var sortDirection = $(this).attr('rel');
		if(sortDirection == 'asc') {
			$(this).attr('rel', 'desc');
		}else {
			$(this).attr('rel', 'asc');
		}
		Table.options.order = {"ideas_count": sortDirection};
		Table.fetchTable(Table.options);
		return false;
	});

	// diff
	$(document).on('click', '#sort-finishDate', function(){
		var sortDirection = $(this).attr('rel');
		if(sortDirection == 'asc') {
			$(this).attr('rel', 'desc');
		}else {
			$(this).attr('rel', 'asc');
		}
		Table.options.order = {"finishDate": sortDirection};
		Table.fetchTable(Table.options);
		return false;
	});

    $('.filterlist a').on('click', function() { // Add tag
        $('li[data-group=' + $(this).data('group') + ']', '#filterbox').remove();
        var box = '<li style="margin-left:6px;" data-group="' + $(this).data('group') + '">' + $(this).text() + '<a class="removeTag" href="#" data-group="' + $(this).data('group') + '" data-value="' + $(this).data('value') + '"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>';
        $(box).appendTo('#filterbox');
        checkPlaceholder();
        recalculateBox();
        Table.setFilter($(this).data('group'), $(this).data('value'));
        if($(this).data('group') != 'type') {
            if(!isCurrentTypeFilterExists()) {
                Table.setFilter('type', 'all');
            }
            if($(this).data('group') == 'timeframe') {
                Table.setFilter('type', 'current');
            }
        }
        Table.fetchTable(Table.options);
        return false;
    })

    var isCurrentTypeFilterExists = function () {
        return ($('li[data-group=type]').length);
    }

    recalculateBox();

	// started
	$(document).on('click', '#sort-started', function(){
		var sortDirection = $(this).attr('rel');
		/*if(sortDirection == 'asc') {
			$(this).attr('rel', 'desc');
		}else {
			$(this).attr('rel', 'asc');
		}*/
		$('#timelimit-menu-label').html($('span', this).html());

		Table.options.order = {"started": sortDirection};
		Table.fetchTable(Table.options);
		return false;
	});

	// title
	$(document).on('click', '#sort-title', function(){
		var sortDirection = $(this).attr('data-dir');
		if(sortDirection == 'asc') {
			$(this).attr('data-dir', 'desc');
		}else {
			$(this).attr('data-dir', 'asc');
		}
		Table.options.order = {"title": sortDirection};
		Table.fetchTable(Table.options);
		return false;
	});

	// category
	$(document).on('click', '#sort-category', function(){
		var sortDirection = $(this).attr('rel');
		if(sortDirection == 'asc') {
			$(this).attr('rel', 'desc');
		}else {
			$(this).attr('rel', 'asc');
		}
		Table.options.order = {"category": sortDirection};
		Table.fetchTable(Table.options);
		return false;
	});

	/* Status filter */
	/*$(document).on('click', '.status-switch', function(){
		$('.pitches-type').children().removeClass('active-pitches');
		$(this).parent().addClass('active-pitches');
		Table.options.type = $(this).attr('rel');
		Table.fetchTable(Table.options);
		return false;
	})*/

	/* Zebra */

	$(document).on('mouseenter', '.highlighted, .even, .odd', function() {
        if($('.pitches-name-td-img', this).hasClass('expand-link')) {
            $('.pitches-name-td-img', this).attr('src', '/img/arrow_grey.png')
            var row = $(this);
            if($(this).hasClass('highlighted')) {
                var classToSave = 'highlighted';
            }else if($(this).hasClass('newpitch')) {
                var classToSave = 'newpitch';
            }else if($(this).hasClass('even')) {
                var classToSave = 'even';
            }else if($(this).hasClass('odd')) {
                var classToSave = 'odd';
            }
            row.attr('rel', classToSave);
            row.removeClass(row.attr('rel'));
            row.addClass('pitch-mouseover');
        }
	});

	$(document).on('mouseleave', '.pitch-mouseover', function() {
		var row = $(this);
		
		if(!row.hasClass('pitch-open')){
			$('.pitches-name-td-img', this).attr('src', '/img/arrow.png')
			row.removeClass('pitch-mouseover');
			row.addClass(row.attr('rel'));
			row.removeAttr('rel');
		}
	});

	/* Show/Hide cells */

	$(document).on('click', '.expand-link', function() {
		var parentRow = $(this).parent().parent().parent();
		var descRow = parentRow.next();
		if(parentRow.hasClass('pitch-open')) {
			descRow.hide().removeClass('pitch-open').addClass('pitch-collapsed');
			parentRow.removeClass('pitch-open');
			$('.pitches-name-td-img', $(this).parent().parent()).attr('src', '/img/arrow_grey.png')

		}else {
			descRow.show().removeClass('pitch-collapsed').addClass('pitch-open');
			parentRow.addClass('pitch-open');
			$('.pitches-name-td-img', $(this).parent().parent()).attr('src', '/img/arrow_grey_down.png')
		}
		return false;
	});

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
        });

    });

    // Apply filters from query string
    $(window).on('popstate', function() {
        var options = window.location.search.substr(1); 
        queryToSearchField(options);
        fetchOptions = $.deparam(options);
        fetchOptions.fromQuery = true;
        Table.fetchTable(fetchOptions); 
    });
    
    var Table = new TableLoader;
    Table.init();
});

/* Init placeholder */
var placeholder = 'НАЙТИ ПИТЧ ПО КЛЮЧЕВОМУ СЛОВУ ИЛИ ТИПУ';
function checkPlaceholder() {
    var el = $('#searchTerm');
    var value = el.val();
    if ($('#filterbox li').length == 0) {
        if ((el.val() == '') || (el.val() == placeholder)) {
            el.addClass('placeholder');
            var value = placeholder;
        }
    } else {
        if (el.val() == placeholder) {
            var value = '';
        }
        el.removeClass('placeholder');
    }
    el.val(value);
    checkFilterClear();
}

function checkFilterClear() {
    if ((($('#searchTerm').val() == '') || ($('#searchTerm').val() == placeholder)) && ($('#filterbox li').length == 0)) {
        $('#filterClear').fadeOut(50);
    } else {
        $('#filterClear').fadeIn(100);
    }
}

var recalculateBox = function () {
    var baseWidth = 524;
    $.each($('#filterbox').children(), function(index, object) {
        baseWidth -= $(object).width() + 50;
    });
    $('#searchTerm').width(baseWidth);
};

function queryToSearchField(options) {
    if ($('#filterClear').is(':visible')) {
        $.each($('.removeTag', '#filterbox'), function(index, object) {
            $(this).parent().remove();
        });
    }
    $('#searchTerm').val('');
    checkPlaceholder();
    recalculateBox();
    if (options.type && options.type != 'all') {
        var box = '<li style="margin-left:6px;" data-group="type">' + $('a[data-group=type][data-value=' + options.type + ']').text() + '<a class="removeTag" href="#" data-group="type" data-value="' + options.type + '"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>';
        $(box).appendTo('#filterbox');
    }
    if (options.category && options.category != 'all') {
        var box = '<li style="margin-left:6px;" data-group="category">' + $('a[data-group=category][data-value=' + options.category + ']').text() + '<a class="removeTag" href="#" data-group="category" data-value="' + options.category + '"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>';
        $(box).appendTo('#filterbox');
    }
    if (options.priceFilter && options.priceFilter != 'all') {
        var box = '<li style="margin-left:6px;" data-group="priceFilter">' + $('a[data-group=priceFilter][data-value=' + options.priceFilter + ']').text() + '<a class="removeTag" href="#" data-group="priceFilter" data-value="' + options.priceFilter + '"><img src="/img/delete-tag.png" alt="" style="padding-top: 4px;"></a></li>';
        $(box).appendTo('#filterbox');
    }
    if (options.searchTerm) {
        $('#searchTerm').val(options.searchTerm);
    }
    if (options.order) {
        $('#sort-' + Object.keys(options.order)[0]).attr('rel', (options.order[Object.keys(options.order)[0]] == 'asc') ? 'desc' : 'asc');
    }
    checkPlaceholder();
    recalculateBox();
}