<template>
    <div>
        <div class="form-group">
            <router-link :to="{name: 'transaction.create'}" class="btn btn-success">Добавть транзакцию</router-link>
        </div>
        <div class="row">
            <div class="col-sm-5 left">
                <div class="form-group input-group">
                    <input @keyup.enter="updateTable()" type="text" class="form-control" placeholder="Поиск по сумме" v-model="filters.amount">
                    <span class="input-group-btn">
                        <a @click="updateTable()" class="btn btn-primary">
                            <i class="fa fa-search"></i>
                        </a>
                    </span>
                </div>
            </div>

            <div class="col-sm-5">
                <div class="form-group">
                    <select class="form-control" v-model="filters.transaction_type_id" @change="updateTable()">
                        <option v-for="option in transactionTypeOptions" :value="option.value" :selected="option.value == null" v-html="option.label"></option>
                    </select>
                </div>
            </div>
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
                    <tr v-for="row in transactions.data">
                        <td>{{row.id}}</td>
                        <td v-for="field in fields">
                            <template v-if="field.type == 'string'">{{row[field.key]}}</template>
                            <template v-if="field.type == 'boolean'">{{row[field.key] == true ? 'Да':'Нет'}}</template>
                            <template v-if="field.type == 'transaction_type'">{{row.type.name}}</template>
                        </td>
                        <td>
                            <router-link :to="{ name: 'transaction.edit', params: { id: row.id } }">изменить</router-link>
                            <a href="#" @click="removeTransaction(row)">удалить</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6">
            Страница {{transactions.current_page}} из {{transactions.last_page}}. Запись c {{transactions.from}} по {{transactions.to}} из {{transactions.total}}
        </div>
        <div class="col-sm-6">
            <ul class="pagination" style="margin: 2px 0; float: right;" v-if="transactions.last_page > 1">
                <li v-if="transactions.prev_page_url"><a @click="changePage(1)">&#171;</a></li>
                <li v-if="transactions.prev_page_url"><a @click="changePage(transactions.current_page - 1)">&#8249;</a></li>
                <li class="paginate_button" v-for="page in pagesNumbers" :class="[(page == transactions.current_page) ? 'active' : '']">
                    <a @click="changePage(page)">{{page}}</a>
                </li>
                <li v-if="transactions.next_page_url"><a @click="changePage(transactions.current_page + 1)">&#8250;</a></li>
                <li v-if="transactions.next_page_url"><a @click="changePage(transactions.last_page)">&#187;</a></li>
            </ul>
        </div>
    </div>
</template>

<script>
    import Vue from 'vue'
    import axios from 'axios'
    import vuestore from '../../../vuestore'
    import commons from './commons';

    export default {
        data() {
            let transactionTypeOptions = [{value: '', label: 'Все'}];
            commons.transactionTypeLoader().then((response) => {
                response.data.data.forEach((transactionType) => {
                    transactionTypeOptions.push({
                        value: transactionType.id,
                        label: transactionType.name
                    });
                });
            });

            return {
                vuestore: vuestore,
                lroutes,
                fields: [
                    {key: 'transaction_type_id', name: 'Тип транзакции',  type: 'transaction_type'},
                    {key: 'amount',              name: 'Сумма',           type: 'string'},
                    {key: 'datetime',            name: 'Дата',            type: 'string'},
                    {key: 'order_id',            name: 'Заказ',           type: 'string'},
                    {key: 'payment_id',          name: 'Оплата',          type: 'string'},
                    {key: 'comment',             name: 'Комментарий',     type: 'string'}
                ],
                transactions: {
                    data: [],
                    current_page: 1
                },
                filters: {
                    order_id: this.$route.query.order_id ? this.$route.query.order_id : '',
                    amount: this.$route.query.amount ? this.$route.query.amount : '',
                    transaction_type_id: this.$route.query.transaction_type_id ? this.$route.query.transaction_type_id : '',
                    payment_id: this.$route.query.payment_id ? this.$route.query.payment_id : ''
                },
                transactionTypeOptions,
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

                let params = this.filters;
                params.page = this.transactions.current_page;
                params = JSON.parse(JSON.stringify(params));

                let settings = {
                    method: 'get',
                    path: lroutes.api.admin.transaction.index,
                    params
                };

                this.$root.$router.push({name: 'transaction.index', query: params});

                this.vuestore.request(settings).then((response) => {
                    this.transactions = response.data;
                    this.vuestore.loading = false;
                }).catch((response) => {
                    this.vuestore.loading = false;
                });
            },
            removeTransaction(transactionType) {
                if(this.vuestore.loading) return;
                else this.vuestore.loading = true;
                let deleteParams = {
                    method: 'delete',
                    path: lroutes.api.admin.transaction.destroy.replace(/{.*}/, transactionType.id),
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
                this.transactions.current_page = page;
                this.updateTable()
            }
        },
        computed: {
            pagesNumbers() {
                if (!this.transactions.to) {
                    return [];
                }
                var from = this.transactions.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }
                var to = from + (this.offset * 2);
                if (to >= this.transactions.last_page) {
                    to = this.transactions.last_page;
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