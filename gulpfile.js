var elixir = require('laravel-elixir');

elixir.config.sourcemaps = false;
config.css.autoprefix.options.browsers =  ['last 15 versions'];
elixir(function (mix) {
    mix

        // scss
        .sass(['site/app.scss', '../icons/style.css'])
        .sass('site/main.scss')
        .sass('site/cart.scss')
        .sass('site/about.scss')
        .sass('site/payment.scss')
        .sass('site/delivery.scss')
        .sass('admin/admin.scss')

        // fonts
        .copy('resources/assets/icons/fonts/*.*', 'public/css/fonts')
        .copy('resources/assets/fonts/*.*', 'public/fonts')
        .copy('node_modules/bootstrap/dist/fonts/*.*', 'public/fonts/bootstrap')
        .copy('node_modules/font-awesome/css/font-awesome.min.css', 'public/css')

        //css
        .copy('node_modules/bootstrap/dist/css/bootstrap.css', 'public/css')
        .copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/css')
        .copy('node_modules/select2/dist/css/select2.css', 'public/css')
        .copy('node_modules/select2/dist/css/select2.min.css', 'public/css')
        .copy('node_modules/slick-carousel/slick/slick.css', 'public/css')
        .copy('node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css', 'public/css')
        .copy('node_modules/perfect-scrollbar/dist/css/perfect-scrollbar.css', 'public/css')
        .copy('node_modules/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css', 'public/css')
        .copy('node_modules/slick-carousel/slick/slick.css', 'public/css')
        .copy('node_modules/select2/dist/css/select2.min.css', 'public/css')

        .copy('resources/assets/css/*.*', 'public/css')

        // less
        .less('resources/assets/less/styles.less', 'public/css')

        // js
        .copy('node_modules/jquery/dist/jquery.js', 'public/js')
        .copy('node_modules/jquery/dist/jquery.min.js', 'public/js')
        .copy('node_modules/js-cookie/src/js.cookie.js', 'public/js')
        .copy('node_modules/bootstrap/dist/js/bootstrap.js', 'public/js')
        .copy('node_modules/bootstrap-confirmation2/bootstrap-confirmation.js', 'public/js')
        .copy('node_modules/select2/dist/js/select2.js', 'public/js')
        .copy('node_modules/select2/dist/js/select2.full.min.js', 'public/js')
        .copy('node_modules/select2/dist/js/i18n/ru.js', 'public/js/select2.ru.js')
        .copy('node_modules/slick-carousel/slick/slick.min.js', 'public/js/slick.min.js')
        .copy('node_modules/jquery-mask-plugin/dist/jquery.mask.min.js', 'public/js/jquery.mask.min.js')
        .copy('node_modules/slick-carousel/slick/slick.min.js', 'public/js')
        .copy('node_modules/jquery-mask-plugin/dist/jquery.mask.min.js', 'public/js')
        .copy('node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.js', 'public/js')
        .copy('node_modules/bootstrap-datepicker/dist/locales/bootstrap-datepicker.ru.min.js', 'public/js')
        .copy('node_modules/bootstrap-validator/dist/validator.js', 'public/js')
        .copy('node_modules/jquery-sticky/jquery.sticky.js', 'public/js')
        .copy('node_modules/store/store.js', 'public/js')
        .copy('node_modules/simple-ajax-uploader/SimpleAjaxUploader.js', 'public/js')
        .copy('node_modules/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js', 'public/js')
        .copy('node_modules/moment/min/moment.min.js', 'public/js')
        .copy('node_modules/vue/dist/vue.min.js', 'public/js')
        .copy('node_modules/axios/dist/axios.min.js', 'public/js')
        .copy('resources/assets/js', 'public/js');

        if (elixir.config.production) {
            mix.version([
                'css/app.css', 'css/cart.css', 'css/main.css', 'css/about.css', 'css/delivery.css', 'js/about.js',
                'js/delivery.js',
                'js/cart.js', 'js/common.js', 'js/constructor.js', 'js/helper.js', 'js/metrikaGoals.js',
                'js/help.js', 'js/admin-order-form.js', 'css/payment.css'
            ], 'public')
        }
});
