<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'transaction_type.create'}" class="btn btn-success">Добавть статью</router-link>
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
                    <tr v-for="row in transaction_types.data">
                        <td>{{row.id}}</td>
                        <td v-for="field in fields">
                            <template v-if="field.type == 'string'">{{row[field.key]}}</template>
                            <template v-if="field.type == 'transaction_category_id'">{{row.category ? row.category.name : ''}}</template>
                        </td>
                        <td>
                            <router-link :to="{ name: 'transaction_type.edit', params: { id: row.id } }">изменить</router-link>
                            <a href="#" @click="removeTransactionType(row)">удалить</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6">
            Страница {{transaction_types.current_page}} из {{transaction_types.last_page}}. Запись c {{transaction_types.from}} по {{transaction_types.to}} из {{transaction_types.total}}
        </div>
        <div class="col-sm-6">
            <ul class="pagination" style="margin: 2px 0; float: right;" v-if="transaction_types.last_page > 1">
                <li v-if="transaction_types.prev_page_url"><a @click="changePage(1)">&#171;</a></li>
                <li v-if="transaction_types.prev_page_url"><a @click="changePage(transaction_types.current_page - 1)">&#8249;</a></li>
                <li class="paginate_button" v-for="page in pagesNumbers" :class="[(page == transaction_types.current_page) ? 'active' : '']">
                    <a @click="changePage(page)">{{page}}</a>
                </li>
                <li v-if="transaction_types.next_page_url"><a @click="changePage(transaction_types.current_page + 1)">&#8250;</a></li>
                <li v-if="transaction_types.next_page_url"><a @click="changePage(transaction_types.last_page)">&#187;</a></li>
            </ul>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import axios from 'axios'
    import vuestore from '../../../vuestore'

    export default {
        data() {
            return {
                vuestore: vuestore,
                lroutes,
                fields: [
                    {key: 'name', name: 'Название', type: 'string'},
                    {key: 'transaction_category_id', name: 'Тип', type: 'transaction_category_id'}
                ],
                transaction_types: {
                    data: []
                },
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
                    path: lroutes.api.admin.transaction_type.index,
                    params: {
                        page: this.transaction_types.current_page
                    }
                };
                this.vuestore.request(settings).then((response) => {
                    this.transaction_types = response.data;
                    this.vuestore.loading = false;
                }).catch((response) => {
                    window.console.log(response);
                    this.vuestore.loading = false;
                });
            },
            removeTransactionType(transactionType) {
                if(this.vuestore.loading) return;
                else this.vuestore.loading = true;
                let deleteParams = {
                    method: 'delete',
                    path: lroutes.api.admin.transaction_type.destroy.replace(/{.*}/, transactionType.id),
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
                this.transaction_types.current_page = page;
                this.updateTable()
            }
        },
        computed: {
            pagesNumbers() {
                if (!this.transaction_types.to) {
                    return [];
                }
                var from = this.transaction_types.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.transaction_types.last_page) {
                    to = this.transaction_types.last_page;
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