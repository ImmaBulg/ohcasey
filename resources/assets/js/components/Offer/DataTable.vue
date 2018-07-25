<template>
<div>
    <div class="form-group">
        <router-link v-if="$route.name == 'product.edit'" :to="{name: 'offer.create', query: { product_id: $route.params.id }}" class="btn btn-success">Добавть ТП к товару</router-link>&nbsp;
        <a v-if="productForm.option_group_id == 1" class="btn btn-warning" :class="vuestore.loading ? 'disabled' : ''" title="Существующие ТП остануться без изменений" @click="generate()">Дополнить ТП на основе значений опций</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th v-for="field in fields">{{field.name}}</th>
                    <!-- <th><i class="fa fa-picture-o" aria-hidden="true"></i></th> -->
                    <th><i class="fa fa-pencil" aria-hidden="true"></i></th>
                    <!-- <th><i class="fa fa-trash-o" aria-hidden="true"></i></th> -->
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in rows.data">
                    <td>{{row.id}}</td>
                    <td v-for="field in fields">
                        <template v-if="field.type == 'string'">{{row[field.key]}}</template>
                        <template v-if="field.type == 'boolean'">
                            <a @click="changeActive(row)">
                                <i v-if="row[field.key] == true" class="fa fa-toggle-on" aria-hidden="true"></i>
                                <i v-else class="fa fa-toggle-off" aria-hidden="true"></i>
                            </a>
                        </template>
                        <span v-if="field.type == 'array'" v-for="r in row[field.key]" class="category label label-success">
                            {{r.title}}
                        </span>
                    </td>
                    <!-- <td><i v-if="row.photos.length" class="fa fa-picture-o" aria-hidden="true"></i></td> -->
                    <td><router-link :to="{ name: 'offer.edit', params: { id: row.id } }"><i class="fa fa-pencil" aria-hidden="true"></i></router-link></td>
                    <!-- <td><a :id="'delete-' + row.id" data-confirm="Удалить опцию?" @click="destroy(row.id)"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td> -->
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-sm-6">
        Страница {{rows.current_page}} из {{rows.last_page}}. Запись c {{rows.from}} по {{rows.to}} из {{rows.total}}
    </div>
    <div class="col-sm-6">
        <paginator :rows="rows" v-on:update_table="updateTable"></paginator>
    </div>
</div>
</template>

<script>
    import Vue from 'vue'
    import DataTableMixin from './../../DataTableMixin'
    import paginator from './../paginator.vue'

    export default {
        components: {
            paginator
        },
        // props: ['option_group_id'],
        data(){
            let sourceRoute, sourceFields;
            if (this.$route.name == 'product.edit') {
                sourceRoute = lroutes.api.admin.ecommerce.option_group.product_offers.replace(/{.*}/, this.$route.params.id)
                sourceFields = [
                    {key: 'quantity', name: 'Кол-во', type: 'string'},
                    {key: 'option_values', name: 'Значения опций', type: 'array'},
                    {key: 'active', name: 'Активно', type: 'boolean'},
                ]
            } else {
                sourceRoute = lroutes.api.admin.ecommerce.option_group.show.replace(/{.*}/, this.$route.params.id)
                sourceFields = [
                    {key: 'product_id', name: 'Товар', type: 'string'},
                    {key: 'quantity', name: 'Кол-во', type: 'string'},
                    {key: 'weight', name: 'Вес', type: 'string'},
                    {key: 'option_values', name: 'Значения опций', type: 'array'},
                    {key: 'active', name: 'Активно', type: 'boolean'},
                ]
            }
            return {
                fields: sourceFields,
                rowsRoute: sourceRoute,
                productForm: this.$parent.$parent.$refs.pForm.form,
            }
        },
        mixins: [DataTableMixin],
        methods: {
            changeActive(row) {
                if(this.vuestore.loading) return
                this.vuestore.loading = true;
                let settings = {
                    method: 'post',
                    path: lroutes.api.admin.ecommerce.option_group_value.update.replace(/{.*}/, row.id),
                    params: {
                        active: !!!row.active,
                    }
                };
                this.vuestore.request(settings).then(response => {
                    row.active = !row.active;
                    this.vuestore.loading = false
                }).catch(response => {
                    console.error(response);
                    this.vuestore.loading = false
                })
            },
            generate(){
                if(this.vuestore.loading) return
                this.vuestore.loading = true;
                let settings = {
                    method: 'post',
                    path: lroutes.api.admin.ecommerce.product.generate.replace(/{.*}/, this.$route.params.id),
                };
                this.vuestore.request(settings).then(response => {
                    let newOffers = response.data.counts.offers_affter - response.data.counts.offers_before
                    let msg = newOffers > 0 ? "Добавлено "+newOffers+" новых ТП на основе значений опций товара." : "Новые ТП не найдены."
                    window.alert(msg)
                    this.vuestore.loading = false
                }).catch(response => {
                    console.error(response);
                    this.vuestore.loading = false
                })
            }
        }
    }
</script>

<style>
    .category.label{
        margin-right:5px;
    }
</style>