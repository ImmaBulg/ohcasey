<template>
<div class="col-lg-7">
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="" @submit.prevent="onSubmit()" @keydown="form.errors.clear($event.target.name)">
                <div class="form-group" :class="[form.errors.has('key') ? 'has-error' : '']">
                    <label>Ключ</label>
                    <input class="form-control" name="key" type="text" v-model="form.key">
                    <span class="help-block is-danger" v-if="form.errors.has('key')" v-text="form.errors.get('key')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('name') ? 'has-error' : '']">
                    <label>Название опции</label>
                    <input class="form-control" name="name" type="text" v-model="form.name">
                    <span class="help-block is-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('order') ? 'has-error' : '']">
                    <label>Порядок сортировки</label>
                    <input class="form-control" name="order" type="text" v-model="form.order">
                    <span class="help-block is-danger" v-if="form.errors.has('order')" v-text="form.errors.get('order')"></span>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Сохранить</button>
                    <router-link :to="{ name: 'option.index'}" class="btn btn-default">Отмена</router-link>
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
                    key: '',
                    order: ''
                }),
            }
        },
        created(){
            if(this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.option.edit.replace(/{.*}/, this.$route.params.id)
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
                    let path = lroutes.api.admin.ecommerce.option.update.replace(/{.*}/, this.$route.params.id)
                    this.form.put(path)
                        .then(response => {
                            // console.log(response)
                            this.vuestore.loading = false
                            this.$root.$router.push({name: 'option.index'})
                        })
                        .catch(response => {
                            console.error(response)
                            this.vuestore.loading = false
                        })
                } else {
                    let path = lroutes.api.admin.ecommerce.option.store
                    this.form.post(path)
                        .then(response => {
                            // console.log(response)
                            this.vuestore.loading = false
                            this.$root.$router.push({name: 'option.index'})
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