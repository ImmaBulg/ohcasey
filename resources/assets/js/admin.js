//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function () {
    $(window).bind("load resize", function () {
        var topOffset = 50;
        var width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        var height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location.protocol + (window.location.port ? ':' + window.location.port : '') + '//' + window.location.host + window.location.pathname;
    var element = $('ul.nav a').filter(function () {
        return this.href == url;
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }

    // Check new order
    setInterval(function() {
        $.get(BASE_URL + '/orders/last', function(response) {
            if (response.last != LAST_ORDER_ID) {
                var title = document.title.split(' | ').slice(-1)[0];
                title = 'НОВЫЙ ЗАКАЗ | ' + title;
                document.title = title;
                $('#admin-new-order').removeClass('hidden').html('Новых заказов: <strong>' + (response.last - LAST_ORDER_ID) + '</strong>')
            }
        });
    }, 60000);

    $('.status-colors').select2({
        minimumResultsForSearch: Infinity,
        templateSelection: function (status){
            if (!status.id) { return status.text; }
            return $(
                '<span style="color: ' + $(status.element).data('color') + '"><span class="fa fa-circle"></span> ' + status.text + '</span>'
            );
        },
        templateResult: function (status){
            if (!status.id) { return status.text; }
            return $(
                '<span style="color: ' + $(status.element).data('color') + '"><span class="fa fa-circle"></span> ' + status.text + '</span>'
            );
        }
    });

    $('[data-toggle=confirmation]').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        btnOkLabel: 'Да',
        btnOkIcon: 'fa fa-thumbs-o-up',
        btnCancelLabel: 'Нет',
        btnCancelIcon: 'fa fa-thumbs-o-down'
    });

    $('.bs-datepicker').datepicker();

    $('.js-payment-delete').click(function (e) {
        e.preventDefault();
        var $el = $(this);
        if (confirm('Вы уверены?')) {
            $.ajax({
                url: $el.attr('href'),
                type: 'GET',
                dataType: 'JSON',
                success: function (result) {
                    if (result.result == 'success') {
                        $el.closest('.js-payment-container').remove();
                    } else {
                        alert(result.error);
                    }
                }
            });
        }
        return false;
    });
});

/**
 * Get selected items
 * @returns {Array}
 */
function getSelected() {
    return $('.oh-img-list input:checked');
}

/**
 * Select all
 * @param v
 */
function selectAll(v) {
    $('.oh-img-list input').prop('checked', v);
    checkAll();
}

/**
 * Check all selected
 */
function checkAll() {
    var all = getSelected();
    $('#btn-edit, #btn-remove').attr('disabled', all.length == 0);
}

/**
 * Uniq array
 * @param array
 * @returns {*}
 */
function unique(array) {
    return $.grep(array, function(el, index) {
        return index === $.inArray(el, array);
    });
}

/*
 * metismenu - v1.1.3
 * Easy menu jQuery plugin for Twitter Bootstrap 3
 * https://github.com/onokumus/metisMenu
 *
 * Made by Osman Nuri Okumus
 * Under MIT License
 */
! function(a, b, c) {
	function d(b, c) {
		this.element = a(b), this.settings = a.extend({}, f, c), this._defaults = f, this._name = e, this.init()
	}
	var e = "metisMenu",
		f = {
			toggle: !0,
			doubleTapToGo: !1
		};
	d.prototype = {
		init: function() {
			var b = this.element,
				d = this.settings.toggle,
				f = this;
			this.isIE() <= 9 ? (b.find("li.active").has("ul").children("ul").collapse("show"), b.find("li").not(".active").has("ul").children("ul").collapse("hide")) : (b.find("li.active").has("ul").children("ul").addClass("collapse in"), b.find("li").not(".active").has("ul").children("ul").addClass("collapse")), f.settings.doubleTapToGo && b.find("li.active").has("ul").children("a").addClass("doubleTapToGo"), b.find("li").has("ul").children("a").on("click." + e, function(b) {
				return b.preventDefault(), f.settings.doubleTapToGo && f.doubleTapToGo(a(this)) && "#" !== a(this).attr("href") && "" !== a(this).attr("href") ? (b.stopPropagation(), void(c.location = a(this).attr("href"))) : (a(this).parent("li").toggleClass("active").children("ul").collapse("toggle"), void(d && a(this).parent("li").siblings().removeClass("active").children("ul.in").collapse("hide")))
			})
		},
		isIE: function() {
			for (var a, b = 3, d = c.createElement("div"), e = d.getElementsByTagName("i"); d.innerHTML = "<!--[if gt IE " + ++b + "]><i></i><![endif]-->", e[0];) return b > 4 ? b : a
		},
		doubleTapToGo: function(a) {
			var b = this.element;
			return a.hasClass("doubleTapToGo") ? (a.removeClass("doubleTapToGo"), !0) : a.parent().children("ul").length ? (b.find(".doubleTapToGo").removeClass("doubleTapToGo"), a.addClass("doubleTapToGo"), !1) : void 0
		},
		remove: function() {
			this.element.off("." + e), this.element.removeData(e)
		}
	}, a.fn[e] = function(b) {
		return this.each(function() {
			var c = a(this);
			c.data(e) && c.data(e).remove(), c.data(e, new d(this, b))
		}), this
	}
}(jQuery, window, document);
$(function() {

	$('#side-menu').metisMenu();

});

//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
	$(window).bind("load resize", function() {
		topOffset = 50;
		width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
		if (width < 768) {
			$('div.navbar-collapse').addClass('collapse');
			topOffset = 100; // 2-row-menu
		} else {
			$('div.navbar-collapse').removeClass('collapse');
		}

		height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
		height = height - topOffset;
		if (height < 1) height = 1;
		if (height > topOffset) {
			$("#page-wrapper").css("min-height", (height) + "px");
		}
	});

	var url = window.location;
	var element = $('ul.nav a').filter(function() {
		return this.href == url.pathname;
	}).addClass('active').parent().parent().addClass('in').parent();
	if (element.is('li')) {
		element.addClass('active');
	}
});

$(document).ready(function () {
    $('.js-generate-offers').click(function () {
        $('.sk-folding-cube').css('visibility', 'visible');
        $('.js-generate-offers')
            .prop('disabled', 'disabled')
            .html('Генерируем... Не закрывайте вкладку!');

        $.get('/admin/ecommerce/offer/generator/cases', function (products) {
            var i = 0, changesCount = 0;
            var generateOffers = function (i) {
                if (i < products.length) {
                    $.post('/api/admin/ecommerce/product/' + products[i].id + '/generate', function (data) {
                        var change = 0;
                        change = data.counts.offers_affter - data.counts.offers_before;
                        changesCount += change;

                        console.log(data);

                        $('.generate-log').val($('.generate-log').val() + 'ID#' + products[i].id + ' — сгенерировано: ' + change + ' новых предложений\n');
                        $('.current-generate-product').html(i + 1);
                        generateOffers(++i);
                    });
                } else {
                    $('.sk-folding-cube').css('visibility', 'hidden');
                    $('.js-generate-offers')
                        .removeAttr('disabled')
                        .html('Дополнить ТП на основе значений опций');

                    $('.generate-results').html('<b>Готово! Всего сгенерировано ' + changesCount + ' новых предложений.</b>');
                    return false;
                }
            };

            $('.generate-log').val('');
            $('.length-generate-product').html(products.length);
            $.post('/api/admin/ecommerce/product/' + products[i].id + '/generate', function (data) {
                console.log(data);
                generateOffers(++i);
            });
        });
    });
});
