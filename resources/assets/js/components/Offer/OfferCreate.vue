<template>
    <layout>
        <h1 slot="title">Создание Торговое предложение</h1>
        <template slot="panel-title">Создание Торговое предложение</template>
        <template slot="body">
            <offer-form></offer-form>
        </template>
    </layout>
</template>

<script>
    import Layout from './../Layout.vue'
    import OfferForm from './Form.vue'

    export default {
        components:{
            Layout,
            OfferForm
        }
    }
</script>