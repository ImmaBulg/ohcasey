<template>
    <layout>
        <h1 slot="title">Редкатровать транзакцию</h1>
        <template slot="panel-title">Редактирование транзакции</template>
        <template slot="body">
            <transaction-form></transaction-form>
        </template>
    </layout>
</template>

<script>
    import Layout from '../../Layout.vue'
    import TransactionForm from './Form.vue'

    export default {
        components: {
            Layout,
            TransactionForm
        }
    }
</script>