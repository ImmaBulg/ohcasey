<template>
    <layout>
        <h1 slot="title">Новая страница</h1>
        <template slot="panel-title">Добавить страницу</template>
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