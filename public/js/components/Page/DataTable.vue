<template>
<div>
    <div class="form-group">
        <router-link :to="{name: 'page.create'}" class="btn btn-success">Добавть страницу</router-link>
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
                    <td><router-link :to="{ name: 'page.edit', params: { id: row.id } }"><i class="fa fa-pencil" aria-hidden="true"></i></router-link></td>
                    <td><a :id="'delete-' + row.id" data-confirm="Удалить страницу?" @click="destroy({id: row.id, key: 'page'})"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-sm-6">
        Страница {{rows.current_page}} из {{rows.last_page}}. Запись c {{rows.from}} по {{rows.to}} из {{rows.total}}
    </div>
    <div class="col-sm-6">
        <ul class="pagination" style="margin: 2px 0; float: right;" v-if="rows.last_page > 1">
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
</template>

<script>
    import Vue from 'vue'
    import DataTableMixin from './../../DataTableMixin'

    export default {
        data(){
            return {
                fields: [
                    {key: 'title', name: 'Заголовок', type: 'string'},
                    {key: 'slug', name: 'URL', type: 'string'},
                    // {key: 'content', name: 'Содержание', type: 'string'},
                ],
                rowsRoute: lroutes.api.admin.ecommerce.page.index,
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