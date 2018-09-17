<template>
<div>
    <!-- <div class="form-group">
        <router-link :to="{name: 'related_option.create'}" class="btn btn-success" disbled>Добавть связанную опцию</router-link>
        <a class="btn btn-success" disabled="disabled">Добавть связанную опцию</a>
    </div> -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>id</th>
                    <th v-for="field in fields">{{field.name}}</th>
                    <!-- <th><i class="fa fa-picture-o" aria-hidden="true"></i></th> -->
                    <th><i class="fa fa-search" aria-hidden="true"></i></th>
                    <!-- <th><i class="fa fa-pencil" aria-hidden="true"></i></th> -->
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
                    <td><router-link :to="{ name: 'related_option.show', params: { id: row.id } }"><i class="fa fa-search" aria-hidden="true"></i></router-link></td>
                    <!-- <td><router-link :to="{ name: 'option.edit', params: { id: row.id } }"><i class="fa fa-pencil" aria-hidden="true"></i></router-link></td> -->
                    <!-- <td><a :id="'delete-' + row.id" data-confirm="Удалить опцию?" @click="destroy(row.id)"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td> -->
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
                    {key: 'name', name: 'Название', type: 'string'},
                ],
                rowsRoute: lroutes.api.admin.ecommerce.related_option.index,
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