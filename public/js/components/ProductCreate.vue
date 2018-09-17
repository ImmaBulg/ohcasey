<template>
    <layout>
        <h1 slot="title">Новый товар</h1>
        <template slot="panel-title">Добавить товар</template>
        <template slot="body">
            <product-form></product-form>
        </template>
    </layout>
</template>

<script>
    import Layout from './Layout.vue'
    import ProductForm from './Form.vue'

    export default {
        components:{
            Layout,
            ProductForm
        }
    }
</script>