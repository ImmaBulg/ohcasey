<template>
    <layout>
        <h1 slot="title">Новая настройка</h1>
        <template slot="panel-title">Добавить настройку</template>
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