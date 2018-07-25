<template>
<div class="col-lg-7">
    <div class="panel panel-default">
        <div class="panel-body">
            <h2 v-if="data.product">{{data.product.title}}</h2>
            <form action="" @submit.prevent="onSubmit()" @keydown="form.errors.clear($event.target.name)">
                <div class="form-group" :class="[form.errors.has('quantity') ? 'has-error' : '']">
                    <label>Кол-во</label>
                    <input class="form-control" name="quantity" type="text" v-model="form.quantity">
                    <span class="help-block is-danger" v-if="form.errors.has('quantity')" v-text="form.errors.get('quantity')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('weight') ? 'has-error' : '']">
                    <label>Вес</label>
                    <input class="form-control" name="weight" type="text" v-model="form.weight">
                    <span class="help-block is-danger" v-if="form.errors.has('weight')" v-text="form.errors.get('weight')"></span>
                </div>
                <div class="form-group" :class="[form.errors.has('options') ? 'has-error' : '']">
                    <label>Значения опций</label>
                    <!-- <div v-for="v in data.offer_values" class="form-group">
                        <select name="" class="form-control" v-model="selectsData[v.option_id]" :id="'select_for_option_id_'+v.option_id">
                            <option v-for="o in data.values[v.option_id]" :value="o.id">{{o.title}}</option>
                        </select>
                    </div> -->
                    <div v-for="(v, k) in data.values" class="form-group">
                        <select name="" class="form-control" v-model="selectsData[k]" :id="'select_for_option_id_'+k">
                            <option v-for="o in data.values[k]" :value="o.id">{{o.title}}</option>
                        </select>
                    </div>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" v-model="form.active"> Активен
                    </label>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary" type="submit">Сохранить</button>
                    <router-link v-if="this.form.product_id" :to="{ name: 'product.edit', params: {id: this.form.product_id}}" class="btn btn-default">Отмена</router-link>
                </div>
            </form>
        </div>
    </div>
</div>
</template>

<script>
    import Vue from 'vue'
    import vuestore from './../../vuestore'
    import Form from './../../form'

    export default {
        data(){
            return {
                lroutes,
                vuestore,
                data: [], //recived additinol data from server
                form: new Form({
                    product_id: '',
                    quantity: '',
                    weight: '',
                    options: '',
                    active: true
                }),
                selectsData: {}
            }
        },
        created(){
            if(this.$route.params.id) {
                var path = lroutes.api.admin.ecommerce.offer.edit.replace(/{.*}/, this.$route.params.id)
            } else {
                if(this.$route.query && this.$route.query.product_id) {
                    this.form.product_id = this.$route.query.product_id
                    var path = lroutes.api.admin.ecommerce.offer.create+'?product_id='+this.form.product_id
                } else return
            }
            this.vuestore.loading = true
            this.form.get(path)
                .then(response => {
                    if(response.data) {
                        this.data = response.data.data
                        if(this.data.offer_values){
                            for(let v in this.data.offer_values){
                                Vue.set(this.selectsData, this.data.offer_values[v].option_id, this.data.offer_values[v].id)
                            }
                        } else {
                            for(let v in Object.keys(this.data.values)){
                                Vue.set(this.selectsData, Object.keys(this.data.values)[v], '')
                            }
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
                let opt = []
                for(let s in this.selectsData){
                    if(this.selectsData[s]) opt.push(this.selectsData[s])
                }
                this.form.options = opt

                if(this.$route.params.id) { 
                    let path = lroutes.api.admin.ecommerce.offer.update.replace(/{.*}/, this.$route.params.id)
                    this.form.put(path)
                        .then(response => {
                            this.vuestore.loading = false
                            this.$root.$router.push({name: 'product.edit', params: {id: this.form.product_id}})
                        })
                        .catch(response => {
                            console.error(response)
                            this.vuestore.loading = false
                        })
                } else {
                    let path = lroutes.api.admin.ecommerce.offer.store
                    this.form.post(path)
                        .then(response => {
                            this.vuestore.loading = false
                            this.$root.$router.push({name: 'product.edit', params: {id: this.form.product_id}})
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