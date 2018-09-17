<template>
<div>
    <div>
        <h2>{{page.name}}</h2>
    </div>
    <!-- <div class="form-group">
        <router-link :to="{name: 'option.create'}" class="btn btn-success">Добавть значения связанной опции</router-link>
        <a class="btn btn-success" disabled="disabled">Добавть значения связанной опции</a>
    </div> -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th v-for="field in fields">{{field.name}}</th>
                    <th><i class="fa fa-pencil" aria-hidden="true"></i></th>
                    <!-- <th><i class="fa fa-picture-o" aria-hidden="true"></i></th> -->
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
                            {{r}}
                        </span>
                    </td>
                    <td><router-link :to="{ name: 'offer.edit', params: { id: row.id } }"><i class="fa fa-pencil" aria-hidden="true"></i></router-link></td>
                    <!-- <td><i v-if="row.photos.length" class="fa fa-picture-o" aria-hidden="true"></i></td> -->
                    <!-- <td><a :id="'delete-' + row.id" data-confirm="Удалить опцию?" @click="destroy(row.id)"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td> -->
                </tr>
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-12">
            Страница {{rows.current_page}} из {{rows.last_page}}. Запись c {{rows.from}} по {{rows.to}} из {{rows.total}}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <ul class="pagination" style="margin: 2px 0;" v-if="rows.last_page > 1">
                <li v-if="rows.prev_page_url"><a @click="changePage(1)">&#171;</a></li>
                <li v-if="rows.prev_page_url"><a @click="changePage(rows.current_page - 1)">&#8249;</a></li>
                <li class="paginate_button" v-for="page in pagesNumbers" :class="[(page == rows.current_page) ? 'active' : '']">
                    <a @click="changePage(page)">{{page}}</a>
                </li>
                <li v-if="rows.next_page_url"><a @click="changePage(rows.current_page + 1)">&#8250;</a></li>
                <li v-if="rows.next_page_url"><a @click="changePage(rows.last_page)">&#187;</a></li>
            </ul>
        </div>
    </div>
</div>
</template>

<script>
    import Vue from 'vue'
    import DataTableMixin from './../../DataTableMixin'

    export default {
        data() {
            let sourceRoute;
            if (this.$route.name == 'product.edit') {
                sourceRoute = lroutes.api.admin.ecommerce.option_group.search_values + '?perPage=20&product_id=' + this.$route.params.id
            } else {
                sourceRoute = lroutes.api.admin.ecommerce.option_group.show.replace(/{.*}/, this.$route.params.id)
            }
            return {
                fields: [
                    {key: 'option_values', name: 'Значения', type: 'array'},
                    {key: 'active', name: 'Активно', type: 'boolean'},
                ],
                rowsRoute: sourceRoute,
            }
        },
        mixins: [DataTableMixin],
        methods: {
            changeActive(row) {
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
            }
        }
    }
</script>

<style>
    .category.label{
        margin-right:5px;
    }
</style>