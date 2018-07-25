import Vue from 'vue'
import VueRouter from 'vue-router'

import MenuLinkIndex from './components/MenuLink/Index.vue'
import MenuLinkEdit from './components/MenuLink/Edit.vue'
import MenuLinkCreate from './components/MenuLink/Create.vue'

const routes = [
    { path: lroutes.admin.menu_links.index, name: 'menu_links.index', component: MenuLinkIndex },
    { path: lroutes.admin.menu_links.create, name: 'menu_links.create', component: MenuLinkCreate },
    { path: lroutes.admin.menu_links.edit.replace(/{.*}/, ':id'), name: 'menu_links.edit', component: MenuLinkEdit }
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