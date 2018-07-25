<template>
<div class="col-lg-7">
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="" @submit.prevent="onSubmit()" @keydown="form.errors.clear($event.target.name)">
                <div class="form-group" :class="[form.errors.has('option_id') ? 'has-error' : '']">
                    <label>Опция</label>
                    <select class="form-control" v-model="selected_option">
                        <option v-for="option in this.data.options" :value="option.id" v-html="option.name"></option>
                    </select>
                    <span class="help-block is-danger" v-if="form.errors.has('option_id')" v-text="form.errors.get('option_id')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('value') ? 'has-error' : '']">
                    <label>Значение</label>
                    <input class="form-control" name="value" type="text" v-model="form.value">
                    <span class="help-block is-danger" v-if="form.errors.has('value')" v-text="form.errors.get('value')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('title') ? 'has-error' : '']">
                    <label>Название значения</label>
                    <input class="form-control" name="title" type="text" v-model="form.title">
                    <span class="help-block is-danger" v-if="form.errors.has('title')" v-text="form.errors.get('title')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('description') ? 'has-error' : '']">
                    <label>Описание значения</label>
                    <textarea class="form-control" name="description" v-model="form.description"></textarea>
                    <span class="help-block is-danger" v-if="form.errors.has('description')" v-text="form.errors.get('description')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('image') ? 'has-error' : '']">
                    <label>Фото значения</label>
                    <input class="form-control" name="image" type="text" v-model="form.image">
                    <span class="help-block is-danger" v-if="form.errors.has('image')" v-text="form.errors.get('image')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('order') ? 'has-error' : '']">
                    <label>Порядок сортировки</label>
                    <input class="form-control" name="order" type="text" v-model="form.order">
                    <span class="help-block is-danger" v-if="form.errors.has('order')" v-text="form.errors.get('order')"></span>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Сохранить</button>
                    <router-link :to="{ name: 'option_value.index'}" class="btn btn-default">Отмена</router-link>
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
                selected_option: null,
                form: new Form({
                    option_id: '',
                    value: '',
                    title: '',
                    description: '',
                    image: '',
                    order: ''
                }),
            }
        },
        created(){
            if(this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.option_value.edit.replace(/{.*}/, this.$route.params.id)
            } else {
                path = lroutes.api.admin.ecommerce.option_value.create
            }
            this.vuestore.loading = true
            this.form.get(path)
                .then(response => {
                    if(response.data) {
                        this.data = response.data.data                        
                    }
                    if(this.form.option_id) {
                        this.selected_option = this.form.option_id
                    }
                    this.vuestore.loading = false
                })
                .catch(response => {
                    console.error(response)
                    this.vuestore.loading = false
                })
        },
        methods: {
            onSubmit() {
                if(this.vuestore.loading) return 
                else this.vuestore.loading = true
                if(this.$route.params.id) { 
                    let path = lroutes.api.admin.ecommerce.option_value.update.replace(/{.*}/, this.$route.params.id)
                    this.form.put(path)
                        .then(response => {
                            // console.log(response)
                            this.vuestore.loading = false
                            this.$root.$router.push({name: 'option_value.index'})
                        })
                        .catch(response => {
                            console.error(response)
                            this.vuestore.loading = false
                        })
                } else {
                    let path = lroutes.api.admin.ecommerce.option_value.store
                    this.form.post(path)
                        .then(response => {
                            // console.log(response)
                            this.vuestore.loading = false
                            this.$root.$router.push({name: 'option_value.index'})
                        })
                        .catch(response => {
                            console.error(response)
                            this.vuestore.loading = false
                        })
                }
            }
        },
        watch: {
            selected_option(){
                this.form.option_id = this.selected_option
            }
        }
    }
</script>