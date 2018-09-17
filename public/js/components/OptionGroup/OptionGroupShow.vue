<template>
    <layout>
        <h1 slot="title">Значения связанной опции</h1>
        <template slot="panel-title">Значения связанной опции</template>
        <template slot="body">
            <data-table></data-table>
        </template>
    </layout>
</template>

<script>
    import Layout from './../Layout.vue'
    import DataTable from './OptionGroupTable.vue'

    export default {
        components:{
            Layout,
            DataTable
        }
    }
</script>