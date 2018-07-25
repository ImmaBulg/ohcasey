import Vue from 'vue';
import VueRouter from 'vue-router'
import axios from 'axios';

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


const app = new Vue({
    router,
    data: {
      isLoaded: false,
      isCollection: undefined,
      isLoading: false,
      queryString: {},
      select2case: null,
      select2device: null,
      select2deviceModal: null,
      select2caseModal: null,
      data: [],
      selected: {},
      selectedCase: null,
      selectedDevice: null,
      selectedColor: null,
      selectedColorIndex: null,
      selectedPalette: [],
      devices: window.devices,
      cases: null,
      colors: window.colors,
      category: window.category,
      paging: {
        total: null,
        from: null,
        to: null,
        pages: null
      }
    },


    methods: {
      changeFilterParams(p, v) {
        var data = Object.assign({}, this.$route.query);

        data[p] = v;
        this.$router.push({ path : '/', query : data });
        this.queryString = data;

        if (this.isLoaded) this.getFilteredData();
      },


      getFilteredData() {
        this.isLoading = true;

        axios.get('/api/product?category_id=' + window.category.id, {
          params: this.queryString
        }).then(response => {
          this.isLoading = false;

          this.paging.pages = response.data.last_page;
          this.data = response.data.data;
        }).catch(error => {
          this.isLoading = false;

          console.log(error);
        })
      },


      setColorPalette() {
        this.selectedPalette = this.colors[this.selectedDevice];
      },


      selectColor(v, index) {
        console.log(v, index);
        this.selectedColor = v;
        this.changeFilterParams('color', index)
      },


      changePage(number) {
        this.changeFilterParams('page', number);
        window.scrollTo(0, 0);
      },


      initCaseSelect() {
        if ($('.js-select-item-case').length) {

          if (this.select2case) {
            this.select2case.select2('destroy');
            this.select2case = null;
          }

          this.select2case = $('.js-select-item-case')

          this.select2case.select2({
            dropdownCssClass: 'drop-item',
            minimumResultsForSearch: -1
          })

          .on('change', () => {
            setTimeout(() => {
              this.changeFilterParams('case', this.select2case.val());
            }, 1);
          })

          .on('select2:closing', function (evt) {
            $('.select2-results__options').mCustomScrollbar("destroy");
          })

          .on('select2:open', function (evt) {
            setTimeout(function() {
              $('.select2-results__options').mCustomScrollbar({
                  axis: 'y',
                  scrollInertia: 400
              });

              $(document).scroll();
            }, 1);
          });
        }
      },

      initCaseModalSelect() {
        if ($('.js-select-item-case-modal').length) {

          if (this.select2caseModal) {
            this.select2caseModal.select2('destroy');
            this.select2caseModal = null;
          }

          this.select2caseModal = $('.js-select-item-case-modal')

          this.select2caseModal.select2({
            dropdownCssClass: 'drop-item',
            minimumResultsForSearch: -1
          })

          .on('change', () => {
            this.selectedCase = this.select2caseModal.val();
          })

          .on('select2:closing', function (evt) {
            $('.select2-results__options').mCustomScrollbar("destroy");
          })

          .on('select2:open', function (evt) {
            setTimeout(function() {
              $('.select2-results__options').mCustomScrollbar({
                  axis: 'y',
                  scrollInertia: 400
              });

              $(document).scroll();
            }, 1);
          });
        }
      },


      select2init() {
        if ($('.js-select-item-device').length) {
          this.select2device = $('.js-select-item-device')
          this.select2device.select2({
            dropdownCssClass: 'drop-item',
            minimumResultsForSearch: -1
          })

          .on('change', () => {
            var queryParams = this.$route.query;

            this.selectedDevice = this.select2device.val();
            this.changeFilterParams('device', this.selectedDevice);
            this.setColorPalette();
            this.selectedColor = this.colors[this.selectedDevice][0];
            this.changeFilterParams('color', (this.selectedColorIndex) ? this.selectedColorIndex : 0);
            this.cases = window.cases[this.selectedDevice];

            setTimeout(() => {
              this.initCaseSelect();
              this.select2case.val((queryParams.case) ? queryParams.case : 'silicone').trigger('change');
            }, 0);
          })

          .on('select2:closing', function (evt) {
            $('.select2-results__options').mCustomScrollbar("destroy");
          })
          .on('select2:open', function (evt) {
            setTimeout(function() {
              $('.select2-results__options').mCustomScrollbar({
                  axis: 'y',
                  scrollInertia: 400
              });

              $(document).scroll();
            }, 1);
          });
        }

        if ($('.js-select-item-device-modal').length) {
          this.select2deviceModal = $('.js-select-item-device-modal')
          this.select2deviceModal.select2({
            dropdownCssClass: 'drop-item',
            minimumResultsForSearch: -1
          })

          .on('change', () => {
            var queryParams = this.$route.query;

            this.selectedDevice = this.select2deviceModal.val();

            this.setColorPalette();
            this.selectedColor = this.colors[this.selectedDevice][0];
            this.changeFilterParams('color', (this.selectedColorIndex) ? this.selectedColorIndex : 0);
            this.cases = window.cases[this.selectedDevice];

            setTimeout(() => {
              this.initCaseModalSelect();
              this.select2caseModal.val((queryParams.case) ? queryParams.case : 'silicone').trigger('change');
            }, 0);
          })

          .on('select2:closing', function (evt) {
            $('.select2-results__options').mCustomScrollbar("destroy");
          })
          .on('select2:open', function (evt) {
            setTimeout(function() {
              $('.select2-results__options').mCustomScrollbar({
                  axis: 'y',
                  scrollInertia: 400
              });

              $(document).scroll();
            }, 1);
          });
        }

      },

      applyFilters() {
        var queryParams = this.$route.query;

        if (this.selectedCase !== queryParams.case) {
          this.changeFilterParams('case', this.selectedCase);
        }

        // this.changeFilterParams('color', this.selectedColorIndex);
        this.selectColor(this.selectedPalette[this.selectedColorIndex], this.selectedColorIndex);

        if (this.selectedDevice !== queryParams.device) {
          this.select2device.val(this.selectedDevice).trigger('change');
        }

        $('.js-popup-close').click();
      }
    },


    mounted() {
      var queryParams = this.$route.query;

      this.isCollection = window.location.pathname.split('/')[1] == 'collections';

      if (this.isCollection) {
        this.cases = window.cases[(queryParams.device) ? queryParams.device : 'iphone7'];

        this.select2init();
        this.changeFilterParams('sort', (queryParams.sort) ? queryParams.sort : '');

        this.select2device.val((queryParams.device) ? queryParams.device : 'iphone7').trigger('change');
        this.select2deviceModal.val((queryParams.device) ? queryParams.device : 'iphone7').trigger('change');

        this.setColorPalette();

        if (queryParams.color) {
          this.selectColor(this.selectedPalette[queryParams.color], queryParams.color);
        } else {
          this.selectColor(this.selectedPalette[0], 0);
        }

        this.getFilteredData();
        this.isLoaded = true;
      } else {
        this.getFilteredData();
        this.isLoaded = true;
      }

    }
}).$mount('#app');