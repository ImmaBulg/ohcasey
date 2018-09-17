<template>
    <div class="col-lg-6">
        <div class="panel panel-default">
            <div class="panel-body">
                <form action="" @submit.prevent="onSubmit()" @keydown="form.errors.clear($event.target.name)">
                    <div class="form-group">
                        <label>Подменю для:</label>
                        <select class="form-control" v-model="parent_id_selected">
                            <option v-for="option in menuLinksOptions" :value="option.value" v-html="option.label"></option>
                        </select>
                        <span class="help-block is-danger" v-if="form.errors.has('menu_link_type_id')" v-text="form.errors.get('menu_link_type_id')"></span>
                    </div>
                    <div class="form-group" :class="[form.errors.has('name') ? 'has-error' : '']">
                        <label>Название</label>
                        <input class="form-control" name="name" type="text" v-model="form.name">
                        <span class="help-block is-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></span>
                    </div>
                    <div class="form-group" :class="[form.errors.has('url') ? 'has-error' : '']">
                        <label>Адрес</label>
                        <input class="form-control" name="url" type="text" v-model="form.url">
                        <span class="help-block is-danger" v-if="form.errors.has('url')" v-text="form.errors.get('url')"></span>
                    </div>
                    <div class="form-group" :class="[form.errors.has('sort') ? 'has-error' : '']">
                        <label>Сортировка</label>
                        <input class="form-control" name="sort" type="text" v-model="form.sort">
                        (рекомендуемый шаг - 100)
                        <span class="help-block is-danger" v-if="form.errors.has('sort')" v-text="form.errors.get('sort')"></span>
                    </div>
                    <div class="form-group">
                        <label>Отображать</label>
                        <div class="radio">
                            <label>
                                <input type="radio" v-model="form.display_type" name="display_type" id="optionsRadios1" value="0" :checked="form.display_type == 0">Везде
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" v-model="form.display_type" name="display_type" id="optionsRadios2" value="1" :checked="form.display_type == 1">Только в мобильном меню
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" v-model="form.display_type" name="display_type" id="optionsRadios3" value="2" :checked="form.display_type == 2">Только в десктопном меню
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="information_type" v-model="form.information_type"> Информационный раздел
                        </label>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary" type="submit">Сохранить</button>
                        <router-link :to="{ name: 'menu_links.index'}" class="btn btn-default">Отмена</router-link>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import vuestore from './../../vuestore'
    import quillEditor from './../quill-editor.vue'
    import commons from './commons';

    export default {
        data() {
            let menuLinksOptions = [{value: '', label: ''}];
            commons.menuLinkLoader().then((response) => {
                response.data.data.forEach((menuLink) => {
                    if (menuLink.id != this.$route.params.id) {
                        menuLinksOptions.push({
                            value: menuLink.id,
                            label: menuLink.name
                        });
                    }
                });

                if (this.$route.params.id) {
                    var path = lroutes.api.admin.menu_links.edit.replace(/{.*}/, this.$route.params.id);
                    this.vuestore.loading = true;
                    this.form.get(path)
                        .then(response => {
                            this.vuestore.loading = false;
                            this.parent_id_selected = response.data.form.parent_id;
                        }).catch(response => {
                        this.vuestore.loading = false
                    });
                }
            });

            return {
                lroutes,
                vuestore,
                form: new Form({
                    name: '',
                    sort: '',
                    display_type: 0,
                    information_type: false,
                    url: '',
                    parent_id: ''
                }),
                menuLinksOptions,
                parent_id_selected: ''
            }
        },
        methods: {
            onSubmit() {
                if(this.vuestore.loading) return;
                else this.vuestore.loading = true;
                let promise, path;
                if(this.$route.params.id) {
                    path = lroutes.api.admin.menu_links.update.replace(/{.*}/, this.$route.params.id);
                    promise = this.form.put(path);
                } else {
                    path = lroutes.api.admin.menu_links.store;
                    promise = this.form.post(path);
                }
                console.debug(promise);
                promise.then(response => {
                    this.vuestore.loading = false;
                    this.$root.$router.push({name: 'menu_links.index'})
                }).catch(response => {
                    console.error(response);
                    this.vuestore.loading = false;
                })
            }
        },
        watch: {
            parent_id_selected(){
                this.form.parent_id = this.parent_id_selected;
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