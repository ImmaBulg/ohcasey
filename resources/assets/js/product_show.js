import Vue from 'vue'
import VueRouter from 'vue-router'
import axios from 'axios'

Vue.use(VueRouter);

const routes = [
  { path: '/', component: app }
];

const router = new VueRouter({
  hashbang: false,
  mode: 'history',
  base: window.location.pathname,
  routes
});

Vue.component('modal', {
  template: '#modal-template',
  props: ['cart'],
  mounted() {
    $('.js-scroll-popup').mCustomScrollbar({
        axis: 'y',
        scrollInertia: 400
    });
  }
});

var app = new Vue({
    el: '#app',
    router,
    data: {
        select2: null,
        // data matrix related options from server
        raw: offers,
        // rebuilded options to display
        options: {},
        // exclude option from rebuild (always display any variants)
        exclude: 'device',
        // current selected option, others options will be rebuild
        selected: {},
        // offer on selected values
        offer: {},
        // product photos
        photos: { offer: null, product: []},
        cart: [],
        cartCount: document.getElementById('cart__count'),
        // json data for old cart request
        json: null,
        // error - if old options values mapping false or if offer not found
        optionsError: false,
        sliderIsInit: false,
        // Modal window toggle flag
        showModal: false,
        headline: null,
        caseType: null
    },
    mounted(){
        // select2
        this.select2init()
        // init options keys
        options.forEach(o => Vue.set(this.options, o.key, {name: o.name, values: []}))
        // fill only exluded option values
        this.fillValues({exclude: true})
        // set default selected only exluded value
        Vue.set(this.selected, this.exclude, this.options[this.exclude].values[0].id)
        // fill product extra photos
        this.photos.product = product_photos
        this.rebuildOptions()

        setTimeout(() => {
            if (this.$route.query.device) {
                this.options.device.values.forEach((item, index) => {
                    if (item.value == this.$route.query.device) {
                        this.select2.val(parseInt(item.id)).trigger('change');
                    }
                });
            }

            if (this.$route.query.color) {
                if (this.options.color.values[this.$route.query.color])
                    this.selectOption('color', this.options.color.values[this.$route.query.color].id);
            }

            if (this.$route.query.case) {
                this.options.case.values.forEach((item, index) => {
                    if (item.value == this.$route.query.case) {
                        this.selectOption('case', this.options.case.values[index].id);
                    }
                });
            }
        }, 0);
    },
    updated(){
        if(!this.sliderIsInit) {
            this.sliderIsInit = true
            this.sliderInit()
        } else {
            this.sliderReinit()
        }
    },
    methods:{
        resetOffer(){
            this.offer = {}
            // this.photos.offer = null
            this.json = null
            this.optionsError = false
        },
        defaultSelected(){
            for(let o in this.options){
                // set default selected values as first array value
                if(!this.selected[o]){
                    Vue.set(this.selected, o, this.options[o].values[0].id)
                }
            }
        },
        rebuildOptions(){
            this.resetOffer()

            // get rows only with selected option, options in this rows must be filled
            let rows = this.raw.filter(row => {
                for(var id in row.option_values){
                    if(this.selected[this.exclude] === row.option_values[id].id) {
                        return true
                    }
                }
            })
            this.fillValues({rows: rows})
            this.defaultSelected()
            this.findOffer()
        },
        fillValues(s){
            let settings = s || {}
            let rows = settings.rows || this.raw
            let exclude = settings.exclude || false
            // clear old options values except excluded
            for(let o in this.options){
                if(o != this.exclude){
                    this.options[o].values = []
                }
            }
            for(let r in rows){
                for(let v in rows[r].option_values){
                    let key = options.filter(o => {return o.id == rows[r].option_values[v].option_id})[0].key
                    let value = rows[r].option_values[v]
                    // check if this value already exists in options array
                    let exists = this.options[key].values.filter(v => {
                        return v.id === value.id
                    })
                    // if not exists - add
                    if(exists.length == 0) {
                        if(exclude && key == this.exclude)
                            this.options[key].values.push(value)
                        if(!exclude)
                            this.options[key].values.push(value)
                    }
                }
            }
            // sort options by order
            for(let i in this.options){
                let s = this.sortByKey(this.options[i].values, 'order')
                this.options[i].values = s
            }
        },
        select2init(){
            window.select2 = this.select2 = $('.js-select-item')
            if (this.select2.length) {
                this.select2.select2({
                    dropdownCssClass: 'drop-item',
                    minimumResultsForSearch: -1
                })
                .on('change', () => {
                    var value = parseInt(this.select2.val());

                    this.selectOption(this.exclude, value);
                    this.changeHeadline('device', value);
                });
            }
        },
        sliderInit(){
            if ($('.js-slider-for').length) {
                $('.js-slider-for').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    fade: true,
                    asNavFor: '.js-slider-nav',
                    responsive: [
                        {
                            breakpoint: 700,
                            settings: {
                                dots: true,
                                fade: false
                            }
                        }
                    ]
                });

                $('.js-slider-nav').slick({
                    slidesToShow: 4,
                    slidesToScroll: 1,
                    asNavFor: '.js-slider-for',
                    dots: false,
                    arrows: false,
                    infinite: true,
                    centerMode: false,
                    variableWidth: true,
                    focusOnSelect: true
                });
            }
        },
        sliderReinit(){
            $('.js-slider-for').slick('unslick')
            $('.js-slider-nav').slick('unslick')
            this.sliderInit()
        },
        changeHeadline(key, value) {
            this.headline = this.headline || {};

            this.options[key].values.forEach((v) => {
                if (value === v.id) {
                    if (key === 'device') this.headline.device = v.title;
                    if (key === 'case') {
                        this.caseType = v.value;
                        this.headline.case = v.title;
                    }
                }
            });

            document.title = `Чехол для ${this.headline.device} «${window.product_name}» (материал ${ this.headline.case })  купить в интернет-магазине`;
        },
        selectOption(key, value){
            if(key == this.exclude) this.selected = {}
            Vue.set(this.selected, key, value)
            if(key == this.exclude) {
                this.rebuildOptions()
                return
            }
            if (key === 'case') this.changeHeadline('case', value);
            // find offer if all options selected
            if(Object.keys(this.selected).length == Object.keys(this.options).length) this.findOffer()
        },
        findOffer(){
            let offer = this.raw.filter(row => {
                let find = true
                for(v in row.option_values){
                    let key = options.filter(o => {return o.id == row.option_values[v].option_id})[0].key
                    find = find && (row.option_values[v].id == this.selected[key])
                }
                return find
            })[0]

            if(!offer) {
                this.resetOffer()
                this.optionsError = true
                return
            } else {
                this.offer = offer
                this.optionsError = false
            }

            let current_device_key = this.options.device.values.filter(device => {return device.id == this.selected['device']})[0].value

            // make json for old cart request
            var jsondata = {}
            for(var o in options){
                let key = options[o].key
                let value = this.options[key].values.filter(v => {return v.id == this.selected[key]})[0].value
                if(oldvalues[key]){
                    let oldFindValue = null
                    for(var v in oldvalues[key]){
                        if(key == 'color'){
                            if(oldvalues[key][current_device_key].length == 0) break
                            var color_index = oldvalues[key][current_device_key].indexOf(value)
                            if(color_index != -1) {
                                oldFindValue = color_index
                                break
                            }
                        } else {
                            if(oldvalues[key][v] == value) {
                                oldFindValue = oldvalues[key][v]
                                break
                            }
                        }
                    }
                    if(oldFindValue != null) {
                        jsondata[key] = oldFindValue
                    } else {
                        this.optionsError = true
                        return false
                    }
                }
            }
            this.json = {
                DEVICE:{
                    device: jsondata.device || '',
                    color: parseInt(jsondata.color) === jsondata.color ? jsondata.color : '',
                    casey: jsondata.case || '',
                    bg: bgName || '',
                    mask: []
                }
            }

            // update cuurent offer photo
            this.photos.offer = lroutes.api.product.image + '?bgName=' + bgName + '&deviceName=' + current_device_key + '&deviceColorIndex=' + jsondata.color + '&caseFileName=' + jsondata.case
        },
        addToCart(){
            ga('send', 'event', 'Click', 'Add2basketCart');

            if(this.offer.id && this.json) {
                this.cart.push(this.offer)
                let current_count = parseInt(this.cartCount.innerHTML) || 0
                this.cartCount.innerHTML = current_count + 1
                axios.post('/cart/put/case/1', {current: this.json, offer_id: this.offer.id}).then(response => {
                    var requestConfig = {
                        headers: {
                            "Accept": "application/json",
                            "X-Requested-With": "XMLHttpRequest"
                        }
                    };
                    this.json = null
                    axios.get('/cart', requestConfig).then(response => {
                        this.cart = [];

                        if (response.data.data.cartSetCases.length) {
                            this.cart = this.cart.concat(response.data.data.cartSetCases);
                        }
                        if (response.data.data.cartSetProducts.length) {
                            this.cart = this.cart.concat(response.data.data.cartSetProducts);
                        }

                        this.toggleModal();
                    }).catch(response => {
                        console.error(error)
                        this.json = null
                    });
                }).catch(error => {
                    console.error(error)
                    this.json = null
                })
            }
        },
        toggleModal() {
            this.showModal = true;
            $('body').addClass('is-nav');
            $('.js-scroll-popup').mCustomScrollbar({
                axis: 'y',
                scrollInertia: 400
            });
        },
        sortByKey(array, key) {
            return array.sort(function(a, b) {
                var x = a[key]; var y = b[key];
                return ((x < y) ? -1 : ((x > y) ? 1 : 0));
            });
        }
    },
    computed:{
        inCart(){
            let productInCart = this.cart.filter(o => {
                return o.id == this.offer.id
            })[0]
            return !!productInCart
        },
        devicelist(){
            if(this.options && this.options.device && this.options.device.values)
                return this.options.device.values
            else return []
        },
        colorlist(){
            if(this.options && this.options.color && this.options.color.values)
                return this.options.color.values
            else return []
        },
        caselist(){
            if(this.options && this.options.case && this.options.case.values)
                return this.options.case.values
            else return []
        }
    }
})