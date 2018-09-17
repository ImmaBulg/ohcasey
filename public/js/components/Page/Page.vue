<template>
    <layout>
        <h1 slot="title">Список страниц</h1>
        <template slot="panel-title">Страницы</template>
        <template slot="body">
            <data-table></data-table>
        </template>
    </layout>
</template>

<script>
    import Layout from './../Layout.vue'
    import DataTable from './DataTable.vue'

    export default {
        components:{
            Layout,
            DataTable
        }
    }
</script>