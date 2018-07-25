<template>
    <layout>
        <h1 slot="title">Редкатровать статью</h1>
        <template slot="panel-title">Редактирование статьи</template>
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