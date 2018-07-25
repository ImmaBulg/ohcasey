<template>
    <layout>
        <h1 slot="title">Новая тип</h1>
        <template slot="panel-title">Добавить тип</template>
        <template slot="body">
            <category-form></category-form>
        </template>
    </layout>
</template>

<script>
    import Layout from '../../Layout.vue'
    import CategoryForm from './Form.vue'

    export default {
        components: {
            Layout,
            CategoryForm
        }
    }
</script>