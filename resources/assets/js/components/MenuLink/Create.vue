<template>
    <layout>
        <h1 slot="title">Новый пункт меню</h1>
        <template slot="panel-title">Добавить пункт пеню</template>
        <template slot="body">
            <menu-form></menu-form>
        </template>
    </layout>
</template>

<script>
    import Layout from '../Layout.vue'
    import MenuForm from './Form.vue'

    export default {
        components: {
            Layout,
            MenuForm
        }
    }
</script>