<template>
<div class="col-lg-6">
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="" @submit.prevent="onSubmit()" @keydown="form.errors.clear($event.target.name)">
                <div class="form-group" :class="[form.errors.has('name') ? 'has-error' : '']">
                    <label>Название товара</label>
                    <input class="form-control" name="name" type="text" v-model="form.name">
                    <span class="help-block is-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('code') ? 'has-error' : '']">
                    <label>Артикул</label>
                    <input class="form-control" name="code" type="text" v-model="form.code">
                    <span class="help-block is-danger" v-if="form.errors.has('code')" v-text="form.errors.get('code')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('title') ? 'has-error' : '']">
                    <label>Title meta</label>
                    <input class="form-control" name="title" type="text" v-model="form.title">
                    <span class="help-block is-danger" v-if="form.errors.has('title')" v-text="form.errors.get('title')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('cat') ? 'has-error' : '']">
                    <label>Категории</label>
                    <select class="form-control" v-model="selected" multiple size="8" data-default="default_selected">
                        <option v-for="option in this.category_options" :value="option.id" v-html="option.path"></option>
                    </select>
                    <span class="help-block is-danger" v-if="form.errors.has('cat')" v-text="form.errors.get('cat')"></span>
                    <a @click="resetCategories()">Восстановить</a>
                </div>
                <div class="form-group" :class="[form.errors.has('description') ? 'has-error' : '']">
                    <label>Описание</label>
                    <textarea class="form-control" name="description" type="text" v-model="form.description" style="display:none;"></textarea>
                    <quill-editor v-model="form.description"></quill-editor>
                    <span class="help-block is-danger" v-if="form.errors.has('description')" v-text="form.errors.get('description')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('price') ? 'has-error' : '']">
                    <label>Цена (&#8381;)</label>
                    <input class="form-control" name="price" type="text" v-model="form.price">
                    <span class="help-block is-danger" v-if="form.errors.has('price')" v-text="form.errors.get('price')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('discount') ? 'has-error' : '']">
                    <label>Размер скидки</label>
                    <input class="form-control" name="discount" type="text" v-model="form.discount">
                    <span class="help-block is-danger" v-if="form.errors.has('discount')" v-text="form.errors.get('discount')"></span>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" v-model="form.active"> Активен
                    </label>
                </div> 
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Сохранить</button>
                    <router-link :to="{ name: 'product.index'}" class="btn btn-default">Отмена</router-link>
                </div>
            </form>
        </div>
    </div>
</div>
</template>

<script>
    import axios from 'axios'
    import vuestore from '../vuestore'
    import quillEditor from './quill-editor.vue'

    export default {
        data(){
            return {
                lroutes,
                vuestore,
                selected: [],
                default_selected: [],
                category_options: [],
                form: new Form({
                    name: '',
                    code: '',
                    title: '',
                    description: '',
                    price: '',
                    discount: '',
                    active: false,
                    categories: this.selected,
                }),
            }
        },
        components:{
            quillEditor
        },
        created(){
            if(this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.product.edit.replace(/{.*}/, this.$route.params.id)
            } else {
                var path = lroutes.api.admin.ecommerce.product.create
            }
            this.vuestore.loading = true
            this.form.get(path)
                .then(response => {
                    if(response.data) {
                        this.category_options = response.data.data.category_options
                        this.form.categories.forEach(c => this.selected.push(c.id))
                        this.default_selected = this.selected
                    }
                    this.vuestore.loading = false
                })
                .catch(response => {
                    // console.error(response)
                    this.vuestore.loading = false
                })
        },
        methods: {
            onSubmit() {
                if(this.vuestore.loading) return 
                else this.vuestore.loading = true
                if(this.$route.params.id) { 
                    let path = lroutes.api.admin.ecommerce.product.update.replace(/{.*}/, this.$route.params.id)
                    this.form.put(path)
                        .then(response => {
                            // console.log(response)
                            this.vuestore.loading = false
                            this.$root.$router.push({name: 'product.index'})
                        })
                        .catch(response => {
                            console.error(response)
                            this.vuestore.loading = false
                        })
                } else {
                    let path = lroutes.api.admin.ecommerce.product.store
                    this.form.post(path)
                        .then(response => {
                            // console.log(response)
                            this.vuestore.loading = false
                            this.$root.$router.push({name: 'product.index'})
                        })
                        .catch(response => {
                            console.error(response)
                            this.vuestore.loading = false
                        })
                }
            },
            resetCategories(){
                this.selected = this.default_selected
            }
        },
        watch:{
            selected(){
                this.form.categories = this.selected
            }
        },
    }

    class Errors {
        /**
         * Create a new Errors instance.
         */
        constructor() {
            this.errors = {}
        }


        /**
         * Determine if an errors exists for the given field.
         *
         * @param {string} field
         */
        has(field) {
            return this.errors.hasOwnProperty(field)
        }


        /**
         * Determine if we have any errors.
         */
        any() {
            return Object.keys(this.errors).length > 0
        }


        /**
         * Retrieve the error message for a field.
         *
         * @param {string} field
         */
        get(field) {
            if (this.errors[field]) {
                return this.errors[field][0]
            }
        }


        /**
         * Record the new errors.
         *
         * @param {object} errors
         */
        record(errors) {
            this.errors = errors
        }


        /**
         * Clear one or all error fields.
         *
         * @param {string|null} field
         */
        clear(field) {
            if (field) {
                delete this.errors[field]

                return
            }

            this.errors = {}
        }
    }


    class Form {
        /**
         * Create a new Form instance.
         *
         * @param {object} data
         */
        constructor(data) {
            this.originalData = data

            for (let field in data) {
                this[field] = data[field]
            }

            this.errors = new Errors()
        }


        /**
         * Fetch all relevant data for the form.
         */
        data() {
            let data = {}

            for (let property in this.originalData) {
                data[property] = this[property]
            }

            return data
        }


        /**
         * Reset the form fields.
         */
        reset() {
            for (let field in this.originalData) {
                this[field] = ''
            }

            this.errors.clear()
        }

        /**
         * Send a GET request to the given URL.
         * .
         * @param {string} url
         */
        get(url) {
            return this.submit('get', url)
        }

        /**
         * Send a POST request to the given URL.
         * .
         * @param {string} url
         */
        post(url) {
            return this.submit('post', url)
        }


        /**
         * Send a PUT request to the given URL.
         * .
         * @param {string} url
         */
        put(url) {
            return this.submit('put', url)
        }


        /**
         * Send a PATCH request to the given URL.
         * .
         * @param {string} url
         */
        patch(url) {
            return this.submit('patch', url)
        }


        /**
         * Send a DELETE request to the given URL.
         * .
         * @param {string} url
         */
        delete(url) {
            return this.submit('delete', url)
        }


        /**
         * Submit the form.
         *
         * @param {string} requestType
         * @param {string} url
         */
        submit(requestType, url) {
            return new Promise((resolve, reject) => {
                axios[requestType](url, this.data())
                    .then(response => {
                        if(response.data){
                            this.onSuccess(response.data)
                        }

                        resolve(response)
                    })
                    .catch(error => {
                        if(error.response) {
                            this.onFail(error.response.data)
                        }

                        reject(error)
                    })
            })
        }


        /**
         * Handle a successful form submission.
         *
         * @param {object} data
         */
        onSuccess(data) {
            if(data.form) {
                for(let field in this.originalData){
                    this[field] = data.form[field]
                }
            }
        }


        /**
         * Handle a failed form submission.
         *
         * @param {object} errors
         */
        onFail(errors) {
            this.errors.record(errors)
        }
    }
</script>