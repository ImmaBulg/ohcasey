<template>
    <layout>
        <h1 slot="title">Новое значение опции</h1>
        <template slot="panel-title">Добавить значение опиции</template>
        <template slot="body">
            <optionvalue-form></optionvalue-form>
        </template>
    </layout>
</template>

<script>
    import Layout from './../Layout.vue'
    import OptionvalueForm from './Form.vue'

    export default {
        components:{
            Layout,
            OptionvalueForm
        }
    }
</script>