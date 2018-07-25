<template>
    <layout>
        <h1 slot="title">Редкатровать настройки</h1>
        <template slot="panel-title">Редактирование настройки</template>
        <template slot="body">
            <setting-form></setting-form>
        </template>
    </layout>
</template>

<script>
    import Layout from './../Layout.vue'
    import SettingForm from './Form.vue'

    export default {
        components:{
            Layout,
            SettingForm
        }
    }
</script>