/**
 * Ajax setup
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

var current_city_id;
var sdecPointsMap;
var showRoomMap;
var ymap_requested = false;
var sdec_points;
var available_methods = {
    sdec_points: false,
    courier_sdec: false,
    russian_post: true,
    msk: false,
    all_checked: false,
    sdec_points_checked: false,
    courier_sdec_checked: false
};

$(document).ready(function () {
    //$('select').selectpicker(); //хз что это
    $(".loader_spin").hide();
    //$("div.delivery-description").hide();
    $(".city-input").show();
    show_zaglushki();
});


Date.prototype.getMonthName = function () {
    var month = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня',
        'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];
    return month[this.getMonth()];
}

/* Когда выбрали город вверху формы, показываем доступные способы доставки и оплаты */

$(document).ready(function () {

    if ($("input.city-input").val() != "" && $("input.city-input").val() != null) {
        displayAvailableDelivery();
    }
});

$(document).on('keydown', 'input.city-input', function (event) {
    if (event.keyCode == 13 || event.keyCode == 9) {
        displayAvailableDelivery();
    }
});
$(document).on('click', 'div.suggestion-element', function () {
    $(".city-input").val($(this).text());
    displayAvailableDelivery();
});
$(document).on('focusout', 'input.city-input', function () {
//    displayAvailableDelivery();
});
/*--------------------------------------------------------------------------------------------------*/

function displayAvailableDelivery() {
    available_methods.all_checked = false;
    available_methods.sdec_points_checked = false;
    available_methods.courier_sdec_checked = false;
    var city_name = $('input.city-input').val();
    console.log("введен город " + city_name);
    if (city_name == 'Москва' || city_name == 'москва') {
        $('input.city-input').removeClass('error');
        removeSuggestionElements();
        available_methods.msk = true;
        show_delivery_methods_for_Moscow();

    } else {
        available_methods.msk = false;
        console.log("будем проверять город");
        //Проверить правильность введенного города
        $.ajax({
            type: "POST",
            url: "/cdek/cities",
            dataType: 'text',
            data: {
                mask: city_name + '%'
            },
            success: function (data) {
                var result = JSON.parse(data);
                result = result.data;

                var city_exists = false;
                for (var iter = 0; iter < result.length; iter++) {
                    if (result[iter].city_name == city_name) {
                        city_exists = true;
                    }
                }

                if (city_exists) {
                    $('input.city-input').removeClass('error');
                    removeSuggestionElements();
                    show_delivery_methods_for_not_Moscow();
                }
                else {
                    $('input.city-input').addClass('error');
                    show_zaglushki();
                }
            },
            fail: function (data) {
                console.log("ERROR: AJAX request to check city exists FAILED");
            }
        });
    }
}


$(document).on('input', '.city-input', function () {

    if ($(this).val().length < 3) {
        return;
    }

    $(".select-city__main-input__sdec").val($(this).val());

    $(".suggestion-list").find("span").remove();
    $.ajax({
        type: "POST",
        url: "/cdek/cities",
        dataType: 'text',
        data: {
            mask: $(".city-input").val() + '%'
        },
        success: function (data) {
            if (data != false) {
                var result = JSON.parse(data);
                result = result.data;
                console.log(result);
                $('div.suggestion-element').remove();
                for (var i = 0; i < result.length; i++) {
                    $(".suggestions-list").append("<div class='suggestion-element'><span class='city-suggestion'>" + result[i].city_name + "</span></div>");
                }
            }
        },
        fail: function (data) {

        }
    });
});


function GetCostAndDateUsingSDEKGeoNameID(params) {

    $.ajax({
        type: "POST",
        url: "/cdek/cities/" + params["city_id"] + "/cost",
        dataType: 'text',
        success: function (data) {
            console.log(data);
            if (data != false) {
                var result = JSON.parse(data);

                if (!result[137]) {
                    //hideSdecCourierDeliveryOption();
                    console.log("Нет доставки CDEK в выбранный город");
                    available_methods.courier_sdec = false;
                    available_methods.courier_sdec_checked = true;
                    changeActiveMethod(available_methods);
                }
                else {
                    setSdecPriceAndTimeToTheDivs(result);
                    available_methods.courier_sdec = true;
                    available_methods.courier_sdec_checked = true;
                    changeActiveMethod(available_methods);
                }
            }
            else {
                console.log("AJAX request to SDEC API returned an empty array\n");
                available_methods.courier_sdec = false;
                vailable_methods.courier_sdec_checked = true;
                changeActiveMethod(available_methods);
                return false;
            }
        },
        fail: function (data) {
            console.log("AJAX request to SDEC API failed\n");
            available_methods.courier_sdec = false;
            vailable_methods.courier_sdec_checked = true;
            changeActiveMethod(available_methods);
            return false;
        }
    });
}


//TODO: Решить что делать, когда 2 города с абсолютно одинаковыми названиями
//Посмотреть в документации как конкретизировать регион
function GetSDEKCourierDeliveryCostAndDate(city_name) {

    var req = $.ajax({
        url: "http://api.cdek.ru/city/getListByTerm/jsonp.php?callback=?",
        dataType: "jsonp",
        data: {
            q: function () {
                return city_name;
            },
            name_startsWith: function () {
                return city_name;
            }
        },
        success: function (data) {

            if (data.geonames == null || data.geonames == undefined) {
                //hideSdecCourierDeliveryOption();
                available_methods.courier_sdec = false;
                available_methods.sdec_points = false;
                changeActiveMethod(available_methods);
            }
            else {
                if (data.geonames.length == 0) {
                    //hideSdecCourierDeliveryOption();
                    available_methods.courier_sdec = false;
                    available_methods.sdec_points = false;
                    changeActiveMethod(available_methods);
                }
                else {
                    for (var i = 0; i < data.geonames.length; i++) {
                        if (data.geonames[i].cityName.toLowerCase() == city_name.toLowerCase()) {
                            GetCostAndDateUsingSDEKGeoNameID({"city_id": data.geonames[i].id});
                            getPointsSdec(data.geonames[i].id);
                            break;
                        }
                    }
                }
            }
        },
        fail: function (data) {
            console.log("AJAX request to SDEC API failed\n");
            //hideSdecCourierDeliveryOption();
            available_methods.courier_sdec = false;
            available_methods.sdec_points = false;
            changeActiveMethod(available_methods);
        }
    });

}

/*
 var sdecPointsMap;
 var showRoomMap;

 // Дождёмся загрузки API и готовности DOM.
 ymaps.ready(init);
 */
function init() {
    // Создание экземпляра карты и его привязка к контейнеру с
    // заданным id ("map").
    sdecPointsMap = new ymaps.Map('map', {
        // При инициализации карты обязательно нужно указать
        // её центр и коэффициент масштабирования.
        center: [55.76, 37.64], // Москва
        zoom: 10,
    }, {});
    sdecPointsMap.controls.add(
        new ymaps.control.ZoomControl()
    );

    var showroom_coord_x = 55.740031;
    var showroom_coord_y = 37.66176;

    showRoomMap = new ymaps.Map('map-showroom',
        {
            center: [showroom_coord_x, showroom_coord_y],
            zoom: 17
        },
        {}
    );


    var adress = 'Москва, м. Таганская ул. Таганская д. 24 стр. 1';
    var worktime = "С 10:00 до 22:00";
    var phone = "8 (800) 555 35 35";

    var showroom_info = "<table><tbody>";
    showroom_info += "<tr><td>Адрес</td><td>" + adress + "</td></tr>";
    showroom_info += "<tr><td>Время работы</td><td>" + worktime + "</td></tr>";
    showroom_info += "<tr><td>Телефон</td><td>" + phone + "</td></tr>";
    showroom_info += "</tbody></table>";

    var showroom = new ymaps.Placemark(
        [showroom_coord_x, showroom_coord_y],
        {
            balloonContentHeader: "Шоурум OhCasey",
            balloonContent: showroom_info
        }
    );
    showRoomMap.geoObjects.add(showroom);
    console.log("убираем спин с карты шоурума");
}


function getPointsSdec(idcity) {
    $.ajax({
        type: "POST",
        url: "/cdek/cities/" + idcity + "/pvz",
        success: function (data) {
            sdec_points = data;

            if (sdec_points.length == 0) {
                console.log("В этом городе нет пунктов самовывоза");
                //hideSdecPointsDeliveryOption();
                available_methods.sdec_points = false;
                available_methods.sdec_points_checked = true;
                changeActiveMethod(available_methods);
            }
            else {
                $('.sdec-point-list .spin').hide();

                if (sdec_points.length < 1) {
                    $('div.sdec-point-list').val('В выбранном населенном пункте нет пунктов самовывоза');
                    $('.map-row').hide();
                    console.log("В выбранном городе нет пунктов самовывоза");
                    //hideSdecPointsDeliveryOption();
                    available_methods.sdec_points = false;
                    available_methods.sdec_points_checked = true;
                    changeActiveMethod(available_methods);
                }
                else {
                    console.log("SDEC POINTS!!!");
                    console.log(sdec_points);
                    available_methods.sdec_points = true;
                    available_methods.sdec_points_checked = true;
                    changeActiveMethod(available_methods);
                    //$("div.sdec-point").show();
                    $('div.sdec-point-list ul').empty();
                    if (sdec_points.length > 1) {
                        for (var i = 0; i < sdec_points.length; i++) {
                            $('div.sdec-point-list ul').append('<li><b>' + ucfirst(sdec_points[i].name) + "</b> - " + ucfirst(sdec_points[i].address.toLowerCase()) + '</li>');
                        }
                    }
                    else {
                        $('div.sdec-point-list ul').append('<li><b>' + ucfirst(sdec_points[0].name) + "</b> - " + ucfirst(sdec_points[0].address.toLowerCase()) + '</li>');
                    }

                    ymaps.ready(function () {
                        myGeoObjects = [];
                        sdecPointsMap.geoObjects.removeAll();
                        console.log("карта готова. убираем spin с карты сдэк");

                        for (var i = 0; i < sdec_points.length; i++) {

                            var table = '<table><tbody>';
                            table += '<tr><td>Адрес:</td><td>' + ucfirst(sdec_points[i].address.toLowerCase()) + '</td><tr>';
                            if ((sdec_points[i].work_time != undefined) && (sdec_points[i].work_time != "")) {
                                table += '<tr><td>Рабочее время</td><td>' + ucfirst(sdec_points[i].work_time.toLowerCase()) + '</td><tr>';
                            }
                            if ((sdec_points[i].phone != undefined) && (sdec_points[i].phone != "")) {
                                table += '<tr><td>Телефон</td><td>' + sdec_points[i].phone + '</td><tr>';
                            }
                            if ((sdec_points[i].note != undefined) && (sdec_points[i].note != "")) {
                                table += '<tr><td>Примечание</td><td>' + sdec_points[i].note + '</td><tr>';
                            }
                            table += '</tbody></table>';

                            var object = new ymaps.Placemark(
                                [sdec_points[i].coordy, sdec_points[i].coordx], {
                                    balloonContentHeader: sdec_points[i].name,
                                    balloonContent: table
                                }
                            );
                            myGeoObjects.push(object);
                        }

                        clusterer = new ymaps.Clusterer();
                        clusterer.add(myGeoObjects);
                        sdecPointsMap.geoObjects.add(clusterer);
                        sdecPointsMap.setBounds(clusterer.getBounds(), {checkZoomRange: true});

                    });
                }
            }


        }
    });
}

function show_delivery_methods_for_Moscow() {
    $(".zaglushki").hide();
    $(".not-moscow").hide();
    $(".moscow").show();
    $(".delivery-method").removeClass("active");
    //$(".moscow .delivery-method:first").addClass("active");
    $("div.delivery-description").hide();
    //$("div.delivery-description[delivery-description-to="+$(".moscow .delivery-method:first").attr("delivery-name")+"]").show();

    $('div.map-row').hide();
    $('div.sdec-point-list').hide();
    $("div.map-row-showroom").hide();

    GetSDEKCourierDeliveryCostAndDate($("input.city-input").val());

    add_yndex_map();
    /*
     if($(".moscow .delivery-method:first").attr("delivery-name")=="sdec-point"){
     console.log("Москва: выбран самовыоз СДЭК");
     $("div.map-row").show();
     $('div.sdec-point-list').show();

     }

     if($(".moscow .delivery-method:first").attr("delivery-name")=="showroom"){
     console.log("Москва: выбран шоурум");
     $("div.map-row-showroom").show();
     }
     */

}


// TODO
// Проверить работоспособность отрисовки доставки курьером СДЭК
// Доделать отрисовку цены, даты и списка пунктов выдачи для самовывоза из пункта СДЭК
//
function show_delivery_methods_for_not_Moscow() {
    $(".zaglushki").hide();
    $(".moscow").hide();
    $(".not-moscow").show();
    if (available_methods.all_checked == false) {
        $(".not-moscow").addClass("spin");
    }

    //яндекс-карта и ответ придут асинхронно, поэтому не дожидаясь ответа СДЭК подгружаем карту
    add_yndex_map();
    //запрос цены и срока доставка, запрос пунктов самовывоза
    available_methods.sdec_points = false;
    available_methods.courier_sdec = false;
    available_methods.russian_post = true;
    console.log("сброс для нового города ");
    changeActiveMethod(available_methods);
    GetSDEKCourierDeliveryCostAndDate($("input.city-input").val());
}

function add_yndex_map() {
    // Скачиваем файл js яндекс-карт
    // Дождёмся загрузки API и готовности DOM.
    if (ymap_requested == false) {
        ymap_requested = true;
        console.log("скачиваем js яндекс-карт");
        $.getScript("//api-maps.yandex.ru/2.1/?lang=ru_RU", function (data, textStatus, jqxhr) {
            console.log(data); // Data returned
            console.log(textStatus); // Success
            console.log(jqxhr.status); // 200
            console.log("Load was performed.");
            // после того как яндекс-карты скачали себя, добавляем в верстку 2 карты (шоурума с точкой шоурума и пустую карту москвы для точек СДЭК)
            ymaps.ready(init);
        });
    }
}

function show_zaglushki() {
    $(".moscow").hide();
    $(".not-moscow").hide();
    $(".zaglushki").show();
    $('div.map-row').hide();
    $("div.map-row-showroom").hide();
    $('div.sdec-point-list').hide();
    $("div.delivery-description").hide();
    $('div.delivery-description[delivery-description-to="zaglushki-description"]').show();

}

function setSdecPriceAndTimeToTheDivs(sdec_delivery_data) {
    // Courier
    $("div.courier_sdec").show();
    $("div.courier_sdec span.cost").text(sdec_delivery_data[137].price + " p.");
    var date_start = new Date(Date.parse(sdec_delivery_data[137].deliveryDateMax));
    $("div.courier_sdec span.text").html("<span class='glyphicon glyphicon-time'></span> с <strong>" + date_start.getDate() + "</strong> " + date_start.getMonthName());

    // Pickpoint
    if (sdec_delivery_data[136]) {
        $("div.sdec-point").show();
        $("div.sdec-point span.cost").text(sdec_delivery_data[136].price + " p.");
        var date_start = new Date(Date.parse(sdec_delivery_data[136].deliveryDateMax));
        $("div.sdec-point span.text").html("<span class='glyphicon glyphicon-time'></span> с <strong>" + date_start.getDate() + "</strong> " + date_start.getMonthName());
    }
}

function hideSdecCourierDeliveryOption() {
    $("div.courier_sdec").hide();
}
function hideSdecPointsDeliveryOption() {
    $("div.sdec-point").hide();
    $(".sdec-point-list").hide();
    $(".map-row").hide();
}

function removeSuggestionElements() {
    $("div.suggestion-element").remove();
}


function changeActiveMethod(available_methods) {

    console.log("самовывоз сдэк: " + available_methods['sdec_points'] + "; доставка СДЭК: " + available_methods['courier_sdec'] + "; почта россии: " + available_methods['russian_post'] + "Москва: " + available_methods.msk);
    if (available_methods.msk != true) {
        $("div.delivery-description").hide();
        $('div.map-row').hide();
        $('div.sdec-point-list').hide();
        // Сброс выбранного способа, т.к. из-за асинхронного ответа СДЭК о дате-стоимости и о пунктах самовывоза, сначала может вернуться курьер СДЭК, а потом самовывоз СДЭК. поэтому при при каждом ответе. сначала сбрасываем интерфейс
        $(".not-moscow .delivery-method").removeClass("active");
        // Показываем в шапке доступные методы доставки
        $(".not-moscow .item_block").hide();

        if ((available_methods.sdec_points_checked == true) && (available_methods.courier_sdec_checked == true)) {
            available_methods.all_checked = true;
            $(".not-moscow").removeClass("spin");
            if (available_methods.sdec_points == true) {
                $(".not-moscow .sdec-point").show();
            }
            if (available_methods.courier_sdec == true) {
                $(".not-moscow .courier_sdec").show();
            }
            if (available_methods.russian_post == true) {
                $(".not-moscow .russian_post").show();
            }
            // Почта России по-умолчанию из верстки показана


            $("div.map-row-showroom").hide();
            /*
             // Исходя из доступных, определяем какокй методо доставки активен и раскрыт по-умолчанию: самовывоз СДЭК, а если он недоступен, то курьер СДЭК, а если он недоступен, то почта России
             if (available_methods.sdec_points == true) {
             $(".not-moscow .sdec-point .delivery-method").addClass("active");
             //и без пользовательского клика показываем описание способа доставки СДЭК, карту и список точек самовывоза
             $("div.delivery-description[delivery-description-to="+$(".not-moscow .sdec-point .delivery-method").attr("delivery-name")+"]").show();
             $("div.map-row").show();
             $('div.sdec-point-list').show();
             }
             else {
             if (available_methods.courier_sdec == true) {
             $(".not-moscow .courier_sdec .delivery-method").addClass("active");
             $("div.delivery-description[delivery-description-to="+$(".not-moscow .courier_sdec .delivery-method").attr("delivery-name")+"]").show();
             }
             else {
             $(".not-moscow .russian_post .delivery-method").addClass("active");
             $("div.delivery-description[delivery-description-to="+$(".not-moscow .russian_post .delivery-method").attr("delivery-name")+"]").show();
             }
             }
             */

        }
    }

}

$(".delivery-method").on("click", function () {
    $(".delivery-method").removeClass("active");
    $(this).addClass("active");
    $("div.delivery-description").hide();
    $("div.delivery-description[delivery-description-to=" + $(this).attr("delivery-name") + "]").show();

    $('div.map-row').hide();
    $('div.sdec-point-list').hide();
    $("div.map-row-showroom").hide();

    if ($(this).attr("delivery-name") == "sdec-point") {
        $('div.map-row').show();
        $('div.sdec-point-list').show();
    }
    if ($(this).attr('delivery-name') == "showroom") {
        $("div.map-row-showroom").show();
    }
    $('body').scrollTo("div.delivery-description[delivery-description-to=" + $(this).attr("delivery-name") + "]", {
        duration: 'slow',
        offsetTop: '50'
    });
});


function dump(obj) {
    var out = "";
    if (obj && typeof(obj) == "object") {
        for (var i in obj) {
            out += i + ": " + obj[i] + "\n";
        }
    } else {
        out = obj;
    }
    console.log(out);
}

function ucfirst(str) {
    var first = str.charAt(0).toUpperCase();
    return first + str.substr(1);
}
