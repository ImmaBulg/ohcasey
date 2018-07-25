<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'menu_links.create'}" class="btn btn-success">Добавть пункт меню</router-link>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr>
                    <th>id</th>
                    <th v-for="field in fields">{{field.name}}</th>
                    <th>*</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="row in menu_links.data">
                    <td>{{row.id}}</td>
                    <td v-for="field in fields">
                        <template v-if="field.type == 'string'">{{row[field.key]}}</template>
                        <template v-if="field.type == 'parent_id'">{{row.parent ? row.parent.name : ''}}</template>
                    </td>
                    <td>
                        <router-link :to="{ name: 'menu_links.edit', params: { id: row.id } }">изменить</router-link>
                        <a href="#" @click="removemenuLink(row)">удалить</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6">
            Страница {{menu_links.current_page}} из {{menu_links.last_page}}. Запись c {{menu_links.from}} по {{menu_links.to}} из {{menu_links.total}}
        </div>
        <div class="col-sm-6">
            <paginator :rows="menu_links" v-on:update_table="updateTable"></paginator>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import axios from 'axios'
    import vuestore from './../../vuestore'
    import paginator from './../paginator.vue'

    export default {
        components: {
            paginator
        },
        data() {
            return {
                lroutes,
                vuestore: vuestore,
                fields: [
                    {key: 'name',      name: 'Название',   type: 'string'},
                    {key: 'sort',      name: 'Сортировка', type: 'string'},
                    {key: 'url',       name: 'Адрес',      type: 'string'},
                    {key: 'parent_id', name: 'Родитель',   type: 'parent_id'}
                ],
                menu_links: [],
                offset: 4
            }
        },
        created() {
            this.updateTable();
        },
        methods: {
            updateTable() {
                if(this.vuestore.loading) return;
                else this.vuestore.loading = true;
                let settings = {
                    method: 'get',
                    path: lroutes.api.admin.menu_links.index,
                    params: {
                        page: this.menu_links.current_page
                    }
                };
                this.vuestore.request(settings).then((response) => {
                    this.menu_links = response.data;
                    this.vuestore.loading = false;
                }).catch((response) => {
                    window.console.log(response);
                    this.vuestore.loading = false;
                });
            },
            removemenuLink(menuLink) {
                if(this.vuestore.loading) return;
                else this.vuestore.loading = true;
                let deleteParams = {
                    method: 'delete',
                    path: lroutes.api.admin.menu_links.destroy.replace(/{.*}/, menuLink.id),
                    params: {
                    }
                };
                this.vuestore.request(deleteParams).then((response) => {
                    this.vuestore.loading = false;
                    this.updateTable();
                }).catch((response) => {
                    this.vuestore.loading = false;
                    alert('Ошибка удаления');
                });

            },
            changePage(page) {
                this.menu_links.current_page = page;
                this.updateTable()
            }
        },
        computed: {
            pagesNumbers() {
                if (!this.menu_links.to) {
                    return [];
                }
                var from = this.menu_links.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.menu_links.last_page) {
                    to = this.menu_links.last_page;
                }
                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        }
    }
</script>

<style>
    .category.label{
        margin-right:5px;
    }
</style>