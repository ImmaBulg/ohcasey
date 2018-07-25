<template>
    <layout>
        <h1 slot="title">Список ссылок</h1>
        <template slot="body">
            <data-table></data-table>
        </template>
    </layout>
</template>

<script>
    import Layout from '../Layout.vue'
    import DataTable from './DataTable.vue'

    export default {
        components: {
            Layout,
            DataTable
        }
    }
</script>