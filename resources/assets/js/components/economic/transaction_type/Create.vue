<template>
    <layout>
        <h1 slot="title">Новая статья</h1>
        <template slot="panel-title">Добавить статью</template>
        <template slot="body">
            <type-form></type-form>
        </template>
    </layout>
</template>

<script>
    import Layout from '../../Layout.vue'
    import TypeForm from './Form.vue'

    export default {
        components: {
            Layout,
            TypeForm
        }
    }
</script>