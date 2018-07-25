<template>
<div class="col-lg-7">
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="" @submit.prevent="onSubmit()" @keydown="form.errors.clear($event.target.name)">
                <div class="form-group" :class="[form.errors.has('name') ? 'has-error' : '']">
                    <label>Название</label>
                    <input class="form-control" name="name" type="text" v-model="form.name">
                    <span class="help-block is-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('slug') ? 'has-error' : '']">
                    <label>Slug</label>
                    <input class="form-control" name="slug" type="text" v-model="form.slug">
                    <span class="help-block is-danger" v-if="form.errors.has('slug')" v-text="form.errors.get('slug')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('title') ? 'has-error' : '']">
                    <label>Title</label>
                    <input class="form-control" name="title" type="text" v-model="form.title">
                    <span class="help-block is-danger" v-if="form.errors.has('title')" v-text="form.errors.get('title')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('h1') ? 'has-error' : '']">
                    <label>H1</label>
                    <input class="form-control" name="h1" type="text" v-model="form.h1">
                    <span class="help-block is-danger" v-if="form.errors.has('h1')" v-text="form.errors.get('h1')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('keywords') ? 'has-error' : '']">
                    <label>Keywords</label>
                    <input class="form-control" name="keywords" type="text" v-model="form.keywords">
                    <span class="help-block is-danger" v-if="form.errors.has('keywords')" v-text="form.errors.get('keywords')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('description') ? 'has-error' : '']">
                    <label>Description</label>
                    <input class="form-control" name="description" type="text" v-model="form.description">
                    <span class="help-block is-danger" v-if="form.errors.has('description')" v-text="form.errors.get('description')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('order') ? 'has-error' : '']">
                    <label>Порядок сортировки</label>
                    <input class="form-control" name="order" type="text" v-model="form.order">
                    <span class="help-block is-danger" v-if="form.errors.has('order')" v-text="form.errors.get('order')"></span>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Сохранить</button>
                    <router-link :to="{ name: 'tag.index'}" class="btn btn-default">Отмена</router-link>
                </div>
            </form>
        </div>
    </div>
</div>
</template>

<script>
    import vuestore from './../../vuestore'
    import Form from './../../form'

    export default {
        data(){
            return {
                lroutes,
                vuestore,
                data: [], //recived additinol data from server
                form: new Form({
                    name: '',
                    slug: '',
                    title: '',
                    h1: '',
                    keywords: '',
                    description: '',
                    order: ''
                }),
            }
        },
        created(){
            if(this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.tag.edit.replace(/{.*}/, this.$route.params.id)
                this.vuestore.loading = true
                this.form.get(path)
                    .then(response => {
                        if(response.data) {
                            this.data = response.data.data
                        }
                        this.vuestore.loading = false
                    })
                    .catch(response => {
                        console.error(response)
                        this.vuestore.loading = false
                    })
            }
        },
        methods: {
            onSubmit() {
                if(this.vuestore.loading) return
                else this.vuestore.loading = true
                if(this.$route.params.id) {
                    let path = lroutes.api.admin.ecommerce.tag.update.replace(/{.*}/, this.$route.params.id)
                    this.form.put(path)
                        .then(response => {
                            // console.log(response)
                            this.vuestore.loading = false
                            this.$root.$router.push({name: 'tag.index'})
                        })
                        .catch(response => {
                            console.error(response)
                            this.vuestore.loading = false
                        })
                } else {
                    let path = lroutes.api.admin.ecommerce.tag.store
                    this.form.post(path)
                        .then(response => {
                            // console.log(response)
                            this.vuestore.loading = false
                            this.$root.$router.push({name: 'tag.index'})
                        })
                        .catch(response => {
                            console.error(response)
                            this.vuestore.loading = false
                        })
                }
            }
        }
    }
</script>