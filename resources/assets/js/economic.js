import Vue from 'vue'
import VueRouter from 'vue-router'

import EconomicIndex from './components/economic/Index.vue'
import TransactionTypeIndex from './components/economic/transaction_type/Index.vue'
import TransactionTypeEdit from './components/economic/transaction_type/Edit.vue'
import TransactionTypeCreate from './components/economic/transaction_type/Create.vue'
import TransactionIndex from './components/economic/transaction/Index.vue'
import TransactionReport from './components/economic/transaction/Report.vue'
import TransactionEdit from './components/economic/transaction/Edit.vue'
import TransactionCreate from './components/economic/transaction/Create.vue'
import TransactionCategoryIndex from './components/economic/transaction_category/Index.vue'
import TransactionCategoryEdit from './components/economic/transaction_category/Edit.vue'
import TransactionCategoryCreate from './components/economic/transaction_category/Create.vue'

const routes = [
    { path: lroutes.admin.economic.index, name: 'economic.index', component: EconomicIndex },
    { path: lroutes.admin.economic.transaction_type.index, name: 'transaction_type.index', component: TransactionTypeIndex },
    { path: lroutes.admin.economic.transaction_type.create, name: 'transaction_type.create', component: TransactionTypeCreate },
    { path: lroutes.admin.economic.transaction_type.edit.replace(/{.*}/, ':id'), name: 'transaction_type.edit', component: TransactionTypeEdit },
    { path: lroutes.admin.economic.transaction.index, name: 'transaction.index', component: TransactionIndex },
    { path: lroutes.admin.economic.transaction.report, name: 'transaction.report', component: TransactionReport },
    { path: lroutes.admin.economic.transaction.create, name: 'transaction.create', component: TransactionCreate },
    { path: lroutes.admin.economic.transaction.edit.replace(/{.*}/, ':id'), name: 'transaction.edit', component: TransactionEdit },
    { path: lroutes.admin.economic.transaction_category.index, name: 'transaction_category.index', component: TransactionCategoryIndex },
    { path: lroutes.admin.economic.transaction_category.create, name: 'transaction_category.create', component: TransactionCategoryCreate },
    { path: lroutes.admin.economic.transaction_category.edit.replace(/{.*}/, ':id'), name: 'transaction_category.edit', component: TransactionCategoryEdit }
];

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes
});

const app = new Vue({
    el: '#app',
    router
});

export default app