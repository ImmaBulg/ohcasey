<template>
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="" @submit.prevent="onSubmit()" @keydown="form.errors.clear($event.target.name)">
                <div class="form-group" :class="[form.errors.has('option_group_id') ? 'has-error' : '']">
                    <label>Тип товара</label>
                    <p v-if="!this.is_create"><span v-text="form.option_group.name"></span></p>
                    <input v-if="!this.is_create" type="hidden" v-model="selected_option_group_id">
                    <select v-show="is_create" class="form-control" v-model="selected_option_group_id">
                        <option v-for="option in this.data.option_groups" :value="option.id"
                                v-html="option.name"></option>
                    </select>
                    <span class="help-block is-danger" v-if="form.errors.has('option_group_id')"
                          v-text="form.errors.get('option_group_id')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('name') ? 'has-error' : '']">
                    <label>Название товара</label>
                    <input class="form-control" name="name" type="text" v-model="form.name">
                    <span class="help-block is-danger" v-if="form.errors.has('name')"
                          v-text="form.errors.get('name')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('background_id') ? 'has-error' : '']">
                    <label>Картинка товара</label>
                    <select class="form-control" v-model="selected_background">
                        <option v-for="option in this.data.background_options" :value="option.id"
                                v-html="option.name"></option>
                    </select>
                    <span class="help-block is-danger" v-if="form.errors.has('background_id')"
                          v-text="form.errors.get('background_id')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('code') ? 'has-error' : '']">
                    <label>Артикул</label>
                    <input class="form-control" name="code" type="text" v-model="form.code">
                    <span class="help-block is-danger" v-if="form.errors.has('code')"
                          v-text="form.errors.get('code')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('title') ? 'has-error' : '']">
                    <label>Title meta</label>
                    <input class="form-control" name="title" type="text" v-model="form.title">
                    <span class="help-block is-danger" v-if="form.errors.has('title')"
                          v-text="form.errors.get('title')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('keywords') ? 'has-error' : '']">
                    <label>Keywords</label>
                    <input class="form-control" name="keywords" type="text" v-model="form.keywords">
                    <span class="help-block is-danger" v-if="form.errors.has('keywords')"
                          v-text="form.errors.get('keywords')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('h1') ? 'has-error' : '']">
                    <label>H1</label>
                    <input class="form-control" name="h1" type="text" v-model="form.h1">
                    <span class="help-block is-danger" v-if="form.errors.has('h1')"
                          v-text="form.errors.get('h1')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('cat') ? 'has-error' : '']">
                    <label>Категории</label>
                    <select class="form-control" v-model="selected" multiple size="8" data-default="default_selected">
                        <option v-for="option in this.data.category_options" :value="option.id"
                                v-html="option.path"></option>
                    </select>
                    <span class="help-block is-danger" v-if="form.errors.has('cat')"
                          v-text="form.errors.get('cat')"></span>
                    <a @click="resetCategories()">Восстановить</a>
                </div>
                <div class="form-group" :class="[form.errors.has('description') ? 'has-error' : '']">
                    <label>Описание</label>
                    <textarea class="form-control" name="description" type="text" v-model="form.description"
                              style="display:none;"></textarea>
                    <quill-editor v-model="form.description"></quill-editor>
                    <span class="help-block is-danger" v-if="form.errors.has('description')"
                          v-text="form.errors.get('description')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('price') ? 'has-error' : '']">
                    <label>Цена (&#8381;)</label>
                    <input class="form-control" name="price" type="text" v-model="form.price">
                    <span class="help-block is-danger" v-if="form.errors.has('price')"
                          v-text="form.errors.get('price')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('discount') ? 'has-error' : '']">
                    <label>Старая цена</label>
                    <input class="form-control" name="discount" type="text" v-model="form.discount">
                    <span class="help-block is-danger" v-if="form.errors.has('discount')"
                          v-text="form.errors.get('discount')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('order') ? 'has-error' : '']">
                    <label>Порядок</label>
                    <input class="form-control" name="order" type="text" v-model="form.order">
                    <span class="help-block is-danger" v-if="form.errors.has('order')"
                          v-text="form.errors.get('order')"></span>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" v-model="form.active"> Активен
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" v-model="form.bestseller"> Бестселлер
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" v-model="form.sale"> Акция
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" v-model="form.hit"> Хит
                    </label>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" v-model="form.maket_photo"> Макет как основное фото
                    </label>
                </div>
                <div class="form-group">
                    <div v-if="photos.length" class="photos">
                        <div class="photo-block" v-for="(photo, index) in photos">
                            <div class="photo" :class="photo.default ? 'default' : ''">
                                <a @click="clickDefault(photo.id, index)"><img :src="photo.url" alt=""
                                                                               width="150px"></a>
                            </div>
                            <a @click="removePhoto(photo)">Удалить</a>
                        </div>
                    </div>
                </div>
                <div class="form-group clear">
                    <div id="dz" class="dropzone" action="/">
                        <div class="fallback">
                            <input name="file" type="file" multiple/>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Теги</label>
                    <div class="well">
                        <table class="table">
                            <tr v-for="item in this.form.tags">
                                <td>{{ item.name }}</td>
                                <td><a @click="removeTag(item.id)">Удалить</a></td>
                            </tr>
                        </table>
                        <div class="form-inline">
                            <div class="form-group">
                                <select class="js-data-tags-ajax form-control">
                                    <option value="">Выберите тег</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button @click="addTag()"class="btn btn-default btn-primary" type="button">Добавить</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Сопутствующие товары</label>
                    <div class="well">
                        <table class="table">
                            <tr v-for="item in this.form.related">
                                <td>{{ item.name }}</td>
                                <td><a @click="removeRelated(item.id)">Удалить</a></td>
                            </tr>
                        </table>
                        <div class="form-inline">
                            <div class="form-group">
                                <select class="js-data-example-ajax form-control">
                                    <option value="">Выберите сопутствующий товар</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button @click="addRelated()"class="btn btn-default btn-primary" type="button">Добавить</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Сохранить</button>
                    <router-link :to="{ name: 'product.index'}" class="btn btn-default">Отмена</router-link>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import vuestore from './../../vuestore'
    import quillEditor from '../quill-editor.vue'
    import dropzone from 'dropzone'
    import Form from './../../form'
    import 'dropzone/dist/dropzone.css'

    export default {
        props: ['is_create'],
        data(){
            return {
                lroutes,
                vuestore,
                selected: [],
                selected_background: null,
                selected_option_group_id: null,
                option_groups: [],
                default_selected: [],
                selected_related_product: null,
                selected_tag: null,
                photos: [],
                data: {}, //recived additinol data from server
                form: new Form({
                    name: '',
                    background_id: '',
                    code: '',
                    title: '',
                    keywords: '',
                    h1: '',
                    description: '',
                    price: '',
                    discount: '',
                    order: '',
                    active: false,
                    hit: false,
                    sale: false,
                    bestseller: false,
                    maket_photo: true,
                    categories: [],
                    related: [],
                    tags: [],
                    option_group_id: null,
                    option_group: {
                        name: ''
                    }
                }),
            }
        },
        components: {
            quillEditor
        },
        created(){
            var path;
            if (this.$route.params.id) {
                path = lroutes.api.admin.ecommerce.product.edit.replace(/{.*}/, this.$route.params.id)
            } else {
                path = lroutes.api.admin.ecommerce.product.create
            }
            this.vuestore.loading = true;
            this.form.get(path)
                    .then(response => {
                        if (response.data) {
                            this.data = response.data.data;
                            if (this.form.categories && this.form.categories.length) {
                                this.form.categories.forEach(c => {
                                    this.selected.push(c.id)
                                });
                                this.default_selected = this.selected
                            }
                            if (this.form.background_id) {
                                this.selected_background = this.form.background_id
                            }
                        }
                        this.vuestore.loading = false;
                        this.getPhotos(this.data.id)
                    })
                    .catch(response => {
                        console.error(response);
                        this.vuestore.loading = false
                    })
        },
        mounted(){
            dropzone.autoDiscover = false;
            let dzsettings = {};
            dzsettings.url = lroutes.api.admin.ecommerce.photo.store;
            dzsettings.paramName = 'photo';
            dzsettings.maxFilesize = 10;
            dzsettings.maxFiles = 10;
            // dzsettings.addRemoveLinks = true
            this.dz = new dropzone("#dz", dzsettings);

            this.dz.on('sending', (file, response, formData) => {
                if (this.$route.params.id) {
                    formData.append('id', this.$route.params.id)
                }
            });
            this.dz.on('success', (file, response) => {
                this.getPhotos(this.data.id);
                this.dz.removeAllFiles()
            })
            var $tagsSelect = $('.js-data-tags-ajax').select2({
                placeholder: "Выберите тег",
                minimumInputLength: 1,
                ajax: {
                    url: "/api/admin/ecommerce/tag",
                    dataType: 'json',
                    cache: true,
                    delay: 250,
                    data: function (params) {
                        return { search: params.term };
                    },
                    processResults: function (data, params) {
                        return { results: data.data };
                    }
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateResult: function (product) {
                    return '<div>' + product.name + '</div>';
                },
                templateSelection: function (product) {
                    return product.name;
                }
            });

            $tagsSelect.on("select2:select", (e) => {
                this.selected_tag = e.params.data;
            });

            var $relatedSelect = $(".js-data-example-ajax").select2({
                placeholder: "Выберите сопутствующий товар",
                minimumInputLength: 1,
                ajax: {
                    url: "/api/admin/ecommerce/product",
                    dataType: 'json',
                    cache: true,
                    delay: 250,
                    data: function (params) {
                        return { search: params.term };
                    },
                    processResults: function (data, params) {
                        return { results: data.data };
                    }
                },
                escapeMarkup: function (markup) {
                    return markup;
                },
                templateResult: function (product) {
                    return '<div>' + product.name + '</div>';
                },
                templateSelection: function (product) {
                    return product.name;
                }
            });

            $relatedSelect.on("select2:select", (e) => {
                this.selected_related_product = e.params.data;
            });
        },
        methods: {
            onSubmit() {
                if (this.vuestore.loading) return;
                else this.vuestore.loading = true;

                if (this.form.related) {
                    this.form.related = this.form.related.map((item) => item.id);
                }

                if (this.form.tags) {
                    this.form.tags = this.form.tags.map((item) => item.id);
                }

                if (this.$route.params.id) {
                    let path = lroutes.api.admin.ecommerce.product.update.replace(/{.*}/, this.$route.params.id);
                    this.form.put(path)
                            .then(response => {
                                this.vuestore.loading = false;
                                this.$root.$router.push({name: 'product.index'})
                            })
                            .catch(response => {
                                console.error(response);
                                this.vuestore.loading = false
                            })
                } else {
                    let path = lroutes.api.admin.ecommerce.product.store;
                    this.form.post(path)
                            .then(response => {
                                this.vuestore.loading = false;
                                this.$root.$router.push({name: 'product.index'})
                            })
                            .catch(response => {
                                console.error(response);
                                this.vuestore.loading = false
                            })
                }
            },
            resetCategories(){
                this.selected = this.default_selected
            },
            getPhotos(id){
                let settings = {};
                settings.path = lroutes.api.admin.ecommerce.photo.index;
                settings.method = 'get';
                settings.params = {id: id};
                this.vuestore.loading = true;
                this.vuestore.request(settings).then(response => {
                    if (response.data.length) {
                        this.photos = response.data;
                        // server return first photo as default
                        this.setDefault(0)
                    }
                    this.vuestore.loading = false
                }).catch(response => console.error(response))
            },
            removeRelated(item) {
                var removeIndex = this.form.related.map(function(item) {
                    return item.id;
                }).indexOf(item);
                this.form.related.splice(removeIndex, 1);
            },
            addRelated() {
                if (!this.form.related) this.form.related = [];
                this.form.related.push(this.selected_related_product);
            },
            removeTag(item) {
                var removeIndex = this.form.tags.map(function(item) {
                    return item.id;
                }).indexOf(item);
                this.form.tags.splice(removeIndex, 1);
            },
            addTag() {
                if (!this.form.tags) this.form.tags = [];
                this.form.tags.push(this.selected_tag);
            },
            removePhoto(photo){
                if (this.vuestore.loading) return;
                let settings = {}
                settings.path = lroutes.api.admin.ecommerce.photo.destroy.replace(/{.*}/, photo.id);
                settings.method = 'delete';
                this.vuestore.loading = true;
                this.vuestore.request(settings).then(response => {
                    this.photos = this.photos.filter(item => {
                        return item.id != photo.id
                    });
                    this.vuestore.loading = false;
                    if (photo.default) this.getPhotos(this.data.id)
                }).catch(response => {
                    console.error(response);
                    this.vuestore.loading = false
                })
            },
            clickDefault(id, index){
                if (this.vuestore.loading) return;
                this.setDefault(index);
                let settings = {};
                settings.path = lroutes.api.admin.ecommerce.photo.update.replace(/{.*}/, id)
                settings.method = 'put';
                this.vuestore.loading = true;
                this.vuestore.request(settings).then(response => {
                    this.vuestore.loading = false
                }).catch(response => {
                    console.error(response);
                    this.vuestore.loading = false
                })
            },
            setDefault(index){
                this.photos.forEach((item, i) => {
                    this.$set(item, 'default', (i == index))
                })
            }
        },
        watch: {
            selected(){
                this.form.categories = this.selected
            },
            selected_background(){
                this.form.background_id = this.selected_background
            },
            selected_option_group_id(){
                this.form.option_group_id = this.selected_option_group_id
            }
        },
    }
</script>

<style>
    .clear {
        clear: both;
    }

    .photo-block {
        float: left;
        margin: 15px;
    }

    .photo {
        width: 150px;
        height: 150px;
        overflow: hidden;
        border-radius: 5px;
    }

    .photo img {
        width: 150px;
        height: auto;
    }

    .photo.default {
        border: 3px solid green;
    }
</style>