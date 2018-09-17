<template>
    <layout>
        <h1 slot="title">Список тегов</h1>
        <template slot="panel-title">Значения тегов</template>
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