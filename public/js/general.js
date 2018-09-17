$(function () {
    $("#mobile-menu-btn").click(function () {
        $("#left").toggleClass('mobile-show');
        $("#mobile-menu-btn").toggleClass('mobile-show');
    });
	
	// google analinics и yandex.metrika
	GAevents.init();
});

// Инициализация событий на странице конструктора 
GAevents = {
	
	init: function () {
		var $self = this;
		
		// табы
		$(window).on('hashchange', function() {
			var hash = window.location.hash;
			switch(hash){
				case "#device":
					$self.tabDevice();
					break;
				case "#casey":
					$self.tabCase();
					break;
				case "#bg":
					$self.tabBackround();
					break;
				case "#font":
					$self.tabText();
					break;
				case "#smile":
					$self.tabSmile();
					break;
			}
		});
		
		$("body").on('mousedown', '.item.cart', function (e) {
			$self.clickCartTab();
		});	
		
		$("body").on('click', '#cart-submit', function (e) {
			$self.clickCartSubmit();
		});		
		
		$("body").on('click', '.device.list-item', function (e) {
			var id_device = $(this).data("device");
			$self.clickChosenDevice(id_device);
		});
		
		$("body").on('click', '#make-order', function (e) {
			$self.clickButtonPurchase();			
		});
		
		$("body").on('click', '.case.list-item', function (e) {
			var model = $(this).data("case");
			$self.clickModelCase(model);
		});
		
		$("body").on('click', '.control-panel-case-color .list-item', function (e) {
			var id_color = $(this).data("color-id");
			$self.clickModelColor(id_color);			
		});
		
		$("body").on('click', '.helper-clear', function (e) {
			$self.clickClearTemplate();			
		});		
		
		$("body").on('click', '.control-panel-bg .r-button .link', function (e) {
			var id_category = $(this).parent().find("input").val();
			$self.clickChoiceCategory(id_category);
			
		});
		
		$("body").on('click', '.bg.list-item.link', function (e) {
			var version = $(this).data("bg");
			$self.clickChoiceDesign(version);
		});
		
		$("body").on('click', '#font-add', function (e) {
			$self.clickChoiceText();					
		});
		
		$("body").on('mousedown', '.control-panel-font .list-item', function (e) {
			var version = $(this).data("font");
			$self.clickChoiceTextRight(version);			
		});
		
		$("body").on('click', '.control-panel-smile .r-button', function (e) {
			var id_category = $(this).find("input").val();
			$self.clickChoiceCategorySmile(id_category);
		});
		
		$("body").on('click', '.smile.list-item.link', function (e) {
			var version = $(this).data("smile");
			$self.clickChoiceSmile(version);
		});
		
		$("body").on('click', '#cart .add-header', function (e) {
			$self.clickCreateCaseTop();
		});
		
		$("body").on('click', 'a:contains("+ Добавить чехол по акции")', function (e) {
			$self.clickAddCasePromo();
		});
		
		$("body").on('click', '#cart .cart-button.add-button', function (e) {
			$self.clickCreateCaseBottom();
		});
		
		$("body").on('click', '#promocode_ctl #promocode_button', function (e) {
			$self.clickApplyPromo();
		});
		
		$("body").on('click', '.left-link.calc a', function (e) {
			$self.clickCalc();
		});
		
		$("body").on('click', '.left-link.about_casey a', function (e) {
			$self.clickAboutCase();
		});
		
		$("body").on('click', '.left-link.in_insta a', function (e) {
			$self.clickInInsta();
		});
		
		$("body").on('click', '.logo_construct a', function (e) {
			$self.clickLogoConstructor();
		});
		
		$("body").on('focus', '#font-text', function (e) {
			$self.focusEnterText();
		});
		
		$("body").on('mousedown', '.icon-cross.pointer', function (e) {
			$self.delCaseConstructor($(this));
		});
	},

	// уделение чехла из конструктора
    delCaseConstructor: function (btn) {
        var block_device = btn.closest(".cart-device");
		var type_casey = block_device.find(".case-description").html();
        var device = block_device.find(".case-name").eq(1).html();
        var price = block_device.find(".case-cost-value").text();
		var deviceCasey = block_device.find(".case-name").eq(0).html();
		
		var type_bg = "bg no found";
		if(block_device.find("image.constructor-bg").length > 0){
			var href_bg = $("image.constructor-bg").attr("href");
			type_bg = href_bg.split('/')[href_bg.split('/').length-1];
			type_bg = type_bg.replace(".png", "");
		}
		
		
		dataLayer.push({
			"ecommerce": {
				"remove": {
					"products": [
						{
							"name": type_bg,
							"category": device,
							"quantity": 1,
							"variant": deviceCasey,
							"price": price,
						}
					]
				}
			}
		});
		
		/*dataLayer.push({
			"ecommerce": {
				"remove": {
					"products": [
						{
							"name": type_bg,
							"category": type_casey,
							"quantity": 1
						}
					]
				}
			}
		});*/
		
		// console.log(type_bg, type_casey, device, price);
    },
	
	// когда появился фокус у поля для ввода текста
    focusEnterText: function () {
        yaCounter32242774.reachGoal('WRITE_TEXT');
    },
	
	// клик по логотипу в конструкторе
    clickLogoConstructor: function () {
        yaCounter32242774.reachGoal('LOGOCONSTRUCTOR');
		ga('send', 'event', 'Click', 'logo');
    },
	
	// клик по ссылке "мы в инстаграм"
    clickInInsta: function () {
        yaCounter32242774.reachGoal('OURINSTAGRAM');
    },
	
	// клик по ссылке "о чехлах"
    clickAboutCase: function () {
        yaCounter32242774.reachGoal('CASEABOUT');
    },
	
	// клик по калькулятору доставки
    clickCalc: function () {
        yaCounter32242774.reachGoal('DELIVERYCALC');
    },
	
	// переход в корзину конструктора
    clickCartTab: function () {
        yaCounter32242774.reachGoal('CARTBUTTION');
    },
	
	// клик по кнопке "Оформить/Оплатить" в конструкторе
    clickCartSubmit: function () {
        yaCounter32242774.reachGoal('CHECKOUT');
    },
	
	// Активация таба “девайс”
    tabDevice: function () {
        this.send("constructor", "device", "initiliaze");
    },
	
	// Активация таба “чехол”
    tabCase: function () {
        this.send("constructor", "case", "initiliaze");
    },
	
	// Активация таба “фон”
    tabBackround: function () {
        this.send("constructor", "backround", "initiliaze");
    },
	
	// Активация таба “текст”
    tabText: function () {
        this.send("constructor", "text", "initiliaze");
		yaCounter32242774.reachGoal('CHOOSEFONTSERVICE');
    },
	
	// Активация таба "смайлик" 
    tabSmile: function () {
        this.send("constructor", "smile", "initiliaze");
		yaCounter32242774.reachGoal('ADDSMILE');
    },
	
	// Клик по блоку “выберите устройство”
    clickChosenDevice: function (id_device) {
        this.send("constructor", "chosen_device", id_device);
		yaCounter32242774.reachGoal('CHOOSEPHONE');
    },
	
	// Клик по кнопке “Купить”
    clickButtonPurchase: function () {
        this.send("constructor", "button_purchase", "");
		yaCounter32242774.reachGoal('BUY');
		
		// яндекс коммерция. добавить в корзину
		var href_d = $("image.constructor-device").attr("href");
		var device = href_d.split('/')[href_d.split('/').length-2];
		
		var href_c = $("image.constructor-casey").attr("href");
		var type_casey = href_c.split('/')[href_c.split('/').length-1];
		type_casey = type_casey.replace(".png", "");
		
		var type_bg = "bg no found";
		if($("image.constructor-bg").length > 0){
			var href_bg = $("image.constructor-bg").attr("href");
			type_bg = href_bg.split('/')[href_bg.split('/').length-1];
			type_bg = type_bg.replace(".png", "");
		}
		var price = $("#make-order").find(".icon-cart").data("cost");
		dataLayer.push({
			"ecommerce": {
				"add": {
					"products": [
						{
							"name": type_bg,
							"price": price,
							"category": DEVICE.value.device,
							"variant": DEVICE.value.casey
						}
					]
				}
			}
		});

		console.log(type_bg, price, type_casey, device);
    },
	
	// Клик по модели чехла
    clickModelCase: function (version) {
        this.send("constructor", "case", "chosen_" + version);
		yaCounter32242774.reachGoal('CHOOSECASE');
    },
		
	// Клик по цвету
    clickModelColor: function (version) {
        this.send("constructor", "case", "chosen_" + version);
		yaCounter32242774.reachGoal('CHOOSECOLORDEVICE');
    },
	
	// Очистить макет
    clickClearTemplate: function () {
        this.send("constructor", "clear", "");
		yaCounter32242774.reachGoal('CLEARMAKET');
    },
	
	// Выбрать категорию
    clickChoiceCategory: function (id_category) {
        this.send("constructor", "backround", "click_" + id_category);
		yaCounter32242774.reachGoal('CHOOSECOLLECTION');
    },
	
	// Выбрать вариант дизайна
    clickChoiceDesign: function (version) {
        this.send("constructor", "backround", "click_" + version);
		yaCounter32242774.reachGoal('BGCLICK');
    },
	
	// Добавить свой текст
    clickChoiceText: function () {
        this.send("constructor", "text", "click own text");
		yaCounter32242774.reachGoal('ADDMORETEXT');	
    },
	
	// Выбрать текст справа
    clickChoiceTextRight: function (version) {
        this.send("constructor", "text", "click_" + version);
		yaCounter32242774.reachGoal('CHOOSETYPEOFFONT');
    },
	
	// Выбрать категорию
    clickChoiceCategorySmile: function (id_category) {
        this.send("constructor", "smile", "click_" + id_category);
    },
	
	// Выбрать смайлик
    clickChoiceSmile: function (version) {
        this.send("constructor", "smile", "click_" + version);
    },
	
	// Создать чехол - кнопка сверху
    clickCreateCaseTop: function () {
        this.send("constructor", "checkout", "create_top");
		yaCounter32242774.reachGoal('ADDCASE_TOP_BUTTION');
    },
	
	// Добавить чехол по акции
    clickAddCasePromo: function () {
        this.send("constructor", "checkout", "promo add case");
    },
	
	// Создать чехол снизу
    clickCreateCaseBottom: function () {
        this.send("constructor", "checkout", "create_bottom");
		yaCounter32242774.reachGoal('ADDCASE_CART_BOTTOM');
    },
	
	// Применить промокод
    clickApplyPromo: function () {
        this.send("constructor", "checkout", "apply promo");
    },
	
	
    send: function (category, action, label){
        dataLayer.push({
			'event': 'constructor',
			'eventCategory': category, 
			'eventAction': action, 
			'eventLabel': label 
		});
    },
	
};