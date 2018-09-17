<template>
    <layout>
        <h1 slot="title">Редкатровать опцию</h1>
        <template slot="panel-title">Редактирование опцию</template>
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