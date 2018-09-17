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
                <div class="form-group" :class="[form.errors.has('delivery_info') ? 'has-error' : '']">
                    <label>О доставке</label>
                    <textarea class="form-control" name="delivery_info" v-model="form.delivery_info" rows="10"></textarea>
                    <span class="help-block is-danger" v-if="form.errors.has('delivery_info')" v-text="form.errors.get('delivery_info')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('options') ? 'has-error' : '']">
                    <label>Опции</label>
                    <select class="form-control" multiple size="8" v-model="selected_option">
                        <option v-for="option in this.data.options" :value="option.id" v-html="option.name"></option>
                    </select>
                    <span class="help-block is-danger" v-if="form.errors.has('options')" v-text="form.errors.get('options')"></span>
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
                form: new Form({
                    name: '',
                    delivery_info: '',
                    options: [],
                }),
                selected_option: []
            }
        },
        created(){
            if(this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.option_group.edit.replace(/{.*}/, this.$route.params.id)
            } else {
                path = lroutes.api.admin.ecommerce.option_group.create
            }
            this.vuestore.loading = true
            this.form.get(path)
                .then(response => {
                    if(response.data) {
                        this.data = response.data.data
                        if(this.form.options){
                            this.selected_option = this.form.options
                        }
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
                    let path = lroutes.api.admin.ecommerce.option_group.update.replace(/{.*}/, this.$route.params.id)
                    this.form.put(path)
                        .then(response => {
                            // console.log(response)
                            this.vuestore.loading = false
                            this.$root.$router.push({name: 'option_group.index'})
                        })
                        .catch(response => {
                            console.error(response)
                            this.vuestore.loading = false
                        })
                } else {
                    let path = lroutes.api.admin.ecommerce.option_group.store
                    this.form.post(path)
                        .then(response => {
                            // console.log(response)
                            this.vuestore.loading = false
                            this.$root.$router.push({name: 'option_group.index'})
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
                this.form.options = this.selected_option
            }
        }
    }
</script>