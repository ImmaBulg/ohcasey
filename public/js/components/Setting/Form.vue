<template>
<div class="col-lg-7">
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="" @submit.prevent="onSubmit()" @keydown="form.errors.clear($event.target.name)">
                <div class="form-group" :class="[form.errors.has('title') ? 'has-error' : '']">
                    <label>Название</label>
                    <input class="form-control" name="title" type="text" v-model="form.title">
                    <span class="help-block is-danger" v-if="form.errors.has('name')" v-text="form.errors.get('title')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('value') ? 'has-error' : '']">
                    <label>Значение</label>
                    <input class="form-control" name="value" type="text" v-model="form.value">
                    <span class="help-block is-danger" v-if="form.errors.has('value')" v-text="form.errors.get('value')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('key') ? 'has-error' : '']">
                    <label>Ключ</label>
                    <input class="form-control" name="key" type="text" v-model="form.key">
                    <span class="help-block is-danger" v-if="form.errors.has('key')" v-text="form.errors.get('key')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('type') ? 'has-error' : '']">
                    <label>Тип</label>
                    <input class="form-control" name="type" type="text" v-model="form.type">
                    <span class="help-block is-danger" v-if="form.errors.has('type')" v-text="form.errors.get('type')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('group') ? 'has-error' : '']">
                    <label>Группировка</label>
                    <input class="form-control" name="group" type="text" v-model="form.group">
                    <span class="help-block is-danger" v-if="form.errors.has('group')" v-text="form.errors.get('group')"></span>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Сохранить</button>
                    <router-link :to="{ name: 'setting.index'}" class="btn btn-default">Отмена</router-link>
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
                    title: '',
                    value: '',
                    key: '',
                    type: '',
                    group: ''
                }),
            }
        },
        created(){
            if(this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.setting.edit.replace(/{.*}/, this.$route.params.id)
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
                    let path = lroutes.api.admin.ecommerce.setting.update.replace(/{.*}/, this.$route.params.id)
                    this.form.put(path)
                        .then(response => {
                            // console.log(response)
                            this.vuestore.loading = false
                            this.$root.$router.push({name: 'setting.index'})
                        })
                        .catch(response => {
                            console.error(response)
                            this.vuestore.loading = false
                        })
                } else {
                    let path = lroutes.api.admin.ecommerce.setting.store
                    this.form.post(path)
                        .then(response => {
                            // console.log(response)
                            this.vuestore.loading = false
                            this.$root.$router.push({name: 'setting.index'})
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