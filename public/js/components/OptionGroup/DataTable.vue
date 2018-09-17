<template>
<div>
    <div class="form-group">
        <router-link :to="{name: 'option_group.create'}" class="btn btn-success" disbled>Добавть тип товара</router-link>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th v-for="field in fields">{{field.name}}</th>
                    <!-- <th><i class="fa fa-picture-o" aria-hidden="true"></i></th> -->
                    <th><i class="fa fa-search" aria-hidden="true"></i></th>
                    <th><i class="fa fa-pencil" aria-hidden="true"></i></th>
                    <!-- <th><i class="fa fa-trash-o" aria-hidden="true"></i></th> -->
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in rows.data">
                    <td>{{row.id}}</td>
                    <td v-for="field in fields">
                        {{row[field.key]}}
                    </td>
                    <!-- <td><i v-if="row.photos.length" class="fa fa-picture-o" aria-hidden="true"></i></td> -->
                    <td><router-link :to="{ name: 'option_group.show', params: { id: row.id } }"><i class="fa fa-search" aria-hidden="true"></i></router-link></td>
                    <td><router-link :to="{ name: 'option_group.edit', params: { id: row.id } }"><i class="fa fa-pencil" aria-hidden="true"></i></router-link></td>
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
        data(){
            return {
                fields: [
                    {key: 'name', name: 'Название', type: 'string'},
                ],
                rowsRoute: lroutes.api.admin.ecommerce.option_group.index,
            }
        },
        mixins: [DataTableMixin],
    }
</script>

<style>
    .category.label{
        margin-right:5px;
    }
</style>