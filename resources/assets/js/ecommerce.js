import Vue from 'vue'
import VueRouter from 'vue-router'

import Product from './components/Product/Product.vue'
import ProductCreate from './components/Product/ProductCreate.vue'
import ProductEdit from './components/Product/ProductEdit.vue'

import Option from './components/Option/Option.vue'
import OptionCreate from './components/Option/OptionCreate.vue'
import OptionEdit from './components/Option/OptionEdit.vue'

import Setting from './components/Setting/Setting.vue'
import SettingCreate from './components/Setting/SettingCreate.vue'
import SettingEdit from './components/Setting/SettingEdit.vue'

import Tag from './components/Tag/Tag.vue'
import TagCreate from './components/Tag/TagCreate.vue'
import TagEdit from './components/Tag/TagEdit.vue'

import OptionValue from './components/OptionValue/OptionValue.vue'
import OptionValueCreate from './components/OptionValue/OptionValueCreate.vue'
import OptionValueEdit from './components/OptionValue/OptionValueEdit.vue'

import OptionGroup from './components/OptionGroup/OptionGroup.vue'
import OptionGroupCreate from './components/OptionGroup/OptionGroupCreate.vue'
import OptionGroupEdit from './components/OptionGroup/OptionGroupEdit.vue'

// import OptionGroupShow from './components/OptionGroup/OptionGroupShow.vue'

import Offer from './components/Offer/Offer.vue'
import OfferCreate from './components/Offer/OfferCreate.vue'
import OfferEdit from './components/Offer/OfferEdit.vue'

import Page from './components/Page/Page.vue'
import PageCreate from './components/Page/PageCreate.vue'
import PageEdit from './components/Page/PageEdit.vue'

const routes = [
    { path: lroutes.admin.ecommerce.product.index, name: 'product.index', component: Product },
    { path: lroutes.admin.ecommerce.product.create, name: 'product.create', component: ProductCreate },
    { path: lroutes.admin.ecommerce.product.edit.replace(/{.*}/, ':id'), name: 'product.edit', component: ProductEdit },

    { path: lroutes.admin.ecommerce.option.index, name: 'option.index', component: Option },
    { path: lroutes.admin.ecommerce.option.create, name: 'option.create', component: OptionCreate },
    { path: lroutes.admin.ecommerce.option.edit.replace(/{.*}/, ':id'), name: 'option.edit', component: OptionEdit },

    { path: lroutes.admin.ecommerce.setting.index, name: 'setting.index', component: Setting },
    { path: lroutes.admin.ecommerce.setting.create, name: 'setting.create', component: SettingCreate },
    { path: lroutes.admin.ecommerce.setting.edit.replace(/{.*}/, ':id'), name: 'setting.edit', component: SettingEdit },

    { path: lroutes.admin.ecommerce.tag.index, name: 'tag.index', component: Tag },
    { path: lroutes.admin.ecommerce.tag.create, name: 'tag.create', component: TagCreate },
    { path: lroutes.admin.ecommerce.tag.edit.replace(/{.*}/, ':id'), name: 'tag.edit', component: TagEdit },

    { path: lroutes.admin.ecommerce.option_value.index, name: 'option_value.index', component: OptionValue },
    { path: lroutes.admin.ecommerce.option_value.create, name: 'option_value.create', component: OptionValueCreate },
    { path: lroutes.admin.ecommerce.option_value.edit.replace(/{.*}/, ':id'), name: 'option_value.edit', component: OptionValueEdit },

    { path: lroutes.admin.ecommerce.option_group.index, name: 'option_group.index', component: OptionGroup },
    { path: lroutes.admin.ecommerce.option_group.create, name: 'option_group.create', component: OptionGroupCreate },
    { path: lroutes.admin.ecommerce.option_group.show.replace(/{.*}/, ':id'), name: 'option_group.show', component: Offer },
    { path: lroutes.admin.ecommerce.option_group.edit.replace(/{.*}/, ':id'), name: 'option_group.edit', component: OptionGroupEdit },

    { path: lroutes.admin.ecommerce.offer.create, name: 'offer.create', component: OfferCreate },
    { path: lroutes.admin.ecommerce.offer.edit.replace(/{.*}/, ':id'), name: 'offer.edit', component: OfferEdit },

    { path: lroutes.admin.ecommerce.page.index, name: 'page.index', component: Page },
    { path: lroutes.admin.ecommerce.page.create, name: 'page.create', component: PageCreate },
    { path: lroutes.admin.ecommerce.page.edit.replace(/{.*}/, ':id'), name: 'page.edit', component: PageEdit },
];

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes
});

const app = new Vue({
    router
}).$mount('#app');

export default app