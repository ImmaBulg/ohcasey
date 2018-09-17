<template>
    <layout>
        <h1 slot="title">Новый тип товара</h1>
        <template slot="panel-title">Добавить тип тована</template>
        <template slot="body">
            <option-group-form></option-group-form>
        </template>
    </layout>
</template>

<script>
    import Layout from './../Layout.vue'
    import OptionGroupForm from './Form.vue'

    export default {
        components:{
            Layout,
            OptionGroupForm
        }
    }
</script>