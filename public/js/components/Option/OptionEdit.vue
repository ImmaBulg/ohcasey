<template>
    <layout>
        <h1 slot="title">Редкатровать опцию</h1>
        <template slot="panel-title">Редактирование опцию</template>
        <template slot="body">
            <option-form></option-form>
        </template>
    </layout>
</template>

<script>
    import Layout from './../Layout.vue'
    import OptionForm from './Form.vue'

    export default {
        components:{
            Layout,
            OptionForm
        }
    }
</script>