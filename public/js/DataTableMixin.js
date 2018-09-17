import vuestore from './vuestore'

var DataTableMixin = {
    data(){
        return {
            vuestore: vuestore,
            page: {},
            rows: [],
            rowsRoute: '',
            deleteRoute: '',
            // offset: 4,
            search: '',
            active: null,
            activeOptions: [
                {text: 'Все', value: null},
                {text: 'Активные', value: 1},
                {text: 'Неактивные', value: 0},
            ],
            category: null,
            categoriesRoute: '',
            categoryOptions: [{text: 'Все', value: null}],
            // map keys for select options value and text
            categoryValueKey: 'id',
            categoryTextKey: 'path',
            firstLoaded: true
        }
    },
    created(){
        this.updateTable()
        // this.getCategories()
    },
    methods: {
        updateTable(){
            if(this.vuestore.loading && !this.firstLoaded) return
            else this.vuestore.loading = true
            this.firstLoaded = true

            let settings = {
                method: 'get',
                path: this.rowsRoute,
                params: {
                    page: this.rows.current_page,
                }
            }
            if(this.search) settings.params.search = this.search
            if(this.active != null) settings.params.active = this.active
            if(this.category != null) settings.params.category = this.category
            this.vuestore.request(settings).then(response => {
                this.rows = response.data.rows || response.data
                this.page = response.data.page
                this.vuestore.loading = false
            }).catch(response => {
                console.error(response)
                this.vuestore.loading = false
            })
        },
        // changePage(page){
        //     this.rows.current_page = page
        //     this.updateTable()
        // },
        searchRows(){
            this.updateTable()
        },
        getCategories(){
            if(this.categoryOptions.length > 1) return
            else this.vuestore.loading = true
            let settings = {
                method: 'get',
                path: this.categoriesRoute,
            }
            this.vuestore.request(settings).then(response => {
                // this.categoryOptions = this.categoryOptions.concat(response.data)
                response.data.forEach(item => {
                    this.categoryOptions.push({value: item[this.categoryValueKey], text: item[this.categoryTextKey]})
                })
                this.vuestore.loading = false
            }).catch(response => {
                console.error(response)
                this.vuestore.loading = false
            })
        },
        filterCategory(){
            this.updateTable()
        },
        destroy(params){
            let del = document.getElementById('delete-'+params.id)
            let choice = confirm(del.getAttribute('data-confirm'))
            if (!choice) return
            if(this.vuestore.loading) return
            else this.vuestore.loading = true
            let row = del.closest('tr')
            row.classList.add('danger')
            let settings = {
                method: 'delete',
                path: lroutes.api.admin.ecommerce[params.key].destroy.replace(/{.*}/, params.id),
            }
            this.vuestore.request(settings).then(response => {s
                this.vuestore.loading = false
                row.classList.remove('danger')
                this.updateTable()
            }).catch(response => {
                console.error(response)
                this.vuestore.loading = false
            })             
        }
    }
}

module.exports = DataTableMixin