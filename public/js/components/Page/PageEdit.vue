<template>
    <layout>
        <h1 slot="title">Редкатровать страницу</h1>
        <template slot="panel-title">Редактирование страницу</template>
        <template slot="body">
            <page-form></page-form>
        </template>
    </layout>
</template>

<script>
    import Layout from './../Layout.vue'
    import PageForm from './Form.vue'

    export default {
        components:{
            Layout,
            PageForm
        }
    }
</script>