<template>
    <layout>
        <h1 slot="title">Редкатровать товар</h1>
        <template slot="panel-title">Редактирование товара</template>
        <template slot="body">
            <div class="row">
                <div class="col-md-6">
                    <product-form ref="pForm" :is_create="0"></product-form>
                </div>
                <div class="col-md-6">
                    <offers></offers>
                </div>
            </div>
        </template>
    </layout>
</template>

<script>
    import Layout from './../Layout.vue'
    import ProductForm from './Form.vue'
    import Offers from '../Offer/DataTable.vue'

    export default {
        components: {
            Layout,
            ProductForm,
            Offers
        }
    }
</script>