<template>
<div>
    <div class="form-group">
        <router-link :to="{name: 'tag.create'}" class="btn btn-success">Добавть тег</router-link>
    </div>
    <div class="row">
        <div class="col-sm-5 left">
            <div class="form-group input-group">
                <slot name="searchfilter" :search="search">
                    <input @keyup.enter="searchRows()" type="text" class="form-control" placeholder="" v-model="search">
                </slot>
                <span class="input-group-btn">
                    <a @click="searchRows()" class="btn btn-primary">
                        <i class="fa fa-search"></i>
                    </a>
                </span>
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th v-for="field in fields">{{field.name}}</th>
                    <!-- <th><i class="fa fa-picture-o" aria-hidden="true"></i></th> -->
                    <th><i class="fa fa-pencil" aria-hidden="true"></i></th>
                    <th><i class="fa fa-trash-o" aria-hidden="true"></i></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in rows.data">
                    <td>{{row.id}}</td>
                    <td v-for="field in fields">
                        {{row[field.key]}}
                    </td>
                    <!-- <td><i v-if="row.photos.length" class="fa fa-picture-o" aria-hidden="true"></i></td> -->
                    <td><router-link :to="{ name: 'tag.edit', params: { id: row.id } }"><i class="fa fa-pencil" aria-hidden="true"></i></router-link></td>
                    <td><a :id="'delete-' + row.id" data-confirm="Удалить тег?" @click="destroy({id: row.id, key: 'tag'})"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
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
                    {key: 'slug', name: 'Slug', type: 'string'},
                    {key: 'title', name: 'Title', type: 'string'},
                    {key: 'h1', name: 'H1', type: 'string'},
                    {key: 'keywords', name: 'Keywords', type: 'string'},
                    {key: 'description', name: 'Description', type: 'string'},
                    {key: 'order', name: 'Порядок', type: 'string'},
                ],
                rowsRoute: lroutes.api.admin.ecommerce.tag.index,
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