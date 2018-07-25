<template>
    <layout>
        <h1 slot="title">Экономика</h1>
        <template slot="body">
            <router-link :to="{ name: 'transaction_category.index' }">Список типов статей</router-link>
            <br>
            <router-link :to="{ name: 'transaction_type.index' }">Список статей</router-link>
            <br>
            <router-link :to="{ name: 'transaction.index' }">Список транзакций</router-link>
            <br>
            <router-link :to="{ name: 'transaction.report' }">Движение денежных средств</router-link>
        </template>
    </layout>
</template>


<script>
    import Layout from '../Layout.vue'

    export default {
        components: {
            Layout
        }
    }
</script>