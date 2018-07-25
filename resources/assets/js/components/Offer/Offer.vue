<template>
    <layout>
        <h1 slot="title">Торговые предложения</h1>
        <template slot="panel-title">Торговые предложения</template>
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