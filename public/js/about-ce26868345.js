$(document).ready(function () {
    $('select').selectpicker();

    $('.carousel').carousel({
        pause: "hover",
        interval: 3000
    });
    set_slider_height();
    $(".carousel-slider").addClass("loader_spin");
    $('.test').attr('src', 'img/loader-spin.gif').load(function () {
        $(this).remove(); // prevent memory leaks as @benweet suggested
        //$(".loader_spin").css('background-image', 'url(img/loader-spin.gif)');
    });
});

function set_slider_height() {
    var slider_height = $(".body_block.active .slider_cases").width() + "px";
    $(".slider_cases").css("height", slider_height);

    console.log(slider_height);
}

$(document).on("click", ".select_cases", function () {

    if ($(this).hasClass("active")) {
        $(".body_block").removeClass("active");
        $(".select_cases").removeClass("active");
        return;
    }

    var id_block = $(this).data("block");

    $(".body_block").removeClass("active");
    $(".body_block_" + id_block).addClass("active");

    $(".select_cases").removeClass("active");
    $(".select_cases[data-block=" + id_block + "]").addClass("active");

    set_slider_height();

    $(".body_block_" + id_block + " .carousel-inner").hide();
    $(".body_block_" + id_block + " .carousel .item").waitForImages({
        waitForAll: true,
        finished: function () {
            // Background image has loaded.
            $(".body_block_" + id_block + " .carousel-slider").removeClass("loader_spin");
            $(".body_block_" + id_block + " .carousel-inner").show();
        }

    });


});

$(window).resize(function () {
    set_slider_height();
});


