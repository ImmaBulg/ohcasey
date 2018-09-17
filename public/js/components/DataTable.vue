<template>
<div>
    <div class="form-group">
        <router-link :to="{name: 'product.create'}" class="btn btn-success">Добавть товар</router-link>
    </div>
    <div class="row">
        <div class="col-sm-5 left">
            <div class="form-group input-group">
                <input @keyup.enter="searchProducts()" type="text" class="form-control" placeholder="Поиск по артикулу или названию" v-model="search">
                <span class="input-group-btn">
                    <a @click="searchProducts()" class="btn btn-primary">
                        <i class="fa fa-search"></i>
                    </a>
                </span>
            </div>
        </div>
        <div class="col-sm-5">
            <div class="form-group">
                <select id="category-select" class="form-control" name="category" v-model="category" @change="filterCategory()">
                    <option v-for="option in categoryOptions" :value="option.value" :selected="option.value == null" v-html="option.text"></option>
                </select>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="form-group">
               <select class="form-control" name="active" v-model="active" @change="searchProducts()">
                    <option v-for="option in activeOptions" :value="option.value" :selected="option.value == null">
                        {{option.text}}
                    </option>
                </select>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th v-for="field in fields">{{field.name}}</th>
                    <th>edit</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in products.data">
                    <td>{{row.id}}</td>
                    <td v-for="field in fields">
                        <template v-if="field.type == 'string'">{{row[field.key]}}</template>
                        <span v-if="field.type == 'array'" v-for="category in row[field.key]" class="category label label-primary">
                            {{category.name}}
                        </span>
                        <template v-if="field.type == 'boolean'">{{row[field.key] == true ? 'Да':'Нет'}}</template>
                    </td>
                    <td><router-link :to="{ name: 'product.edit', params: { id: row.id } }">edit</router-link></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-sm-6">
        Страница {{products.current_page}} из {{products.last_page}}. Запись c {{products.from}} по {{products.to}} из {{products.total}}
    </div>
    <div class="col-sm-6">
        <ul class="pagination" style="margin: 2px 0; float: right;" v-if="products.last_page > 1">
            <li v-if="products.prev_page_url"><a @click="changePage(1)">&#171;</a></li>
            <li v-if="products.prev_page_url"><a @click="changePage(products.current_page - 1)">&#8249;</a></li>
            <li class="paginate_button" v-for="page in pagesNumbers" :class="[(page == products.current_page) ? 'active' : '']">
                <a @click="changePage(page)">{{page}}</a>
            </li>
            <li v-if="products.next_page_url"><a @click="changePage(products.current_page + 1)">&#8250;</a></li>
            <li v-if="products.next_page_url"><a @click="changePage(products.last_page)">&#187;</a></li>
        </ul>
    </div>
</div>
</template>

<script>
    import Vue from 'vue'
    import axios from 'axios'
    import vuestore from '../vuestore'

    export default {
        data(){
            return {
                vuestore: vuestore,
                lroutes,
                fields: [
                    {key: 'code', name: 'Артикул', type: 'string'},
                    {key: 'name', name: 'Название', type: 'string'},
                    {key: 'price_string', name: 'Цена', type: 'string'},
                    {key: 'categories', name: 'Категории', type: 'array'},
                    {key: 'active', name: 'Активен', type: 'boolean'},
                ],
                products: [],
                offset: 4,
                search: '',
                active: null,
                activeOptions: [
                    {text: 'Все', value: null},
                    {text: 'Активные', value: 1},
                    {text: 'Неактивные', value: 0},
                ],
                category: null,
                categoryOptions: [{text: 'Все', value: null}],
            }
        },
        created(){
            this.updateTable()
            this.getCategories()
        },
        methods: {
            updateTable(){
                if(this.vuestore.loading) return 
                else this.vuestore.loading = true
                let settings = {
                    method: 'get',
                    path: lroutes.api.admin.ecommerce.product.index,
                    params: {
                        page: this.products.current_page,
                    }
                }
                if(this.search) settings.params.search = this.search
                if(this.active != null) settings.params.active = this.active
                if(this.category != null) settings.params.category = this.category
                this.vuestore.request(settings).then(response => {
                    this.products = response.data
                    this.vuestore.loading = false
                }).catch(response => {
                    console.error(response)
                    this.vuestore.loading = false
                })
            },
            changePage(page){
                this.products.current_page = page
                this.updateTable()
            },
            searchProducts(){
                this.updateTable()
            },
            getCategories(){
                if(this.categoryOptions.length > 1) return
                else this.vuestore.loading = true
                let settings = {
                    method: 'get',
                    path: lroutes.api.admin.ecommerce.category.index,
                }
                this.vuestore.request(settings).then(response => {
                    // this.categoryOptions = this.categoryOptions.concat(response.data)
                    response.data.forEach(item => {
                        this.categoryOptions.push({value: item.id, text: item.path})
                    })
                    this.vuestore.loading = false
                }).catch(response => {
                    console.error(response)
                    this.vuestore.loading = false
                })
            },
            filterCategory(){
                this.updateTable()
            }
        },
        computed: {
            pagesNumbers() {
                if (!this.products.to) {
                    return [];
                }
                var from = this.products.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.products.last_page) {
                    to = this.products.last_page;
                }
                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        }
    }
</script>

<style>
    .category.label{
        margin-right:5px;
    }
</style>